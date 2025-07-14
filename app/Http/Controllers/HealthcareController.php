<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Player;
use App\Models\Club;
use App\Models\Association;
use App\Models\FifaConnectId;
use App\Services\FifaConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HealthcareController extends Controller
{
    protected $fifaConnectService;

    public function __construct(FifaConnectService $fifaConnectService)
    {
        $this->fifaConnectService = $fifaConnectService;
        $this->middleware('auth');
        $this->middleware('role:club,association');
    }

    public function index()
    {
        $user = Auth::user();
        $healthRecords = collect();

        if ($user->role === 'club') {
            $healthRecords = HealthRecord::whereHas('player', function ($query) use ($user) {
                $query->where('club_id', $user->club_id);
            })
            ->with(['player', 'player.club', 'fifaConnectId'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } elseif ($user->role === 'association') {
            $healthRecords = HealthRecord::whereHas('player.club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->with(['player', 'player.club', 'fifaConnectId'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        $stats = [
            'total' => $healthRecords->total(),
            'healthy' => $healthRecords->where('status', 'healthy')->count(),
            'injured' => $healthRecords->where('status', 'injured')->count(),
            'recovering' => $healthRecords->where('status', 'recovering')->count(),
        ];

        return view('healthcare.index', compact('healthRecords', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $players = collect();

        if ($user->role === 'club') {
            $players = Player::where('club_id', $user->club_id)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
        } elseif ($user->role === 'association') {
            $players = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        }

        return view('healthcare.create', compact('players'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'record_type' => 'required|in:medical_check,injury_report,recovery_update,health_assessment',
            'status' => 'required|in:healthy,injured,recovering,unfit',
            'description' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
            'restrictions' => 'nullable|string',
            'next_checkup' => 'nullable|date|after:today',
            'doctor_name' => 'nullable|string|max:255',
            'hospital_clinic' => 'nullable|string|max:255',
            'severity' => 'nullable|in:low,medium,high,critical',
            'fifa_connect_id' => 'nullable|string|unique:fifa_connect_ids,fifa_id',
        ]);

        // Verify player access
        $player = Player::findOrFail($validated['player_id']);
        $this->authorizePlayerAccess($player);

        DB::beginTransaction();
        try {
            // Create FIFA Connect ID if provided or generate one
            $fifaConnectId = null;
            if (!empty($validated['fifa_connect_id'])) {
                $fifaConnectId = FifaConnectId::create([
                    'fifa_id' => $validated['fifa_connect_id'],
                    'entity_type' => 'health_record',
                    'status' => 'active'
                ]);
            } else {
                // Generate FIFA Connect ID
                $fifaConnectId = $this->fifaConnectService->generateHealthRecordId();
            }

            // Create health record
            $healthRecord = HealthRecord::create([
                'player_id' => $validated['player_id'],
                'record_type' => $validated['record_type'],
                'status' => $validated['status'],
                'description' => $validated['description'],
                'diagnosis' => $validated['diagnosis'],
                'treatment' => $validated['treatment'],
                'medications' => $validated['medications'],
                'restrictions' => $validated['restrictions'],
                'next_checkup' => $validated['next_checkup'],
                'doctor_name' => $validated['doctor_name'],
                'hospital_clinic' => $validated['hospital_clinic'],
                'severity' => $validated['severity'],
                'fifa_connect_id' => $fifaConnectId->id,
            ]);

            // Update player status if needed
            if (in_array($validated['status'], ['injured', 'unfit'])) {
                $player->update(['status' => 'suspended']);
            }

            DB::commit();

            return redirect()->route('healthcare.records.index')
                ->with('success', 'Health record created successfully with FIFA Connect ID: ' . $fifaConnectId->fifa_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create health record: ' . $e->getMessage());
        }
    }

    public function show(HealthRecord $healthRecord)
    {
        $this->authorizeHealthRecordAccess($healthRecord);
        
        $healthRecord->load(['player', 'player.club', 'fifaConnectId']);
        
        return view('healthcare.show', compact('healthRecord'));
    }

    public function edit(HealthRecord $healthRecord)
    {
        $this->authorizeHealthRecordAccess($healthRecord);
        
        $user = Auth::user();
        $players = collect();

        if ($user->role === 'club') {
            $players = Player::where('club_id', $user->club_id)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
        } elseif ($user->role === 'association') {
            $players = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        }

        $healthRecord->load(['player', 'fifaConnectId']);
        
        return view('healthcare.edit', compact('healthRecord', 'players'));
    }

    public function update(Request $request, HealthRecord $healthRecord)
    {
        $this->authorizeHealthRecordAccess($healthRecord);
        
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'record_type' => 'required|in:medical_check,injury_report,recovery_update,health_assessment',
            'status' => 'required|in:healthy,injured,recovering,unfit',
            'description' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
            'restrictions' => 'nullable|string',
            'next_checkup' => 'nullable|date',
            'doctor_name' => 'nullable|string|max:255',
            'hospital_clinic' => 'nullable|string|max:255',
            'severity' => 'nullable|in:low,medium,high,critical',
        ]);

        // Verify player access
        $player = Player::findOrFail($validated['player_id']);
        $this->authorizePlayerAccess($player);

        DB::beginTransaction();
        try {
            $oldStatus = $healthRecord->status;
            $healthRecord->update($validated);

            // Update player status if needed
            if (in_array($validated['status'], ['injured', 'unfit']) && $oldStatus !== $validated['status']) {
                $player->update(['status' => 'suspended']);
            } elseif ($validated['status'] === 'healthy' && $oldStatus !== 'healthy') {
                $player->update(['status' => 'active']);
            }

            // Sync with FIFA Connect if needed
            if ($healthRecord->fifaConnectId) {
                $this->fifaConnectService->syncHealthRecord($healthRecord);
            }

            DB::commit();

            return redirect()->route('healthcare.records.show', $healthRecord)
                ->with('success', 'Health record updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update health record: ' . $e->getMessage());
        }
    }

    public function destroy(HealthRecord $healthRecord)
    {
        $this->authorizeHealthRecordAccess($healthRecord);
        
        try {
            $healthRecord->delete();
            return redirect()->route('healthcare.records.index')
                ->with('success', 'Health record deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete health record: ' . $e->getMessage());
        }
    }

    public function sync(HealthRecord $healthRecord)
    {
        $this->authorizeHealthRecordAccess($healthRecord);
        
        try {
            $result = $this->fifaConnectService->syncHealthRecord($healthRecord);
            return back()->with('success', 'Health record synced with FIFA Connect successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync health record: ' . $e->getMessage());
        }
    }

    public function bulkSync()
    {
        $user = Auth::user();
        $healthRecords = collect();

        if ($user->role === 'club') {
            $healthRecords = HealthRecord::whereHas('player', function ($query) use ($user) {
                $query->where('club_id', $user->club_id);
            })->get();
        } elseif ($user->role === 'association') {
            $healthRecords = HealthRecord::whereHas('player.club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })->get();
        }

        $synced = 0;
        $failed = 0;

        foreach ($healthRecords as $healthRecord) {
            try {
                $this->fifaConnectService->syncHealthRecord($healthRecord);
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
        $healthRecords = collect();

        if ($user->role === 'club') {
            $healthRecords = HealthRecord::whereHas('player', function ($query) use ($user) {
                $query->where('club_id', $user->club_id);
            })
            ->with(['player', 'player.club', 'fifaConnectId'])
            ->get();
        } elseif ($user->role === 'association') {
            $healthRecords = HealthRecord::whereHas('player.club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->with(['player', 'player.club', 'fifaConnectId'])
            ->get();
        }

        $filename = 'health_records_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($healthRecords) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'FIFA Connect ID', 'Player Name', 'Club', 'Record Type', 'Status', 
                'Severity', 'Doctor', 'Hospital/Clinic', 'Next Checkup', 'Created At'
            ]);

            foreach ($healthRecords as $record) {
                fputcsv($file, [
                    $record->fifaConnectId?->fifa_id ?? 'N/A',
                    $record->player ? ($record->player->first_name . ' ' . $record->player->last_name) : 'N/A',
                    $record->player?->club?->name ?? 'N/A',
                    $record->record_type,
                    $record->status,
                    $record->severity ?? 'N/A',
                    $record->doctor_name ?? 'N/A',
                    $record->hospital_clinic ?? 'N/A',
                    $record->next_checkup ?? 'N/A',
                    $record->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function predictions()
    {
        $user = Auth::user();
        $players = collect();

        if ($user->role === 'club') {
            $players = Player::where('club_id', $user->club_id)
                ->with(['healthRecords' => function ($query) {
                    $query->latest()->take(5);
                }])
                ->get();
        } elseif ($user->role === 'association') {
            $players = Player::whereHas('club', function ($query) use ($user) {
                $query->where('association_id', $user->association_id);
            })
            ->with(['healthRecords' => function ($query) {
                $query->latest()->take(5);
            }])
            ->get();
        }

        // Analyze health patterns and generate predictions
        $predictions = [];
        foreach ($players as $player) {
            $recentRecords = $player->healthRecords;
            $injuryCount = $recentRecords->where('status', 'injured')->count();
            $recoveryTime = $recentRecords->where('status', 'recovering')->count();
            
            $riskLevel = 'low';
            if ($injuryCount > 2) {
                $riskLevel = 'high';
            } elseif ($injuryCount > 1) {
                $riskLevel = 'medium';
            }
            
            $predictions[] = [
                'player' => $player,
                'risk_level' => $riskLevel,
                'injury_count' => $injuryCount,
                'recovery_time' => $recoveryTime,
                'recommendation' => $this->generateRecommendation($riskLevel, $injuryCount, $recoveryTime),
            ];
        }

        return view('healthcare.predictions', compact('predictions'));
    }

    protected function generateRecommendation($riskLevel, $injuryCount, $recoveryTime)
    {
        if ($riskLevel === 'high') {
            return 'High injury risk. Recommend reduced training intensity and regular medical monitoring.';
        } elseif ($riskLevel === 'medium') {
            return 'Moderate injury risk. Monitor training load and ensure proper recovery protocols.';
        } else {
            return 'Low injury risk. Continue current training regimen with standard monitoring.';
        }
    }

    protected function authorizeHealthRecordAccess(HealthRecord $healthRecord)
    {
        $user = Auth::user();
        $player = $healthRecord->player;
        
        if (!$player) {
            abort(404, 'Player not found');
        }
        
        if ($user->role === 'club' && $player->club_id !== $user->club_id) {
            abort(403, 'Unauthorized access to health record');
        }
        
        if ($user->role === 'association') {
            $club = $player->club;
            if (!$club || $club->association_id !== $user->association_id) {
                abort(403, 'Unauthorized access to health record');
            }
        }
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
