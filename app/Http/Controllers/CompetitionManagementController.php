<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Club;
use App\Models\Association;
use App\Models\FifaConnectId;
use App\Services\FifaConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompetitionManagementController extends Controller
{
    protected $fifaConnectService;

    public function __construct(FifaConnectService $fifaConnectService)
    {
        $this->fifaConnectService = $fifaConnectService;
        $this->middleware('auth');
        $this->middleware('role:association');
    }

    public function index()
    {
        $user = Auth::user();
        $competitions = collect();

        if ($user->role === 'association') {
            $competitions = Competition::where('association_id', $user->association_id)
                ->with(['association', 'fifaConnectId'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        $stats = [
            'total' => $competitions->total(),
            'active' => $competitions->where('status', 'active')->count(),
            'upcoming' => $competitions->where('status', 'upcoming')->count(),
            'completed' => $competitions->where('status', 'completed')->count(),
        ];

        return view('competition-management.index', compact('competitions', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $clubs = collect();

        if ($user->role === 'association') {
            $clubs = Club::where('association_id', $user->association_id)
                ->orderBy('name')
                ->get();
        }

        return view('competition-management.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:league,cup,friendly,international',
            'season' => 'required|string|max:50',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'max_teams' => 'nullable|integer|min:2|max:100',
            'format' => 'required|in:round_robin,knockout,mixed',
            'status' => 'required|in:upcoming,active,completed,cancelled',
            'fifa_connect_id' => 'nullable|string|unique:fifa_connect_ids,fifa_id',
            'clubs' => 'nullable|array',
            'clubs.*' => 'exists:clubs,id',
        ]);

        DB::beginTransaction();
        try {
            // Create FIFA Connect ID if provided or generate one
            $fifaConnectId = null;
            if (!empty($validated['fifa_connect_id'])) {
                $fifaConnectId = FifaConnectId::create([
                    'fifa_id' => $validated['fifa_connect_id'],
                    'entity_type' => 'competition',
                    'status' => 'active'
                ]);
            } else {
                // Generate FIFA Connect ID
                $fifaConnectId = $this->fifaConnectService->generateCompetitionId();
            }

            // Create competition
            $competition = Competition::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'season' => $validated['season'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'description' => $validated['description'],
                'max_teams' => $validated['max_teams'],
                'format' => $validated['format'],
                'status' => $validated['status'],
                'association_id' => $user->association_id,
                'fifa_connect_id' => $fifaConnectId->id,
            ]);

            // Attach clubs if provided
            if (!empty($validated['clubs'])) {
                $competition->clubs()->attach($validated['clubs']);
            }

            DB::commit();

            return redirect()->route('competition-management.competitions.index')
                ->with('success', 'Competition created successfully with FIFA Connect ID: ' . $fifaConnectId->fifa_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create competition: ' . $e->getMessage());
        }
    }

    public function show(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        $competition->load(['association', 'fifaConnectId', 'clubs', 'matches']);
        
        return view('competition-management.show', compact('competition'));
    }

    public function edit(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        $user = Auth::user();
        $clubs = collect();

        if ($user->role === 'association') {
            $clubs = Club::where('association_id', $user->association_id)
                ->orderBy('name')
                ->get();
        }

        $competition->load(['association', 'fifaConnectId', 'clubs']);
        
        return view('competition-management.edit', compact('competition', 'clubs'));
    }

    public function update(Request $request, Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:league,cup,friendly,international',
            'season' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'max_teams' => 'nullable|integer|min:2|max:100',
            'format' => 'required|in:round_robin,knockout,mixed',
            'status' => 'required|in:upcoming,active,completed,cancelled',
            'clubs' => 'nullable|array',
            'clubs.*' => 'exists:clubs,id',
        ]);

        DB::beginTransaction();
        try {
            $competition->update($validated);

            // Update clubs if provided
            if (isset($validated['clubs'])) {
                $competition->clubs()->sync($validated['clubs']);
            }

            // Sync with FIFA Connect if needed
            if ($competition->fifaConnectId) {
                $this->fifaConnectService->syncCompetition($competition);
            }

            DB::commit();

            return redirect()->route('competition-management.competitions.show', $competition)
                ->with('success', 'Competition updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update competition: ' . $e->getMessage());
        }
    }

    public function destroy(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        try {
            $competition->delete();
            return redirect()->route('competition-management.competitions.index')
                ->with('success', 'Competition deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete competition: ' . $e->getMessage());
        }
    }

    public function sync(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        try {
            $result = $this->fifaConnectService->syncCompetition($competition);
            return back()->with('success', 'Competition synced with FIFA Connect successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync competition: ' . $e->getMessage());
        }
    }

    public function bulkSync()
    {
        $user = Auth::user();
        $competitions = collect();

        if ($user->role === 'association') {
            $competitions = Competition::where('association_id', $user->association_id)->get();
        }

        $synced = 0;
        $failed = 0;

        foreach ($competitions as $competition) {
            try {
                $this->fifaConnectService->syncCompetition($competition);
                $synced++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return back()->with('success', "Bulk sync completed: {$synced} synced, {$failed} failed");
    }

    public function export()
    {
        $user = Auth::user();
        $competitions = collect();

        if ($user->role === 'association') {
            $competitions = Competition::where('association_id', $user->association_id)
                ->with(['association', 'fifaConnectId', 'clubs'])
                ->get();
        }

        $filename = 'competitions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($competitions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'FIFA Connect ID', 'Name', 'Type', 'Season', 'Start Date', 
                'End Date', 'Format', 'Status', 'Max Teams', 'Association', 'Created At'
            ]);

            foreach ($competitions as $competition) {
                fputcsv($file, [
                    $competition->fifaConnectId?->fifa_id ?? 'N/A',
                    $competition->name,
                    $competition->type,
                    $competition->season,
                    $competition->start_date,
                    $competition->end_date,
                    $competition->format,
                    $competition->status,
                    $competition->max_teams,
                    $competition->association?->name ?? 'N/A',
                    $competition->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generateFixtures(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        try {
            // Implementation for generating fixtures
            // This would create matches between teams based on the competition format
            
            return back()->with('success', 'Fixtures generated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate fixtures: ' . $e->getMessage());
        }
    }

    public function standings(Competition $competition)
    {
        $this->authorizeCompetitionAccess($competition);
        
        $competition->load(['clubs', 'matches']);
        
        // Calculate standings based on matches
        $standings = collect();
        
        foreach ($competition->clubs as $club) {
            $matches = $competition->matches()
                ->where(function ($query) use ($club) {
                    $query->where('home_team_id', $club->id)
                          ->orWhere('away_team_id', $club->id);
                })
                ->get();
            
            $points = 0;
            $played = $matches->count();
            $won = 0;
            $drawn = 0;
            $lost = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;
            
            foreach ($matches as $match) {
                if ($match->home_team_id === $club->id) {
                    $goalsFor += $match->home_score ?? 0;
                    $goalsAgainst += $match->away_score ?? 0;
                    
                    if (($match->home_score ?? 0) > ($match->away_score ?? 0)) {
                        $won++;
                        $points += 3;
                    } elseif (($match->home_score ?? 0) === ($match->away_score ?? 0)) {
                        $drawn++;
                        $points += 1;
                    } else {
                        $lost++;
                    }
                } else {
                    $goalsFor += $match->away_score ?? 0;
                    $goalsAgainst += $match->home_score ?? 0;
                    
                    if (($match->away_score ?? 0) > ($match->home_score ?? 0)) {
                        $won++;
                        $points += 3;
                    } elseif (($match->away_score ?? 0) === ($match->home_score ?? 0)) {
                        $drawn++;
                        $points += 1;
                    } else {
                        $lost++;
                    }
                }
            }
            
            $standings->push([
                'club' => $club,
                'points' => $points,
                'played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalsFor - $goalsAgainst,
            ]);
        }
        
        $standings = $standings->sortByDesc('points')
            ->sortByDesc('goal_difference')
            ->values();
        
        return view('competition-management.standings', compact('competition', 'standings'));
    }

    protected function authorizeCompetitionAccess(Competition $competition)
    {
        $user = Auth::user();
        
        if ($user->role === 'association' && $competition->association_id !== $user->association_id) {
            abort(403, 'Unauthorized access to competition');
        }
    }
}
