<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Team;
use App\Models\Lineup;
use App\Models\Player;
use App\Models\PlayerLicense;
use App\Models\Competition;
use App\Models\GameMatch;
use App\Services\FifaConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClubManagementController extends Controller
{
    protected $fifaConnectService;

    public function __construct(FifaConnectService $fifaConnectService)
    {
        $this->fifaConnectService = $fifaConnectService;
        $this->middleware('auth');
        $this->middleware('role:club_admin,club_manager,club_medical,association_admin');
    }

    // Club Dashboard - Show user's club data or all clubs for association admins
    public function dashboard()
    {
        $user = auth()->user();
        
        // Check if user is association admin
        if ($user->isAssociationAdmin()) {
            // Association admin sees overview of all clubs
            $clubs = Club::withCount(['players', 'teams', 'playerLicenses'])->get();
            
            // Get association data for the user
            $association = null;
            if ($user->entity_type === 'association' && $user->entity_id) {
                $association = \App\Models\Association::find($user->entity_id);
            }
            
            $dashboardData = [
                'is_association_admin' => true,
                'association' => $association,
                'clubs' => $clubs,
                'stats' => [
                    'total_clubs' => $clubs->count(),
                    'total_players' => $clubs->sum('players_count'),
                    'total_teams' => $clubs->sum('teams_count'),
                    'total_licenses' => $clubs->sum('player_licenses_count'),
                    'pending_licenses' => PlayerLicense::where('status', 'pending')->count(),
                    'active_licenses' => PlayerLicense::where('status', 'active')->count(),
                    'expiring_licenses' => PlayerLicense::where('status', 'active')
                        ->where('expiry_date', '<=', now()->addDays(30))->count(),
                ]
            ];

            return view('club-management.dashboard', compact('dashboardData'));
        }
        
        // Club user sees their club data
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        // Load the association relationship
        $club->load('association');

        // Simple stats without complex queries
        $dashboardData = [
            'is_association_admin' => false,
            'club' => $club, // Pass the actual Club model instead of array
            'stats' => [
                'total_players' => $club->players()->count(),
                'total_teams' => $club->teams()->count(),
                'active_licenses' => $club->playerLicenses()->where('status', 'active')->count(),
                'pending_licenses' => $club->playerLicenses()->where('status', 'pending')->count(),
                'expiring_licenses' => $club->playerLicenses()->where('status', 'active')
                    ->where('expiry_date', '<=', now()->addDays(30))->count(),
            ]
        ];

        return view('club-management.dashboard', compact('dashboardData'));
    }

    // Team Management - Show only the user's club teams
    public function teams()
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $teams = $club->teams()->with(['players.player'])->get();
        
        $teamStats = $teams->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'type' => $team->type,
                'formation' => $team->formation,
                'squad_size' => $team->getSquadSize(),
                'average_rating' => $team->getAverageRating(),
                'strengths' => $team->getTeamStrength(),
                'analysis' => $team->getTacticalAnalysis()
            ];
        });

        return view('club-management.teams.index', compact('club', 'teams', 'teamStats'));
    }

    public function createTeam()
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $formations = [
            '4-4-2' => '4-4-2 (Balanced)',
            '4-3-3' => '4-3-3 (Attacking)',
            '3-5-2' => '3-5-2 (Wing-backs)',
            '4-2-3-1' => '4-2-3-1 (Modern)',
            '3-4-3' => '3-4-3 (Attacking)',
            '5-3-2' => '5-3-2 (Defensive)'
        ];

        $teamTypes = [
            'first_team' => 'First Team',
            'reserve' => 'Reserve Team',
            'youth' => 'Youth Team',
            'academy' => 'Academy'
        ];

        return view('club-management.teams.create', compact('club', 'formations', 'teamTypes'));
    }

    public function storeTeam(Request $request)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:first_team,reserve,youth,academy',
            'formation' => 'required|string|max:10',
            'tactical_style' => 'nullable|string|max:255',
            'playing_philosophy' => 'nullable|string|max:500',
            'coach_name' => 'nullable|string|max:255',
            'budget_allocation' => 'nullable|numeric|min:0',
            'season' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $team = $club->teams()->create($request->validated());

        return redirect()->route('club-management.teams.show', $team)
            ->with('success', 'Team created successfully!');
    }

    public function showTeam(Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $team->load(['players.player', 'lineups']);
        
        $squadAnalysis = $team->getTacticalAnalysis();
        $teamStrength = $team->getTeamStrength();
        $bestLineup = $team->getBestLineup();
        $availablePlayers = $club->getAvailablePlayers();

        return view('club-management.teams.show', compact('club', 'team', 'squadAnalysis', 'teamStrength', 'bestLineup', 'availablePlayers'));
    }

    public function editTeam(Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $formations = [
            '4-4-2' => '4-4-2 (Balanced)',
            '4-3-3' => '4-3-3 (Attacking)',
            '3-5-2' => '3-5-2 (Wing-backs)',
            '4-2-3-1' => '4-2-3-1 (Modern)',
            '3-4-3' => '3-4-3 (Attacking)',
            '5-3-2' => '5-3-2 (Defensive)'
        ];

        $teamTypes = [
            'first_team' => 'First Team',
            'reserve' => 'Reserve Team',
            'youth' => 'Youth Team',
            'academy' => 'Academy'
        ];

        return view('club-management.teams.edit', compact('club', 'team', 'formations', 'teamTypes'));
    }

    public function updateTeam(Request $request, Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:first_team,reserve,youth,academy',
            'formation' => 'required|string|max:10',
            'tactical_style' => 'nullable|string|max:255',
            'playing_philosophy' => 'nullable|string|max:500',
            'coach_name' => 'nullable|string|max:255',
            'budget_allocation' => 'nullable|numeric|min:0',
            'season' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $team->update($request->validated());

        return redirect()->route('club-management.teams.show', $team)
            ->with('success', 'Team updated successfully!');
    }

    public function destroyTeam(Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $team->delete();

        return redirect()->route('club-management.teams.index')
            ->with('success', 'Team deleted successfully!');
    }

    public function manageTeamPlayers(Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $team->load(['players.player']);
        $availablePlayers = $club->getAvailablePlayers();
        
        return view('club-management.teams.manage-players', compact('club', 'team', 'availablePlayers'));
    }

    public function addPlayerToTeam(Request $request, Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $player = Player::findOrFail($request->player_id);
        
        // Ensure the player belongs to the user's club
        if ($player->club_id !== $club->id) {
            abort(403, 'Unauthorized access to player.');
        }

        // Check if player is already in the team
        if ($team->players()->where('player_id', $player->id)->exists()) {
            return back()->with('error', 'Player is already in this team.');
        }

        $team->players()->create([
            'player_id' => $player->id,
            'position_order' => $team->players()->count() + 1
        ]);

        return redirect()->route('club-management.teams.manage-players', $team)
            ->with('success', 'Player added to team successfully!');
    }

    public function removePlayerFromTeam(Team $team, Player $player)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        // Ensure the player belongs to the user's club
        if ($player->club_id !== $club->id) {
            abort(403, 'Unauthorized access to player.');
        }

        $team->players()->where('player_id', $player->id)->delete();

        return redirect()->route('club-management.teams.manage-players', $team)
            ->with('success', 'Player removed from team successfully!');
    }

    // Lineup Management
    public function lineups()
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $lineups = $club->lineups()->with(['team', 'competition', 'match'])->latest()->get();

        return view('club-management.lineups.index', compact('club', 'lineups'));
    }

    public function createLineup(?Team $team = null)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        // If team is provided, ensure it belongs to the user's club
        if ($team && $team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $teams = $club->teams()->orderBy('name')->get();
        $players = $club->players()->orderBy('name')->get();
        $competitions = Competition::where('club_id', $club->id)->orderBy('name')->get();

        return view('club-management.lineups.create', compact('club', 'teams', 'players', 'competitions', 'team'));
    }

    public function storeLineup(Request $request)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'formation' => 'required|string|max:10',
            'match_type' => 'required|string|max:50',
            'opponent' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'weather_conditions' => 'nullable|string|max:100',
            'pitch_condition' => 'nullable|string|max:100',
            'tactical_notes' => 'nullable|string',
            'substitutions_plan' => 'nullable|string',
            'set_pieces_strategy' => 'nullable|string',
            'pressing_intensity' => 'nullable|string|max:50',
            'possession_style' => 'nullable|string|max:50',
            'counter_attack_style' => 'nullable|string|max:50',
            'defensive_line_height' => 'nullable|string|max:50',
            'marking_system' => 'nullable|string|max:50',
            'captain_id' => 'nullable|exists:players,id',
            'vice_captain_id' => 'nullable|exists:players,id',
            'penalty_taker_id' => 'nullable|exists:players,id',
            'free_kick_taker_id' => 'nullable|exists:players,id',
            'corner_taker_id' => 'nullable|exists:players,id',
            'competition_id' => 'nullable|exists:competitions,id',
            'match_id' => 'nullable|exists:game_matches,id',
            'players' => 'required|array|min:11',
            'players.*.player_id' => 'required|exists:players,id',
            'players.*.position' => 'required|string|max:10',
            'players.*.is_substitute' => 'boolean',
            'players.*.position_order' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ensure the team belongs to the user's club
        $team = Team::findOrFail($request->team_id);
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        // Ensure all players belong to the user's club
        $playerIds = collect($request->players)->pluck('player_id');
        $players = Player::whereIn('id', $playerIds)->get();
        foreach ($players as $player) {
            if ($player->club_id !== $club->id) {
                abort(403, 'Unauthorized access to player.');
            }
        }

        DB::transaction(function () use ($request, $club, $team) {
            $lineup = $club->lineups()->create([
                'team_id' => $team->id,
                'name' => $request->name,
                'formation' => $request->formation,
                'match_type' => $request->match_type,
                'opponent' => $request->opponent,
                'venue' => $request->venue,
                'weather_conditions' => $request->weather_conditions,
                'pitch_condition' => $request->pitch_condition,
                'tactical_notes' => $request->tactical_notes,
                'substitutions_plan' => $request->substitutions_plan,
                'set_pieces_strategy' => $request->set_pieces_strategy,
                'pressing_intensity' => $request->pressing_intensity,
                'possession_style' => $request->possession_style,
                'counter_attack_style' => $request->counter_attack_style,
                'defensive_line_height' => $request->defensive_line_height,
                'marking_system' => $request->marking_system,
                'captain_id' => $request->captain_id,
                'vice_captain_id' => $request->vice_captain_id,
                'penalty_taker_id' => $request->penalty_taker_id,
                'free_kick_taker_id' => $request->free_kick_taker_id,
                'corner_taker_id' => $request->corner_taker_id,
                'competition_id' => $request->competition_id,
                'match_id' => $request->match_id,
                'status' => 'draft',
                'created_by' => auth()->id()
            ]);

            // Create lineup players
            foreach ($request->players as $playerData) {
                $lineup->players()->create([
                    'player_id' => $playerData['player_id'],
                    'position' => $playerData['position'],
                    'is_substitute' => $playerData['is_substitute'] ?? false,
                    'position_order' => $playerData['position_order']
                ]);
            }
        });

        return redirect()->route('club-management.lineups.index')
            ->with('success', 'Lineup created successfully!');
    }

    public function showLineup(Lineup $lineup)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the lineup belongs to the user's club
        if ($lineup->club_id !== $club->id) {
            abort(403, 'Unauthorized access to lineup.');
        }

        $lineup->load(['players.player', 'team', 'competition', 'match']);

        return view('club-management.lineups.show', compact('club', 'lineup'));
    }

    // Player Licensing
    public function licenses()
    {
        $user = auth()->user();
        
        // Check if user is association admin
        if ($user->isAssociationAdmin()) {
            // Association admin sees all licenses for validation
            $licenses = PlayerLicense::with(['player', 'club'])
                ->latest()
                ->get();

            $stats = [
                'total' => $licenses->count(),
                'active' => $licenses->where('status', 'active')->count(),
                'pending' => $licenses->where('status', 'pending')->count(),
                'expired' => $licenses->where('status', 'expired')->count(),
                'rejected' => $licenses->where('status', 'rejected')->count()
            ];

            return view('club-management.licenses.index', compact('licenses', 'stats'));
        }
        
        // Club user sees their club's licenses
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $licenses = $club->playerLicenses()
            ->with(['player'])
            ->latest()
            ->get();

        $stats = [
            'total' => $licenses->count(),
            'active' => $licenses->where('status', 'active')->count(),
            'pending' => $licenses->where('status', 'pending')->count(),
            'expired' => $licenses->where('status', 'expired')->count(),
            'rejected' => $licenses->where('status', 'rejected')->count()
        ];

        return view('club-management.licenses.index', compact('club', 'licenses', 'stats'));
    }

    public function createLicense(?Player $player = null)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        // If player is provided, ensure they belong to the user's club
        if ($player && $player->club_id !== $club->id) {
            abort(403, 'Unauthorized access to player.');
        }

        // Get players who don't have active licenses
        $availablePlayers = $club->players()
            ->whereDoesntHave('licenses', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('name')
            ->get();

        $licenseTypes = [
            'professional' => 'Professional License',
            'amateur' => 'Amateur License',
            'youth' => 'Youth License',
            'temporary' => 'Temporary License'
        ];

        $categories = [
            'senior' => 'Senior',
            'u23' => 'Under 23',
            'u21' => 'Under 21',
            'u19' => 'Under 19',
            'u17' => 'Under 17'
        ];

        return view('club-management.licenses.create', compact('club', 'availablePlayers', 'licenseTypes', 'categories', 'player'));
    }

    public function storeLicense(Request $request)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,id',
            'license_type' => 'required|in:professional,amateur,youth,temporary',
            'category' => 'required|in:senior,u23,u21,u19,u17',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'salary_eur' => 'nullable|numeric|min:0',
            'bonus_eur' => 'nullable|numeric|min:0',
            'release_clause_eur' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'fifa_connect_id' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ensure the player belongs to the user's club
        $player = Player::findOrFail($request->player_id);
        if ($player->club_id !== $club->id) {
            abort(403, 'Unauthorized access to player.');
        }

        // Check if player already has an active license
        if ($player->licenses()->where('status', 'active')->exists()) {
            return back()->with('error', 'Player already has an active license.')
                ->withInput();
        }

        $license = $club->playerLicenses()->create([
            'player_id' => $player->id,
            'license_type' => $request->license_type,
            'category' => $request->category,
            'contract_start_date' => $request->contract_start_date,
            'contract_end_date' => $request->contract_end_date,
            'salary_eur' => $request->salary_eur,
            'bonus_eur' => $request->bonus_eur,
            'release_clause_eur' => $request->release_clause_eur,
            'notes' => $request->notes,
            'fifa_connect_id' => $request->fifa_connect_id,
            'status' => 'pending',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('club-management.licenses.show', $license)
            ->with('success', 'License created successfully!');
    }

    public function showLicense(PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can view any license
        if ($user->isAssociationAdmin()) {
            $license->load(['player', 'club']);
            return view('club-management.licenses.show', compact('license'));
        }
        
        // Club users can only view their club's licenses
        $club = $this->getUserClub($user);
        
        if ($license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $license->load(['player']);

        return view('club-management.licenses.show', compact('club', 'license'));
    }

    public function printLicense(PlayerLicense $license)
    {
        $user = auth()->user();
        if ($user->isAssociationAdmin()) {
            $license->load(['player', 'club.association']);
        } else {
            $club = $this->getUserClub($user);
            if ($license->club_id !== $club->id) {
                abort(403, 'Unauthorized access to license.');
            }
            $license->load(['player', 'club.association']);
        }
        
        // Get player photo from database
        $playerPhoto = $this->getBase64Image($license->player->player_face_url ?? null, $this->getPlaceholderPhoto());
        
        // Get club logo from database
        $clubLogo = $this->getBase64Image($license->club->logo_url ?? null, $this->getPlaceholderLogo());
        
        // Get association logo from database
        $associationLogo = $this->getBase64Image($license->club->association->association_logo_url ?? null, $this->getPlaceholderAssociationLogo());
        
        // QR code for license number
        $qrCode = $this->getQrCodeBase64($license->license_number ?? '');
        
        $pdf = Pdf::loadView('club-management.licenses.pdf', compact('license', 'playerPhoto', 'clubLogo', 'associationLogo', 'qrCode'));
        $pdf->setPaper([85.6, 54], 'mm');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Arial',
            'dpi' => 300,
            'defaultMediaType' => 'print',
            'isFontSubsettingEnabled' => false,
            'debugKeepTemp' => false,
            'debugCss' => false,
        ]);
        $filename = 'license_' . ($license->license_number ?? 'unknown') . '_' . ($license->player->name ?? 'unknown') . '.pdf';
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        return $pdf->stream($filename);
    }

    public function testPrintLicense(PlayerLicense $license)
    {
        $user = auth()->user();
        if ($user->isAssociationAdmin()) {
            $license->load(['player', 'club']);
        } else {
            $club = $this->getUserClub($user);
            if ($license->club_id !== $club->id) {
                abort(403, 'Unauthorized access to license.');
            }
            $license->load(['player', 'club']);
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('club-management.licenses.test-simple', compact('license'));
        $pdf->setPaper([85.6, 54], 'mm');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Arial',
            'dpi' => 300,
            'defaultMediaType' => 'print',
        ]);
        $filename = 'test_simple_license_' . ($license->license_number ?? 'unknown') . '_' . ($license->player->name ?? 'unknown') . '.pdf';
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        return $pdf->stream($filename);
    }

    public function approveLicense(Request $request, PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can approve any license
        if ($user->isAssociationAdmin()) {
            $license->update([
                'status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            return redirect()->route('club-management.licenses.show', $license)
                ->with('success', 'License approved successfully!');
        }
        
        // Club users can only approve their club's licenses
        $club = $this->getUserClub($user);
        
        if ($license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $license->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('club-management.licenses.show', $license)
            ->with('success', 'License approved successfully!');
    }

    public function rejectLicense(Request $request, PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can reject any license
        if ($user->isAssociationAdmin()) {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $license->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_by' => auth()->id(),
                'rejected_at' => now()
            ]);

            return redirect()->route('club-management.licenses.show', $license)
                ->with('success', 'License rejected successfully!');
        }
        
        // Club users can only reject their club's licenses
        $club = $this->getUserClub($user);
        
        if ($license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $license->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => auth()->id(),
            'rejected_at' => now()
        ]);

        return redirect()->route('club-management.licenses.show', $license)
            ->with('success', 'License rejected successfully!');
    }

    public function editLicense(PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can edit any license
        if ($user->isAssociationAdmin()) {
            $license->load(['player', 'club']);
            
            $licenseTypes = [
                'professional' => 'Professional License',
                'amateur' => 'Amateur License',
                'youth' => 'Youth License',
                'temporary' => 'Temporary License'
            ];

            $categories = [
                'senior' => 'Senior',
                'u23' => 'Under 23',
                'u21' => 'Under 21',
                'u19' => 'Under 19',
                'u17' => 'Under 17'
            ];

            return view('club-management.licenses.edit', compact('license', 'licenseTypes', 'categories'));
        }
        
        // Club users can only edit their club's licenses
        $club = $this->getUserClub($user);
        
        if (!$club || $license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $license->load(['player']);

        $licenseTypes = [
            'professional' => 'Professional License',
            'amateur' => 'Amateur License',
            'youth' => 'Youth License',
            'temporary' => 'Temporary License'
        ];

        $categories = [
            'senior' => 'Senior',
            'u23' => 'Under 23',
            'u21' => 'Under 21',
            'u19' => 'Under 19',
            'u17' => 'Under 17'
        ];

        return view('club-management.licenses.edit', compact('club', 'license', 'licenseTypes', 'categories'));
    }

    public function updateLicense(Request $request, PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can update any license
        if ($user->isAssociationAdmin()) {
            $validator = Validator::make($request->all(), [
                'license_type' => 'required|in:professional,amateur,youth,temporary',
                'category' => 'required|in:senior,u23,u21,u19,u17',
                'contract_start_date' => 'required|date',
                'contract_end_date' => 'required|date|after:contract_start_date',
                'salary_eur' => 'nullable|numeric|min:0',
                'bonus_eur' => 'nullable|numeric|min:0',
                'release_clause_eur' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'fifa_connect_id' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $license->update($request->validated());

            return redirect()->route('club-management.licenses.show', $license)
                ->with('success', 'License updated successfully!');
        }
        
        // Club users can only update their club's licenses
        $club = $this->getUserClub($user);
        
        if (!$club || $license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $validator = Validator::make($request->all(), [
            'license_type' => 'required|in:professional,amateur,youth,temporary',
            'category' => 'required|in:senior,u23,u21,u19,u17',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'salary_eur' => 'nullable|numeric|min:0',
            'bonus_eur' => 'nullable|numeric|min:0',
            'release_clause_eur' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'fifa_connect_id' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $license->update($request->validated());

        return redirect()->route('club-management.licenses.show', $license)
            ->with('success', 'License updated successfully!');
    }

    public function destroyLicense(PlayerLicense $license)
    {
        $user = auth()->user();
        
        // Association admins can delete any license
        if ($user->isAssociationAdmin()) {
            $license->delete();

            return redirect()->route('club-management.licenses.index')
                ->with('success', 'License deleted successfully!');
        }
        
        // Club users can only delete their club's licenses
        $club = $this->getUserClub($user);
        
        if (!$club || $license->club_id !== $club->id) {
            abort(403, 'Unauthorized access to license.');
        }

        $license->delete();

        return redirect()->route('club-management.licenses.index')
            ->with('success', 'License deleted successfully!');
    }

    // FIFA Connect Integration
    public function syncFifaData()
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        if (!$club) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not associated with any club.');
        }

        try {
            // Sync club data with FIFA Connect
            $fifaData = $this->fifaConnectService->syncClubData($club);
            
            // Update club with FIFA data
            $club->update([
                'fifa_connect_id' => $fifaData['fifa_connect_id'] ?? $club->fifa_connect_id,
                'last_updated' => now()
            ]);

            // Sync players
            $this->fifaConnectService->syncClubPlayers($club);

            return redirect()->route('club-management.dashboard')
                ->with('success', 'FIFA Connect data synchronized successfully!');

        } catch (\Exception $e) {
            Log::error('FIFA Connect sync failed: ' . $e->getMessage());
            return redirect()->route('club-management.dashboard')
                ->with('error', 'Failed to sync with FIFA Connect. Please try again.');
        }
    }

    // Team Builder
    public function teamBuilder(Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $team->load(['players.player']);
        $availablePlayers = $club->getAvailablePlayers();

        return view('club-management.teams.builder', compact('club', 'team', 'availablePlayers'));
    }

    public function generateOptimalLineup(Request $request, Team $team)
    {
        $user = auth()->user();
        $club = $this->getUserClub($user);
        
        // Ensure the team belongs to the user's club
        if ($team->club_id !== $club->id) {
            abort(403, 'Unauthorized access to team.');
        }

        $validator = Validator::make($request->all(), [
            'formation' => 'required|string|max:10',
            'tactical_style' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $players = $team->players()->with('player')->get();
        $optimalLineup = $this->calculateOptimalLineup($players, $request->formation, $request->tactical_style);

        return view('club-management.teams.optimal-lineup', compact('club', 'team', 'optimalLineup', 'request'));
    }

    // Helper methods
    private function getUserClub($user)
    {
        // Use new direct club relationship first
        if ($user->club_id) {
            return $user->club;
        }
        
        // Fallback to legacy relationship
        if ($user->entity_type === 'club' && $user->entity_id) {
            return Club::find($user->entity_id);
        }
        
        return null;
    }

    private function calculateOptimalLineup($players, $formation, $tacticalStyle)
    {
        // Implementation for calculating optimal lineup based on formation and tactical style
        // This is a simplified version - you can enhance it based on your requirements
        
        $positions = $this->parseFormation($formation);
        $optimalLineup = [];
        
        foreach ($positions as $position => $count) {
            $positionPlayers = $players->where('player.position', $position)
                ->sortByDesc('player.overall_rating')
                ->take($count);
                
            $optimalLineup[$position] = $positionPlayers;
        }
        
        return $optimalLineup;
    }

    private function parseFormation($formation)
    {
        $parts = explode('-', $formation);
        $positions = [];
        
        if (count($parts) === 3) {
            $positions = [
                'GK' => 1,
                'CB' => $parts[0],
                'CM' => $parts[1],
                'ST' => $parts[2]
            ];
        }
        
        return $positions;
    }

    private function getBase64Image($url, $fallback = null)
    {
        if ($url) {
            try {
                // Add timeout and user agent to avoid blocking
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'user_agent' => 'Mozilla/5.0 (compatible; Laravel PDF Generator)'
                    ]
                ]);
                
                $data = @file_get_contents($url, false, $context);
                if ($data) {
                    // Try to determine image type from content
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($data);
                    
                    // Map MIME types to extensions
                    $mimeMap = [
                        'image/jpeg' => 'jpeg',
                        'image/jpg' => 'jpeg',
                        'image/png' => 'png',
                        'image/gif' => 'gif',
                        'image/svg+xml' => 'svg+xml',
                        'image/webp' => 'webp'
                    ];
                    
                    $type = $mimeMap[$mimeType] ?? 'jpeg';
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    return $base64;
                }
            } catch (\Exception $e) {
                // Log error for debugging
                \Log::warning('Failed to load image from URL: ' . $url . ' - Error: ' . $e->getMessage());
            }
        }
        return $fallback;
    }

    private function getPlaceholderPhoto()
    {
        // Professional player photo placeholder
        $svg = '<svg width="80" height="100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="photoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#1e3a8a;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="#f8fafc" stroke="#e2e8f0" stroke-width="1"/>
            <circle cx="40" cy="35" r="18" fill="url(#photoGrad)"/>
            <rect x="22" y="58" width="36" height="32" fill="url(#photoGrad)"/>
            <text x="40" y="95" text-anchor="middle" font-family="Arial, sans-serif" font-size="6" fill="#64748b" font-weight="bold">PLAYER</text>
            <text x="40" y="102" text-anchor="middle" font-family="Arial, sans-serif" font-size="6" fill="#64748b" font-weight="bold">PHOTO</text>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function getPlaceholderLogo()
    {
        // Professional club logo placeholder
        $svg = '<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="clubGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#1e3a8a;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#1e40af;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="#f8fafc" stroke="#e2e8f0" stroke-width="1"/>
            <circle cx="30" cy="30" r="18" fill="url(#clubGrad)"/>
            <text x="30" y="35" text-anchor="middle" font-family="Arial, sans-serif" font-size="7" fill="white" font-weight="bold">CLUB</text>
            <text x="30" y="45" text-anchor="middle" font-family="Arial, sans-serif" font-size="5" fill="white" font-weight="bold">LOGO</text>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function getPlaceholderAssociationLogo()
    {
        // Professional association logo placeholder
        $svg = '<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="assocGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#dc2626;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#b91c1c;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="#f8fafc" stroke="#e2e8f0" stroke-width="1"/>
            <circle cx="30" cy="30" r="18" fill="url(#assocGrad)"/>
            <text x="30" y="35" text-anchor="middle" font-family="Arial, sans-serif" font-size="7" fill="white" font-weight="bold">FA</text>
            <text x="30" y="45" text-anchor="middle" font-family="Arial, sans-serif" font-size="5" fill="white" font-weight="bold">LOGO</text>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function getQrCodeBase64($text)
    {
        try {
            // Try SVG first (no Imagick required)
            $qr = \QrCode::format('svg')->size(120)->margin(0)->generate($text);
            return 'data:image/svg+xml;base64,' . base64_encode($qr);
        } catch (\Exception $e) {
            // Fallback to a simple text representation
            return null;
        }
    }
} 