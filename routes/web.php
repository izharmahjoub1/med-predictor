<?php

use App\Http\Controllers\FifaConnectController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\MedicalPredictionController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PlayerRegistrationController;
use App\Http\Controllers\CompetitionManagementController;
use App\Http\Controllers\HealthcareController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;




// Include auth routes (includes verification routes)
require __DIR__.'/auth.php';

// Routes publiques
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings Routes
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::patch('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');

    // User Management (Back Office)
    Route::prefix('user-management')->name('user-management.')->middleware('role:system_admin,association_admin')->group(function () {
        Route::get('/', [UserManagementController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', UserManagementController::class);
        Route::post('bulk-action', [UserManagementController::class, 'bulkAction'])->name('bulk-action');
        Route::get('export', [UserManagementController::class, 'export'])->name('export');
    });

    // FIFA Connect Routes
    Route::prefix('fifa')->name('fifa.')->group(function () {
        Route::get('/players', [FifaConnectController::class, 'getPlayers'])->name('players');
        Route::get('/players/{id}', [FifaConnectController::class, 'getPlayer'])->name('player');
        Route::post('/sync', [FifaConnectController::class, 'syncPlayers'])->name('sync');
        Route::get('/search', [FifaConnectController::class, 'searchPlayers'])->name('search');
        Route::get('/players/{id}/stats', [FifaConnectController::class, 'getPlayerStats'])->name('player.stats');
        Route::get('/connectivity', [FifaConnectController::class, 'showConnectivityStatus'])->name('connectivity');
        Route::get('/connectivity/api', [FifaConnectController::class, 'getConnectivityStatus'])->name('connectivity.api');
    });

    // Module 1: Player Registration
    Route::prefix('player-registration')->name('player-registration.')->middleware('role:club_admin,club_manager,club_medical,association_admin,association_registrar,association_medical,system_admin')->group(function () {
        Route::get('/', [PlayerRegistrationController::class, 'index'])->name('index');
        Route::resource('players', PlayerRegistrationController::class);
        Route::post('players/{player}/sync', [PlayerRegistrationController::class, 'sync'])->name('players.sync');
        Route::post('bulk-sync', [PlayerRegistrationController::class, 'bulkSync'])->name('bulk-sync');
        Route::get('export', [PlayerRegistrationController::class, 'export'])->name('export');
    });

    // Module 2: Competition Management
    Route::prefix('competition-management')->name('competition-management.')->middleware('role:association_admin,association_registrar,association_medical,system_admin')->group(function () {
        Route::get('/', [CompetitionManagementController::class, 'index'])->name('index');
        Route::resource('competitions', CompetitionManagementController::class);
        Route::post('competitions/{competition}/sync', [CompetitionManagementController::class, 'sync'])->name('competitions.sync');
        Route::post('bulk-sync', [CompetitionManagementController::class, 'bulkSync'])->name('bulk-sync');
        Route::get('export', [CompetitionManagementController::class, 'export'])->name('export');
        Route::post('competitions/{competition}/generate-fixtures', [CompetitionManagementController::class, 'generateFixtures'])->name('competitions.generate-fixtures');
        Route::get('competitions/{competition}/standings', [CompetitionManagementController::class, 'standings'])->name('competitions.standings');
    });

    // Module 3: Healthcare
    Route::prefix('healthcare')->name('healthcare.')->middleware('role:club_admin,club_manager,club_medical,association_admin,association_registrar,association_medical,system_admin')->group(function () {
        Route::get('/', [HealthcareController::class, 'index'])->name('index');
        Route::resource('records', HealthcareController::class);
        Route::post('records/{healthRecord}/sync', [HealthcareController::class, 'sync'])->name('records.sync');
        Route::post('bulk-sync', [HealthcareController::class, 'bulkSync'])->name('bulk-sync');
        Route::get('export', [HealthcareController::class, 'export'])->name('export');
        Route::get('predictions', [HealthcareController::class, 'predictions'])->name('predictions');
    });

    // Medical Predictions Routes
    Route::prefix('medical-predictions')->name('medical-predictions.')->middleware('role:club_admin,club_manager,club_medical,association_admin,association_registrar,association_medical,system_admin')->group(function () {
        Route::get('/', [MedicalPredictionController::class, 'index'])->name('index');
        Route::get('/create', [MedicalPredictionController::class, 'create'])->name('create');
        Route::post('/', [MedicalPredictionController::class, 'store'])->name('store');
        Route::get('/{medicalPrediction}', [MedicalPredictionController::class, 'show'])->name('show');
        Route::get('/{medicalPrediction}/edit', [MedicalPredictionController::class, 'edit'])->name('edit');
        Route::put('/{medicalPrediction}', [MedicalPredictionController::class, 'update'])->name('update');
        Route::delete('/{medicalPrediction}', [MedicalPredictionController::class, 'destroy'])->name('destroy');
        Route::get('/dashboard', [MedicalPredictionController::class, 'dashboard'])->name('dashboard');
    });

    // Player bulk import routes (accessible from club management)
    Route::get('players/bulk-import', [PlayerController::class, 'bulkImportForm'])->name('players.bulk-import');
    Route::post('players/bulk-import', [PlayerController::class, 'bulkImport'])->name('players.bulk-import.store');
    Route::get('players/export', [PlayerController::class, 'exportPlayers'])->name('players.export');

    // Legacy routes for backward compatibility (deprecated)
    Route::prefix('legacy')->name('legacy.')->group(function () {
        Route::resource('health-records', HealthRecordController::class);
        Route::post('health-records/{healthRecord}/generate-prediction', [HealthRecordController::class, 'generatePrediction'])->name('health-records.generate-prediction');
        Route::resource('medical-predictions', MedicalPredictionController::class);
Route::get('medical-predictions/dashboard', [MedicalPredictionController::class, 'dashboard'])->name('medical-predictions.dashboard');

// API route for health records
Route::get('/api/players/{player}/health-records', function ($player) {
    $healthRecords = \App\Models\HealthRecord::where('player_id', $player)
        ->orderBy('record_date', 'desc')
        ->get(['id', 'record_date', 'status']);
    
    return response()->json($healthRecords);
})->name('api.players.health-records');
        Route::resource('players', PlayerController::class);
        Route::get('players/{player}/health-records', [PlayerController::class, 'healthRecords'])->name('players.health-records');
    });
});

