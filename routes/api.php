<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueChampionshipController;
use App\Http\Controllers\RefereeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CalendarManagementController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PlayerController;
use App\Http\Controllers\Api\V1\ClubController;
use App\Http\Controllers\Api\V1\SeasonController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PlayerLicenseController;
use App\Http\Controllers\Api\V1\MatchEventController;
use App\Http\Controllers\Api\V1\MatchSheetController;
use App\Http\Controllers\Api\V1\AnalyticsController;
use App\Http\Controllers\Api\V1\FifaWebhookController;
use App\Http\Controllers\Api\V1\PerformanceApiController;
use App\Http\Controllers\FitDashboardController;
use App\Models\Competition;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\MatchController;
use App\Http\Controllers\Api\V1\CompetitionController;
use App\Http\Controllers\Api\V1\PlayerDashboardController;
use App\Http\Controllers\Api\V1\DTNController;
use App\Http\Controllers\Api\V1\RPMController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransferDocumentController;
use App\Http\Controllers\TransferPaymentController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\Api\AutoKeyController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Landing page route without web middleware
Route::get('/landing', [LandingPageController::class, 'index'])->name('api.landing');

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Public routes (no authentication required)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/check', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->check() ? [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
            ] : null,
        ]);
    });
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        // Authentication routes
        Route::prefix('auth')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });
        
        // Analytics routes
        Route::prefix('analytics')->group(function () {
            Route::get('/dashboard', [AnalyticsController::class, 'dashboard']);
            Route::get('/matches', [AnalyticsController::class, 'matchAnalytics']);
            Route::get('/players', [AnalyticsController::class, 'playerAnalytics']);
            Route::get('/licenses', [AnalyticsController::class, 'licenseAnalytics']);
            Route::get('/clubs', [AnalyticsController::class, 'clubAnalytics']);
            Route::get('/export', [AnalyticsController::class, 'export']);
        });
        
        // Player routes
        Route::prefix('players')->group(function () {
            Route::get('/', [PlayerController::class, 'index']);
            Route::post('/', [PlayerController::class, 'store']);
            Route::get('/{player}', [PlayerController::class, 'show']);
            Route::put('/{player}', [PlayerController::class, 'update']);
            Route::delete('/{player}', [PlayerController::class, 'destroy']);
            Route::get('/{player}/profile', [PlayerController::class, 'profile']);
            Route::get('/{player}/statistics', [PlayerController::class, 'statistics']);
        });
        
        // Club routes
        Route::prefix('clubs')->group(function () {
            Route::get('/', [ClubController::class, 'index']);
            Route::post('/', [ClubController::class, 'store']);
            Route::get('/{club}', [ClubController::class, 'show']);
            Route::put('/{club}', [ClubController::class, 'update']);
            Route::delete('/{club}', [ClubController::class, 'destroy']);
            Route::get('/{club}/players', [ClubController::class, 'players']);
            Route::get('/{club}/teams', [ClubController::class, 'teams']);
            Route::get('/{club}/statistics', [ClubController::class, 'statistics']);
        });
        
        // Team routes
        Route::prefix('teams')->group(function () {
            Route::get('/', [TeamController::class, 'index']);
            Route::post('/', [TeamController::class, 'store']);
            Route::get('/{team}', [TeamController::class, 'show']);
            Route::put('/{team}', [TeamController::class, 'update']);
            Route::delete('/{team}', [TeamController::class, 'destroy']);
            Route::post('/{team}/players', [TeamController::class, 'addPlayer']);
            Route::delete('/{team}/players', [TeamController::class, 'removePlayer']);
            Route::get('/{team}/roster', [TeamController::class, 'roster']);
            Route::get('/{team}/statistics', [TeamController::class, 'statistics']);
            Route::get('/{team}/standings', [TeamController::class, 'standings']);
        });
        
        // Match routes
        Route::prefix('matches')->group(function () {
            Route::get('/', [MatchController::class, 'index']);
            Route::post('/', [MatchController::class, 'store']);
            Route::get('/{match}', [MatchController::class, 'show']);
            Route::put('/{match}', [MatchController::class, 'update']);
            Route::delete('/{match}', [MatchController::class, 'destroy']);
            Route::get('/{match}/lineups', [MatchController::class, 'lineups']);
            Route::get('/{match}/statistics', [MatchController::class, 'statistics']);
            Route::patch('/{match}/status', [MatchController::class, 'updateStatus']);
        });
        
        // Season routes
        Route::prefix('seasons')->group(function () {
            Route::get('/', [SeasonController::class, 'index']);
            Route::post('/', [SeasonController::class, 'store']);
            Route::get('/current', [SeasonController::class, 'current']);
            Route::get('/active', [SeasonController::class, 'active']);
            Route::get('/upcoming', [SeasonController::class, 'upcoming']);
            Route::get('/completed', [SeasonController::class, 'completed']);
            Route::get('/{season}', [SeasonController::class, 'show']);
            Route::put('/{season}', [SeasonController::class, 'update']);
            Route::delete('/{season}', [SeasonController::class, 'destroy']);
            Route::post('/{season}/set-current', [SeasonController::class, 'setCurrent']);
            Route::patch('/{season}/status', [SeasonController::class, 'updateStatus']);
            Route::get('/{season}/statistics', [SeasonController::class, 'statistics']);
        });
        
        // User routes
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/by-role', [UserController::class, 'byRole']);
            Route::get('/by-status', [UserController::class, 'byStatus']);
            Route::get('/by-club', [UserController::class, 'byClub']);
            Route::get('/by-association', [UserController::class, 'byAssociation']);
            Route::get('/online', [UserController::class, 'online']);
            Route::get('/recently-active', [UserController::class, 'recentlyActive']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
            Route::patch('/{user}/role', [UserController::class, 'updateRole']);
            Route::patch('/{user}/permissions', [UserController::class, 'updatePermissions']);
            Route::patch('/{user}/status', [UserController::class, 'updateStatus']);
            Route::get('/{user}/statistics', [UserController::class, 'statistics']);
        });
        
        // Player License routes
        Route::prefix('player-licenses')->group(function () {
            Route::get('/', [PlayerLicenseController::class, 'index']);
            Route::post('/', [PlayerLicenseController::class, 'store']);
            Route::get('/{player_license}', [PlayerLicenseController::class, 'show']);
            Route::put('/{player_license}', [PlayerLicenseController::class, 'update']);
            Route::delete('/{player_license}', [PlayerLicenseController::class, 'destroy']);
            Route::post('/{player_license}/approve', [PlayerLicenseController::class, 'approve']);
            Route::post('/{player_license}/reject', [PlayerLicenseController::class, 'reject']);
            Route::post('/{player_license}/renew', [PlayerLicenseController::class, 'renew']);
            Route::post('/{player_license}/suspend', [PlayerLicenseController::class, 'suspend']);
            Route::post('/{player_license}/transfer', [PlayerLicenseController::class, 'transfer']);
            Route::patch('/{player_license}/status', [PlayerLicenseController::class, 'status']);
        });
        
        // Match Event routes
        Route::prefix('match-events')->group(function () {
            Route::get('/', [MatchEventController::class, 'index']);
            Route::post('/', [MatchEventController::class, 'store']);
            Route::get('/statistics', [MatchEventController::class, 'statistics']);
            Route::get('/{match_event}', [MatchEventController::class, 'show']);
            Route::put('/{match_event}', [MatchEventController::class, 'update']);
            Route::delete('/{match_event}', [MatchEventController::class, 'destroy']);
            Route::post('/{match_event}/confirm', [MatchEventController::class, 'confirm']);
            Route::post('/{match_event}/contest', [MatchEventController::class, 'contest']);
        });
        
        // Match-specific event routes
        Route::prefix('matches/{match}/events')->group(function () {
            Route::get('/', [MatchController::class, 'events']);
            Route::post('/', [MatchController::class, 'addEvent']);
            Route::get('/timeline', [MatchEventController::class, 'timeline']);
        });
        
        // Team-specific event routes
        Route::prefix('teams/{team}/events')->group(function () {
            Route::get('/', [MatchEventController::class, 'byTeam']);
        });
        
        // Player-specific event routes
        Route::prefix('players/{player}/events')->group(function () {
            Route::get('/', [MatchEventController::class, 'byPlayer']);
        });
        
        // Match Sheet routes
        Route::prefix('match-sheets')->group(function () {
            Route::get('/', [MatchSheetController::class, 'index']);
            Route::post('/', [MatchSheetController::class, 'store']);
            Route::get('/{match_sheet}', [MatchSheetController::class, 'show']);
            Route::put('/{match_sheet}', [MatchSheetController::class, 'update']);
            Route::delete('/{match_sheet}', [MatchSheetController::class, 'destroy']);
            Route::post('/{match_sheet}/submit', [MatchSheetController::class, 'submit']);
            Route::post('/{match_sheet}/validate', [MatchSheetController::class, 'validate']);
            Route::post('/{match_sheet}/reject', [MatchSheetController::class, 'reject']);
            Route::post('/{match_sheet}/assign-referee', [MatchSheetController::class, 'assignReferee']);
            Route::post('/{match_sheet}/sign-team', [MatchSheetController::class, 'signByTeam']);
            Route::post('/{match_sheet}/sign-referee', [MatchSheetController::class, 'signByReferee']);
            Route::post('/{match_sheet}/sign-observer', [MatchSheetController::class, 'signByObserver']);
            Route::post('/{match_sheet}/sign-lineup', [MatchSheetController::class, 'signLineup']);
            Route::post('/{match_sheet}/sign-post-match', [MatchSheetController::class, 'signPostMatch']);
            Route::post('/{match_sheet}/fa-validate', [MatchSheetController::class, 'faValidate']);
            Route::post('/{match_sheet}/lock-lineups', [MatchSheetController::class, 'lockLineups']);
            Route::post('/{match_sheet}/unlock-lineups', [MatchSheetController::class, 'unlockLineups']);
            Route::post('/{match_sheet}/lock-match-events', [MatchSheetController::class, 'lockMatchEvents']);
            Route::post('/{match_sheet}/unlock-match-events', [MatchSheetController::class, 'unlockMatchEvents']);
            Route::post('/{match_sheet}/transition-stage', [MatchSheetController::class, 'transitionStage']);
            Route::get('/{match_sheet}/stage-progress', [MatchSheetController::class, 'getStageProgress']);
            Route::get('/{match_sheet}/statistics', [MatchSheetController::class, 'getStatistics']);
            Route::get('/{match_sheet}/export', [MatchSheetController::class, 'export']);
        });
        
        // Performance API routes
        Route::prefix('performances')->group(function () {
            Route::get('/', [PerformanceApiController::class, 'index']);
            Route::post('/', [PerformanceApiController::class, 'store']);
            Route::get('/{performance}', [PerformanceApiController::class, 'show']);
            Route::put('/{performance}', [PerformanceApiController::class, 'update']);
            Route::delete('/{performance}', [PerformanceApiController::class, 'destroy']);
            Route::get('/analytics', [PerformanceApiController::class, 'analytics']);
            Route::get('/export', [PerformanceApiController::class, 'export']);
            Route::post('/bulk-import', [PerformanceApiController::class, 'bulkImport']);
            Route::get('/dashboard', [PerformanceApiController::class, 'dashboard']);
            Route::get('/compare', [PerformanceApiController::class, 'compare']);
            Route::get('/trends', [PerformanceApiController::class, 'trends']);
            Route::post('/generate-alerts', [PerformanceApiController::class, 'generateAlerts']);
        });
        
        // Debug route to test authentication
        Route::get('/debug/auth', function (Request $request) {
            return response()->json([
                'user' => $request->user(),
                'authenticated' => auth()->check(),
                'abilities' => $request->user() ? $request->user()->getAbilities() : []
            ]);
        });
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// League Championship Routes (temporarily public for testing)
Route::prefix('league-championship')->group(function () {
    Route::get('/competitions/{competition}', [LeagueChampionshipController::class, 'show']);
    Route::get('/competitions/{competition}/schedule', [LeagueChampionshipController::class, 'getSchedule']);
    Route::post('/competitions/{competition}/generate-schedule', [LeagueChampionshipController::class, 'generateSchedule']);
    Route::get('/competitions/{competition}/standings', [LeagueChampionshipController::class, 'getStandings']);
    Route::get('/competitions/{competition}/player-stats', [LeagueChampionshipController::class, 'getPlayerStats']);
    
    // Individual match route
    Route::get('/matches/{gameMatch}', [LeagueChampionshipController::class, 'getMatch']);
    
    Route::prefix('matches/{gameMatch}')->group(function () {
        Route::post('/roster', [LeagueChampionshipController::class, 'submitRoster']);
        Route::get('/rosters', [LeagueChampionshipController::class, 'getRosters']);
        Route::post('/officials', [LeagueChampionshipController::class, 'assignOfficials']);
        Route::patch('/status', [LeagueChampionshipController::class, 'updateMatchStatus']);
        Route::patch('/statistics', [LeagueChampionshipController::class, 'updateStatistics']);
        
        // Event routes
        Route::get('/events', [LeagueChampionshipController::class, 'getEvents']);
        Route::post('/events', [LeagueChampionshipController::class, 'addEvent']);
        Route::delete('/events/{eventId}', [LeagueChampionshipController::class, 'deleteEvent']);
    });
});

