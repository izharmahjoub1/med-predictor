<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Club;
use App\Models\Association;
use App\Models\FifaConnectId;
use App\Services\FifaConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PlayerRegistrationController extends Controller
{
    protected $fifaConnectService;

    public function __construct(FifaConnectService $fifaConnectService)
    {
        $this->fifaConnectService = $fifaConnectService;
        $this->middleware('auth');
        $this->middleware('role:club_admin,club_manager,club_medical,association_admin,association_registrar,association_medical,system_admin');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Debug logging
        \Log::info('PlayerRegistrationController::index called', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'club_id' => $user->club_id,
            'association_id' => $user->association_id
        ]);

        $players = collect();

        // Check if user has club-related role
        if (in_array($user->role, ['club_admin', 'club_manager', 'club_medical'])) {
            $players = Player::where('club_id', $user->club_id)
                ->with(['club', 'association', 'fifaConnectId'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } 
        // Check if user has association-related role
        elseif (in_array($user->role, ['association_admin', 'association_registrar', 'association_medical'])) {
            $players = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->with(['club', 'association', 'fifaConnectId'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }
        // For system admin, show all players
        elseif ($user->role === 'system_admin') {
            $players = Player::with(['club', 'association', 'fifaConnectId'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        // Debug logging for players
        \Log::info('Players query result', [
            'total_players' => $players->total(),
            'current_page_players' => $players->count(),
            'user_role' => $user->role
        ]);

        // Calculate stats based on the actual players collection
        $allPlayers = $players->getCollection();
        $stats = [
            'total' => $players->total(),
            'with_fifa_id' => $allPlayers->whereNotNull('fifaConnectId')->count(),
            'without_fifa_id' => $allPlayers->whereNull('fifaConnectId')->count(),
            'with_club' => $allPlayers->whereNotNull('club_id')->count(),
        ];

        // Debug logging for stats
        \Log::info('Stats calculated', $stats);

        return view('player-registration.index', compact('players', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $clubs = collect();

        if (in_array($user->role, ['association_admin', 'association_registrar', 'association_medical'])) {
            $clubs = Club::where('association_id', $user->association_id)
                ->orderBy('name')
                ->get();
        }

        return view('modules.player-registration.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'nationality' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'email' => 'nullable|email|unique:players,email',
            'phone' => 'nullable|string|max:20',
            'club_id' => in_array($user->role, ['association_admin', 'association_registrar', 'association_medical']) ? 'required|exists:clubs,id' : 'nullable',
            'fifa_connect_id' => 'nullable|string|unique:fifa_connect_ids,fifa_id',
        ]);

        DB::beginTransaction();
        try {
            // Set club_id based on user role
            if (in_array($user->role, ['club_admin', 'club_manager', 'club_medical'])) {
                $validated['club_id'] = $user->club_id;
            }

            // Create FIFA Connect ID if provided or generate one
            $fifaConnectId = null;
            if (!empty($validated['fifa_connect_id'])) {
                $fifaConnectId = FifaConnectId::create([
                    'fifa_id' => $validated['fifa_connect_id'],
                    'entity_type' => 'player',
                    'status' => 'active'
                ]);
            } else {
                // Generate FIFA Connect ID
                $fifaConnectId = $this->fifaConnectService->generatePlayerId();
            }

            // Create player
            $player = Player::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'nationality' => $validated['nationality'],
                'position' => $validated['position'],
                'jersey_number' => $validated['jersey_number'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'club_id' => $validated['club_id'],
                'fifa_connect_id' => $fifaConnectId->id,
            ]);

            DB::commit();

            return redirect()->route('player-registration.players.index')
                ->with('success', 'Player registered successfully with FIFA Connect ID: ' . $fifaConnectId->fifa_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to register player: ' . $e->getMessage());
        }
    }

    public function show(Player $player)
    {
        $this->authorizePlayerAccess($player);
        
        $player->load(['club', 'association', 'fifaConnectId', 'healthRecords']);
        
        return view('modules.player-registration.show', compact('player'));
    }

    public function edit(Player $player)
    {
        $this->authorizePlayerAccess($player);
        
        $user = Auth::user();
        $clubs = collect();

        if (in_array($user->role, ['association_admin', 'association_registrar', 'association_medical'])) {
            $clubs = Club::where('association_id', $user->association_id)
                ->orderBy('name')
                ->get();
        }

        $player->load(['club', 'association', 'fifaConnectId']);
        
        return view('modules.player-registration.edit', compact('player', 'clubs'));
    }

    public function update(Request $request, Player $player)
    {
        $this->authorizePlayerAccess($player);
        
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'nationality' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'email' => ['nullable', 'email', Rule::unique('players')->ignore($player->id)],
            'phone' => 'nullable|string|max:20',
            'club_id' => in_array($user->role, ['association_admin', 'association_registrar', 'association_medical']) ? 'required|exists:clubs,id' : 'nullable',
        ]);

        DB::beginTransaction();
        try {
            // Set club_id based on user role
            if (in_array($user->role, ['club_admin', 'club_manager', 'club_medical'])) {
                $validated['club_id'] = $user->club_id;
            }

            $player->update($validated);

            // Sync with FIFA Connect if needed
            if ($player->fifaConnectId) {
                $this->fifaConnectService->syncPlayer($player);
            }

            DB::commit();

            return redirect()->route('player-registration.players.show', $player)
                ->with('success', 'Player updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update player: ' . $e->getMessage());
        }
    }

    public function destroy(Player $player)
    {
        $this->authorizePlayerAccess($player);
        
        try {
            $player->delete();
            return redirect()->route('player-registration.players.index')
                ->with('success', 'Player deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete player: ' . $e->getMessage());
        }
    }

    public function sync(Player $player)
    {
        $this->authorizePlayerAccess($player);
        
        try {
            $result = $this->fifaConnectService->syncPlayer($player);
            return back()->with('success', 'Player synced with FIFA Connect successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync player: ' . $e->getMessage());
        }
    }

    public function bulkSync()
    {
        $user = Auth::user();
        $playerIds = [];

        if (in_array($user->role, ['club_admin', 'club_manager', 'club_medical'])) {
            $playerIds = Player::where('club_id', $user->club_id)->pluck('id')->toArray();
        } elseif (in_array($user->role, ['association_admin', 'association_registrar', 'association_medical'])) {
            $playerIds = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })->pluck('id')->toArray();
        } elseif ($user->role === 'system_admin') {
            $playerIds = Player::pluck('id')->toArray();
        }

        if (empty($playerIds)) {
            return back()->with('info', 'No players found to sync');
        }

        $results = $this->fifaConnectService->bulkSyncPlayers($playerIds);

        $message = "Bulk sync completed: {$results['success']} synced, {$results['failed']} failed";
        
        if (!empty($results['errors'])) {
            $message .= ". Errors: " . implode(', ', array_slice($results['errors'], 0, 3));
            if (count($results['errors']) > 3) {
                $message .= " and " . (count($results['errors']) - 3) . " more";
            }
        }

        return back()->with('success', $message);
    }

    public function export()
    {
        $user = Auth::user();
        $players = collect();

        if (in_array($user->role, ['club_admin', 'club_manager', 'club_medical'])) {
            $players = Player::where('club_id', $user->club_id)
                ->with(['club', 'association', 'fifaConnectId'])
                ->get();
        } elseif (in_array($user->role, ['association_admin', 'association_registrar', 'association_medical'])) {
            $players = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->with(['club', 'association', 'fifaConnectId'])
            ->get();
        } elseif ($user->role === 'system_admin') {
            $players = Player::with(['club', 'association', 'fifaConnectId'])->get();
        }

        $filename = 'players_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($players) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'FIFA Connect ID', 'First Name', 'Last Name', 'Date of Birth', 
                'Nationality', 'Position', 'Jersey Number', 'Height', 'Weight',
                'Email', 'Phone', 'Club', 'Status', 'Created At'
            ]);

            foreach ($players as $player) {
                fputcsv($file, [
                    $player->fifaConnectId?->fifa_id ?? 'N/A',
                    $player->first_name,
                    $player->last_name,
                    $player->date_of_birth,
                    $player->nationality,
                    $player->position,
                    $player->jersey_number,
                    $player->height,
                    $player->weight,
                    $player->email,
                    $player->phone,
                    $player->club?->name ?? 'N/A',
                    $player->status,
                    $player->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function authorizePlayerAccess(Player $player)
    {
        $user = Auth::user();
        
        if ($user->role === 'club' && $player->club_id !== $user->club_id) {
            abort(403, 'Unauthorized access to player');
        }
        
        if ($user->role === 'association') {
            $club = $player->club;
            if (!$club || $club->association_id !== $user->association_id) {
                abort(403, 'Unauthorized access to player');
            }
        }
    }
}