// Club Management Routes (Legacy - will be integrated into modules)
Route::middleware(['auth'])->group(function () {
    // Club Dashboard
    Route::get('/club-management', [App\Http\Controllers\ClubManagementController::class, 'dashboard'])
        ->name('club-management.dashboard');

    // Team Management
    Route::prefix('club-management')->name('club-management.')->group(function () {
        Route::get('/teams', [App\Http\Controllers\ClubManagementController::class, 'teams'])
            ->name('teams.index');
        Route::get('/teams/create', [App\Http\Controllers\ClubManagementController::class, 'createTeam'])
            ->name('teams.create');
        Route::post('/teams', [App\Http\Controllers\ClubManagementController::class, 'storeTeam'])
            ->name('teams.store');
        Route::get('/teams/{team}', [App\Http\Controllers\ClubManagementController::class, 'showTeam'])
            ->name('teams.show');
        Route::get('/teams/{team}/edit', [App\Http\Controllers\ClubManagementController::class, 'editTeam'])
            ->name('teams.edit');
        Route::put('/teams/{team}', [App\Http\Controllers\ClubManagementController::class, 'updateTeam'])
            ->name('teams.update');
        Route::delete('/teams/{team}', [App\Http\Controllers\ClubManagementController::class, 'destroyTeam'])
            ->name('teams.destroy');
        Route::get('/teams/{team}/builder', [App\Http\Controllers\ClubManagementController::class, 'teamBuilder'])
            ->name('teams.builder');
        Route::post('/teams/{team}/optimal-lineup', [App\Http\Controllers\ClubManagementController::class, 'generateOptimalLineup'])
            ->name('teams.optimal-lineup');
        Route::get('/teams/{team}/manage-players', [App\Http\Controllers\ClubManagementController::class, 'manageTeamPlayers'])
            ->name('teams.manage-players');
        Route::post('/teams/{team}/add-player', [App\Http\Controllers\ClubManagementController::class, 'addPlayerToTeam'])
            ->name('teams.add-player');
        Route::delete('/teams/{team}/remove-player/{player}', [App\Http\Controllers\ClubManagementController::class, 'removePlayerFromTeam'])
            ->name('teams.remove-player');
    });

    // Lineup Management
    Route::prefix('club-management')->name('club-management.')->group(function () {
        Route::get('/lineups', [App\Http\Controllers\ClubManagementController::class, 'lineups'])
            ->name('lineups.index');
        Route::get('/lineups/create', [App\Http\Controllers\ClubManagementController::class, 'createLineup'])
            ->name('lineups.create');
        Route::get('/teams/{team}/lineups/create', [App\Http\Controllers\ClubManagementController::class, 'createLineup'])
            ->name('lineups.create-for-team');
        Route::post('/lineups', [App\Http\Controllers\ClubManagementController::class, 'storeLineup'])
            ->name('lineups.store');
        Route::get('/lineups/{lineup}', [App\Http\Controllers\ClubManagementController::class, 'showLineup'])
            ->name('lineups.show');
    });

    // Player Licensing
    Route::prefix('club-management')->name('club-management.')->group(function () {
        Route::get('/licenses', [App\Http\Controllers\ClubManagementController::class, 'licenses'])
            ->name('licenses.index');
        Route::get('/licenses/create', [App\Http\Controllers\ClubManagementController::class, 'createLicense'])
            ->name('licenses.create');
        Route::get('/players/{player}/licenses/create', [App\Http\Controllers\ClubManagementController::class, 'createLicense'])
            ->name('licenses.create-for-player');
        Route::post('/licenses', [App\Http\Controllers\ClubManagementController::class, 'storeLicense'])
            ->name('licenses.store');
        Route::get('/licenses/{license}', [App\Http\Controllers\ClubManagementController::class, 'showLicense'])
            ->name('licenses.show');
        Route::get('/licenses/{license}/print', [App\Http\Controllers\ClubManagementController::class, 'printLicense'])
            ->name('licenses.print');
        Route::get('/licenses/{license}/test-print', [App\Http\Controllers\ClubManagementController::class, 'testPrintLicense'])
            ->name('licenses.test-print');
        Route::post('/licenses/{license}/approve', [App\Http\Controllers\ClubManagementController::class, 'approveLicense'])
            ->name('licenses.approve');
        Route::post('/licenses/{license}/reject', [App\Http\Controllers\ClubManagementController::class, 'rejectLicense'])
            ->name('licenses.reject');
        Route::get('/licenses/{license}/edit', [App\Http\Controllers\ClubManagementController::class, 'editLicense'])
            ->name('licenses.edit');
        Route::delete('/licenses/{license}', [App\Http\Controllers\ClubManagementController::class, 'destroyLicense'])
            ->name('licenses.destroy');
        Route::put('/licenses/{license}', [App\Http\Controllers\ClubManagementController::class, 'updateLicense'])
            ->name('licenses.update');
    });

    // FIFA Connect Integration
    Route::prefix('club-management')->name('club-management.')->group(function () {
        Route::post('/sync-fifa-data', [App\Http\Controllers\ClubManagementController::class, 'syncFifaData'])
            ->name('sync-fifa-data');
    });
});