// Simple test endpoints (temporary)
Route::get('/competitions', function () {
    $competitions = Competition::with('teams')->get();
    return response()->json(['success' => true, 'data' => $competitions]);
});

Route::get('/competitions/{competition}', function (Competition $competition) {
    $competition->load('teams');
    return response()->json(['success' => true, 'data' => $competition]);
});

Route::get('/matches/recent', function () {
    $matches = \App\Models\GameMatch::with(['homeTeam', 'awayTeam'])
        ->orderBy('kickoff_time', 'desc')
        ->limit(10)
        ->get();
    return response()->json(['success' => true, 'data' => $matches]);
});

Route::get('/matches/{gameMatch}', function (\App\Models\GameMatch $gameMatch) {
    $gameMatch->load(['homeTeam', 'awayTeam', 'competition']);
    return response()->json(['success' => true, 'data' => $gameMatch]);
});

Route::get('/matches/{gameMatch}/events', function (\App\Models\GameMatch $gameMatch) {
    $events = $gameMatch->events()->with('player')->get();
    return response()->json(['success' => true, 'data' => $events]);
});

Route::post('/matches/{gameMatch}/events', function (Request $request, \App\Models\GameMatch $gameMatch) {
    $event = $gameMatch->events()->create($request->all());
    return response()->json(['success' => true, 'data' => $event]);
});

Route::delete('/matches/{gameMatch}/events/{event}', function (\App\Models\GameMatch $gameMatch, \App\Models\MatchEvent $event) {
    $event->delete();
    return response()->json(['success' => true]);
});

// Add rosters endpoint for MatchSheet component
Route::get('/matches/{gameMatch}/rosters', function (\App\Models\GameMatch $gameMatch) {
    $rosters = [];
    foreach ([$gameMatch->home_team_id, $gameMatch->away_team_id] as $teamId) {
        $players = \App\Models\MatchRoster::where('match_id', $gameMatch->id)
            ->where('team_id', $teamId)
            ->with('player')
            ->orderBy('is_starter', 'desc')
            ->orderBy('jersey_number')
            ->get();
        $rosters[] = [
            'team_id' => $teamId,
            'players' => $players
        ];
    }
    return response()->json(['success' => true, 'data' => $rosters]);
});

// Add match status update endpoint
Route::put('/matches/{gameMatch}/status', function (Request $request, \App\Models\GameMatch $gameMatch) {
    $validated = $request->validate([
        'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        'home_score' => 'nullable|integer|min:0',
        'away_score' => 'nullable|integer|min:0'
    ]);
    
    $gameMatch->update($validated);
    return response()->json(['success' => true, 'data' => $gameMatch]);
});

// Referee Routes
Route::prefix('referee')->middleware(['auth:sanctum', 'referee'])->group(function () {
    Route::get('/dashboard', [RefereeController::class, 'dashboard']);
    Route::get('/matches/{gameMatch}/events', [RefereeController::class, 'getMatchEvents']);
    Route::post('/matches/{gameMatch}/events', [RefereeController::class, 'recordEvent']);
    Route::patch('/matches/{gameMatch}/status', [RefereeController::class, 'updateMatchStatus']);
    Route::post('/events/{event}/contest', [RefereeController::class, 'contestEvent']);
    Route::post('/events/{event}/confirm', [RefereeController::class, 'confirmEvent']);
});

// Report Routes
Route::prefix('reports')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/competitions/{competition}/standings', [ReportController::class, 'standingsReport']);
    Route::get('/competitions/{competition}/standings/export', [ReportController::class, 'exportStandings']);
    Route::get('/competitions/{competition}/player-stats', [ReportController::class, 'playerStatsReport']);
    Route::get('/competitions/{competition}/summary', [ReportController::class, 'competitionSummary']);
    Route::get('/matches/{gameMatch}', [ReportController::class, 'matchReport']);
});

// Calendar Management Routes
Route::prefix('calendar')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('competitions/{competition}')->group(function () {
        Route::post('/generate-full-schedule', [CalendarManagementController::class, 'generateFullSchedule']);
        Route::get('/schedule', [CalendarManagementController::class, 'getSchedule']);
        Route::post('/matches/validate-and-create', [CalendarManagementController::class, 'validateAndCreateMatch']);
        Route::get('/teams', [CalendarManagementController::class, 'getAvailableTeams']);
        Route::delete('/schedule', [CalendarManagementController::class, 'clearSchedule']);
    });
    
    Route::prefix('matches/{gameMatch}')->group(function () {
        Route::put('/', [CalendarManagementController::class, 'updateMatch']);
        Route::delete('/', [CalendarManagementController::class, 'deleteMatch']);
    });
    
    Route::get('/venues', [CalendarManagementController::class, 'getAvailableVenues']);
});

// Club Management API Routes
Route::middleware(['auth:sanctum', 'role:club_admin,club_manager,club_medical'])->prefix('club')->group(function () {
    Route::get('/eligible-players/{competition}', [App\Http\Controllers\ClubManagementController::class, 'getEligiblePlayers']);
    Route::post('/teams/{team}/players', [App\Http\Controllers\ClubManagementController::class, 'addPlayerToTeam']);
});

// Competition Management API Routes
Route::middleware(['auth:sanctum', 'role:association_admin,association_registrar'])->prefix('competitions')->group(function () {
    Route::post('/{competition}/register-team', [App\Http\Controllers\CompetitionManagementController::class, 'registerTeam']);
});

// Competition API Routes
Route::middleware(['auth:sanctum', 'verified'])->prefix('v1')->group(function () {
    Route::apiResource('competitions', CompetitionController::class);
    Route::get('competitions/{competition}/standings', [CompetitionController::class, 'standings']);
    Route::get('competitions/{competition}/seasons', [CompetitionController::class, 'seasons']);
    Route::get('competitions/{competition}/teams', [CompetitionController::class, 'teams']);
    Route::get('competitions/{competition}/statistics', [CompetitionController::class, 'statistics']);
    Route::post('competitions/{competition}/teams', [CompetitionController::class, 'addTeam']);
    Route::delete('competitions/{competition}/teams', [CompetitionController::class, 'removeTeam']);
});

// Competition API routes for Vue.js components
Route::middleware(['auth:sanctum'])->prefix('competitions')->name('competitions.')->group(function () {
    Route::get('/{competition}/matches', [CompetitionController::class, 'matches'])->name('matches');
    Route::get('/{competition}/standings', [CompetitionController::class, 'standings'])->name('standings');
});

// Match API routes for Vue.js components
Route::middleware(['auth:sanctum'])->prefix('matches')->name('matches.')->group(function () {
    Route::get('/{match}', [MatchController::class, 'show'])->name('show');
    Route::get('/{match}/sheet', [MatchController::class, 'sheet'])->name('sheet');
});

// Player Dashboard API Routes
Route::middleware(['auth:sanctum', 'player.access'])->prefix('v1/player-dashboard')->name('api.v1.player-dashboard.')->group(function () {
    // Profile and FIFA ID
    Route::get('/profile', [PlayerDashboardController::class, 'profile'])->name('profile');
    
    // Performance Dashboard
    Route::get('/performance', [PlayerDashboardController::class, 'performance'])->name('performance');
    
    // General Health Score (GHS)
    Route::get('/ghs', [PlayerDashboardController::class, 'ghs'])->name('ghs');
    Route::post('/ghs', [PlayerDashboardController::class, 'updateGHS'])->name('ghs.update');
    
    // Health & Fitness
    Route::get('/health', [PlayerDashboardController::class, 'health'])->name('health');
    
    // AI-Powered Injury Risk
    Route::get('/injury-risk', [PlayerDashboardController::class, 'injuryRisk'])->name('injury-risk');
    
    // Match Sheet Access
    Route::get('/match-sheet', [PlayerDashboardController::class, 'matchSheet'])->name('match-sheet');
    Route::post('/match-sheet/availability', [PlayerDashboardController::class, 'updateAvailability'])->name('availability.update');
    
    // Licensing
    Route::get('/licensing', [PlayerDashboardController::class, 'licensing'])->name('licensing');
    Route::get('/licensing/{license}/download', [PlayerDashboardController::class, 'downloadLicense'])->name('license.download');
    
    // Data Ownership
    Route::get('/data-ownership', [PlayerDashboardController::class, 'dataOwnership'])->name('data-ownership');
    Route::get('/export', [PlayerDashboardController::class, 'exportData'])->name('export');
    Route::get('/export/download', [PlayerDashboardController::class, 'downloadExport'])->name('export.download');
    
    // Documents & Media
    Route::get('/documents', [PlayerDashboardController::class, 'documents'])->name('documents');
    Route::post('/documents', [PlayerDashboardController::class, 'uploadDocument'])->name('documents.upload');
    Route::get('/documents/{document}/download', [PlayerDashboardController::class, 'downloadDocument'])->name('documents.download');
    Route::delete('/documents/{document}', [PlayerDashboardController::class, 'deleteDocument'])->name('documents.delete');
});

// Routes pour les transferts
Route::middleware(['auth:sanctum'])->group(function () {
    // Transferts
    Route::get('/transfers', [TransferController::class, 'index']);
    Route::post('/transfers', [TransferController::class, 'store']);
    Route::get('/transfers/{transfer}', [TransferController::class, 'show']);
    Route::put('/transfers/{transfer}', [TransferController::class, 'update']);
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy']);
    Route::post('/transfers/{transfer}/submit-fifa', [TransferController::class, 'submitToFifa']);
    Route::post('/transfers/{transfer}/check-itc', [TransferController::class, 'checkItcStatus']);
    Route::get('/transfers/statistics', [TransferController::class, 'statistics']);

    // Documents de transfert
    Route::get('/transfers/{transfer}/documents', [TransferDocumentController::class, 'index']);
    Route::post('/transfers/{transfer}/documents', [TransferDocumentController::class, 'store']);
    Route::get('/transfers/{transfer}/documents/{document}', [TransferDocumentController::class, 'show']);
    Route::put('/transfers/{transfer}/documents/{document}', [TransferDocumentController::class, 'update']);
    Route::delete('/transfers/{transfer}/documents/{document}', [TransferDocumentController::class, 'destroy']);
    Route::post('/transfers/{transfer}/documents/{document}/approve', [TransferDocumentController::class, 'approve']);
    Route::post('/transfers/{transfer}/documents/{document}/reject', [TransferDocumentController::class, 'reject']);

    // Paiements de transfert
    Route::get('/transfers/{transfer}/payments', [TransferPaymentController::class, 'index']);
    Route::post('/transfers/{transfer}/payments', [TransferPaymentController::class, 'store']);
    Route::get('/transfers/{transfer}/payments/{payment}', [TransferPaymentController::class, 'show']);
    Route::put('/transfers/{transfer}/payments/{payment}', [TransferPaymentController::class, 'update']);
    Route::delete('/transfers/{transfer}/payments/{payment}', [TransferPaymentController::class, 'destroy']);

    // Passeport du jour
    Route::get('/clubs/{club}/players/daily-passport', [PassportController::class, 'clubPassport']);
    Route::get('/federations/{federation}/daily-passport', [PassportController::class, 'federationPassport']);
    Route::get('/players/{player}/transfers', [PassportController::class, 'playerTransfers']);

    // Fédérations
    Route::get('/federations', [FederationController::class, 'index']);
    Route::get('/federations/{federation}', [FederationController::class, 'show']);
});

// Webhook FIFA (pas d'authentification requise)
Route::post('/webhooks/fifa', [FifaWebhookController::class, 'handle']);

// FIFA Sync Management Routes (protected)
Route::middleware(['web', 'auth'])->prefix('fifa')->group(function () {
    Route::get('/sync-stats', [FifaWebhookController::class, 'getSyncStats']);
    Route::get('/sync-logs', [FifaWebhookController::class, 'getSyncLogs']);
    Route::post('/sync', [FifaWebhookController::class, 'startSync']);
    Route::get('/connectivity/status', [FifaWebhookController::class, 'testConnectivity']);
    Route::post('/clear-cache', [FifaWebhookController::class, 'clearCache']);
    Route::post('/resolve-conflict/{logId}', [FifaWebhookController::class, 'resolveConflict']);
});

// API v1 routes
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Performance API routes - using api. prefix to avoid conflicts
    Route::apiResource('performances', PerformanceApiController::class)->names([
        'index' => 'api.performances.index',
        'store' => 'api.performances.store',
        'show' => 'api.performances.show',
        'update' => 'api.performances.update',
        'destroy' => 'api.performances.destroy',
    ]);
    Route::get('/performances/analytics', [PerformanceApiController::class, 'analytics'])->name('api.performances.analytics');
    Route::get('/performances/export', [PerformanceApiController::class, 'export'])->name('api.performances.export');
    Route::post('/performances/bulk-import', [PerformanceApiController::class, 'bulkImport'])->name('api.performances.bulk-import');
    Route::get('/performances/dashboard', [PerformanceApiController::class, 'dashboard'])->name('api.performances.dashboard');
    Route::get('/performances/compare', [PerformanceApiController::class, 'compare'])->name('api.performances.compare');
    Route::get('/performances/trends', [PerformanceApiController::class, 'trends'])->name('api.performances.trends');
    Route::post('/performances/generate-alerts', [PerformanceApiController::class, 'generateAlerts'])->name('api.performances.generate-alerts');
});

// DTN Manager API Routes
Route::middleware(['auth:sanctum'])->prefix('dtn')->group(function () {
    // National Teams
    Route::get('/teams', [DTNController::class, 'teams']);
    Route::post('/teams', [DTNController::class, 'storeTeam']);
    Route::get('/teams/{team}', [DTNController::class, 'showTeam']);
    Route::put('/teams/{team}', [DTNController::class, 'updateTeam']);
    Route::delete('/teams/{team}', [DTNController::class, 'destroyTeam']);
    
    // International Selections
    Route::get('/selections', [DTNController::class, 'selections']);
    Route::post('/selections', [DTNController::class, 'storeSelection']);
    Route::get('/selections/{selection}', [DTNController::class, 'showSelection']);
    Route::put('/selections/{selection}', [DTNController::class, 'updateSelection']);
    Route::delete('/selections/{selection}', [DTNController::class, 'destroySelection']);
    
    // Expatriate Players
    Route::get('/expats', [DTNController::class, 'expats']);
    Route::get('/expats/{player}', [DTNController::class, 'showExpat']);
    
    // Medical Interface
    Route::get('/medical/{playerId}', [DTNController::class, 'medicalData']);
    Route::post('/medical/{playerId}/feedback', [DTNController::class, 'sendFeedback']);
    
    // FIFA Connect Integration
    Route::post('/fifa/export', [DTNController::class, 'exportToFifa']);
    Route::get('/fifa/calendar', [DTNController::class, 'fifaCalendar']);
    
    // Reports
    Route::get('/reports', [DTNController::class, 'reports']);
    Route::post('/reports/generate', [DTNController::class, 'generateReport']);
});

// RPM API Routes
Route::middleware(['auth:sanctum'])->prefix('rpm')->group(function () {
    // Training Sessions
    Route::get('/sessions', [RPMController::class, 'sessions']);
    Route::post('/sessions', [RPMController::class, 'storeSession']);
    Route::get('/sessions/{session}', [RPMController::class, 'showSession']);
    Route::put('/sessions/{session}', [RPMController::class, 'updateSession']);
    Route::delete('/sessions/{session}', [RPMController::class, 'destroySession']);
    
    // Match Preparation
    Route::get('/matches', [RPMController::class, 'matches']);
    Route::post('/matches', [RPMController::class, 'storeMatch']);
    Route::get('/matches/{match}', [RPMController::class, 'showMatch']);
    Route::put('/matches/{match}', [RPMController::class, 'updateMatch']);
    Route::delete('/matches/{match}', [RPMController::class, 'destroyMatch']);
    
    // Player Load Monitoring
    Route::get('/load', [RPMController::class, 'playerLoads']);
    Route::get('/load/{playerId}', [RPMController::class, 'showPlayerLoad']);
    Route::post('/load/{playerId}', [RPMController::class, 'updatePlayerLoad']);
    
    // Attendance Tracking
    Route::get('/attendance', [RPMController::class, 'attendance']);
    Route::post('/attendance', [RPMController::class, 'storeAttendance']);
    Route::get('/attendance/{sessionId}', [RPMController::class, 'showAttendance']);
    
    // Performance Sync
    Route::post('/sync/performance', [RPMController::class, 'syncToPerformance']);
    Route::get('/sync/status', [RPMController::class, 'syncStatus']);
    
    // Calendar
    Route::get('/calendar', [RPMController::class, 'calendar']);
    Route::get('/calendar/week/{date}', [RPMController::class, 'weekCalendar']);
    
    // Reports
    Route::get('/reports', [RPMController::class, 'reports']);
    Route::post('/reports/generate', [RPMController::class, 'generateReport']);
});

// ClubBridge API Routes (for foreign clubs)
Route::middleware(['auth:sanctum'])->prefix('club')->group(function () {
    Route::get('/player/{fifaId}/medical', [App\Http\Controllers\Api\V1\ClubBridgeController::class, 'medicalData']);
    Route::get('/player/{fifaId}/trainingload', [App\Http\Controllers\Api\V1\ClubBridgeController::class, 'trainingLoad']);
    Route::post('/player/{fifaId}/feedback', [App\Http\Controllers\Api\V1\ClubBridgeController::class, 'sendFeedback']);
});

Route::get('/fit/kpis', [FitDashboardController::class, 'kpis']);

// Route pour gérer les actions des clés automatiques
Route::post('/auto-key-action', [AutoKeyController::class, 'store'])->name('api.auto-key-action');
