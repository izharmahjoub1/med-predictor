<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PerformanceRecommendationController;
use App\Http\Controllers\PlayerPassportController;
use App\Http\Controllers\FifaController;
use App\Http\Controllers\Hl7Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ClubManagementController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\MatchSheetController;
use App\Http\Controllers\CompetitionManagementController;
use App\Http\Controllers\SeasonManagementController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\BackOffice\LicenseTypeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\StakeholderGalleryController;
use App\Http\Controllers\PlayerRegistrationController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\MedicalPredictionController;
use App\Http\Controllers\RefereeController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\HealthcareController;
use App\Http\Controllers\FitDashboardController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PlayerLicenseReviewController;
use App\Http\Controllers\ClubPlayerLicenseController;
use App\Http\Controllers\PlayerLicenseController;
use App\Http\Controllers\LicenseRequestController;
use App\Http\Controllers\AccountRequestController;
use App\Http\Controllers\PlayerDashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LandingPageController;

// Language switching routes - must be at the top
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en'])) {
        // Set both cookie and session for maximum compatibility
        $cookie = cookie('locale', $locale, 60 * 24 * 30); // 30 days
        Session::put('locale', $locale);
        
        // Debug: Log the change
        Log::info("Language changed to: " . $locale);
        Log::info("Cookie set: " . $locale);
        Log::info("Session set: " . $locale);
    }
    
    // Force redirect with cache-busting parameters
    $timestamp = time();
    $random = rand(1000, 9999);
    return Redirect::to('/?t=' . $timestamp . '&r=' . $random)->withCookie($cookie ?? cookie('locale', 'fr', 60 * 24 * 30))->withHeaders([
        'Cache-Control' => 'no-cache, no-store, must-revalidate, private',
        'Pragma' => 'no-cache',
        'Expires' => '0',
        'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT'
    ]);
})->name('language.switch');

Route::post('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
        Session::save();
        
        // Debug: Log the change
        Log::info("Language changed to: " . $locale);
        Log::info("Session locale: " . Session::get('locale'));
        Log::info("App locale: " . App::getLocale());
    }
    return Redirect::back();
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing page - accessible to all visitors (no auth required)
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Authentication routes (accessible to guests)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Account request routes (accessible to all)
Route::post('/account-request', [AccountRequestController::class, 'store'])->name('account-request.store');
Route::post('/registration-requests', [AccountRequestController::class, 'store'])->name('registration-requests.store');
Route::get('/account-request/football-types', [AccountRequestController::class, 'getFootballTypes'])->name('account-request.football-types');
Route::get('/account-request/organization-types', [AccountRequestController::class, 'getOrganizationTypes'])->name('account-request.organization-types');
Route::get('/account-request/fifa-connect-types', [AccountRequestController::class, 'getFifaConnectTypes'])->name('account-request.fifa-connect-types');
Route::get('/account-request/fifa-associations', [AccountRequestController::class, 'getFifaAssociations'])->name('account-request.fifa-associations');

// Admin account request management routes (protected by auth)
Route::middleware(['auth', 'role:association_admin,association_registrar,system_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/account-requests', function() {
        return view('admin.account-requests.index');
    })->name('account-requests.index');
    Route::get('/account-requests/{accountRequest}', [AccountRequestController::class, 'show'])->name('account-requests.show');
    Route::post('/account-requests/{accountRequest}/approve', [AccountRequestController::class, 'approve'])->name('account-requests.approve');
    Route::post('/account-requests/{accountRequest}/reject', [AccountRequestController::class, 'reject'])->name('account-requests.reject');
    Route::post('/account-requests/{accountRequest}/contact', [AccountRequestController::class, 'markAsContacted'])->name('account-requests.contact');
});

// Legacy welcome page (optional)
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Vue.js Application Routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/app', function () {
        return view('app');
    })->name('app');

    Route::get('/app', function () {
        return view('app');
    })->name('app.index');

    Route::get('/test', function () {
        return view('app');
    })->name('test');

    Route::get('/test-simple', function () {
        return view('test-simple');
    })->name('test-simple');

    Route::get('/simple-test', function () {
        return view('app');
    })->name('simple-test');

    Route::get('/app/{any}', function () {
        return view('app');
    })->where('any', '.*')->name('app.catch-all');
});

// DTN Manager Module Routes (protected by auth)
Route::prefix('dtn')->middleware(['auth'])->group(function () {
    Route::get('/', [ModuleController::class, 'dtnDashboard'])->name('dtn.dashboard');
    Route::get('/teams', [ModuleController::class, 'dtnTeams'])->name('dtn.teams');
    Route::get('/selections', [ModuleController::class, 'dtnSelections'])->name('dtn.selections');
    Route::get('/expats', [ModuleController::class, 'dtnExpats'])->name('dtn.expats');
    Route::get('/medical', [ModuleController::class, 'dtnMedical'])->name('dtn.medical');
    Route::get('/planning', [ModuleController::class, 'dtnPlanning'])->name('dtn.planning');
    Route::get('/reports', [ModuleController::class, 'dtnReports'])->name('dtn.reports');
    Route::get('/settings', [ModuleController::class, 'dtnSettings'])->name('dtn.settings');
});

// RPM Module Routes (protected by auth)
Route::prefix('rpm')->middleware(['auth'])->group(function () {
    Route::get('/', [ModuleController::class, 'rpmDashboard'])->name('rpm.dashboard');
    Route::get('/calendar', [ModuleController::class, 'rpmCalendar'])->name('rpm.calendar');
    Route::get('/sessions', [ModuleController::class, 'rpmSessions'])->name('rpm.sessions');
    Route::get('/matches', [ModuleController::class, 'rpmMatches'])->name('rpm.matches');
    Route::get('/load', [ModuleController::class, 'rpmLoad'])->name('rpm.load');
    Route::get('/attendance', [ModuleController::class, 'rpmAttendance'])->name('rpm.attendance');
    Route::get('/reports', [ModuleController::class, 'rpmReports'])->name('rpm.reports');
    Route::get('/sync', [ModuleController::class, 'rpmSync'])->name('rpm.sync');
    Route::get('/settings', [ModuleController::class, 'rpmSettings'])->name('rpm.settings');
});

// Authentication routes (logout only - login/register are in auth.php)
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile routes
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    
    Route::get('/profile/settings', function () {
        return view('profile.settings');
    })->name('profile.settings');
});

// Dashboard Routes (protected by auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard principal - redirection vers les modules
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Placeholders pour navigation manquante
    Route::get('/club-player-assignments', [ClubManagementController::class, 'clubPlayerAssignments'])->name('club-player-assignments.index');

    Route::get('/content', [ContentManagementController::class, 'index'])->name('content.index');
    Route::get('/player-passports', [PlayerPassportController::class, 'index'])->name('player-passports.index');
    Route::get('/match-sheet', [MatchSheetController::class, 'index'])->name('match-sheet.index');
    Route::get('/performance-recommendations', [PerformanceRecommendationController::class, 'index'])->name('performance-recommendations.index');
    Route::get('/fixtures', [CompetitionManagementController::class, 'fixturesIndex'])->name('fixtures.index');
    Route::get('/seasons', [SeasonManagementController::class, 'index'])->name('seasons.index');
    Route::get('/federations', [FederationController::class, 'index'])->name('federations.index');
    Route::get('/registration-requests', [RegistrationRequestController::class, 'index'])->name('registration-requests.index');
    Route::resource('licenses', LicenseController::class)->except(['show']);
    Route::resource('license-requests', LicenseRequestController::class);
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/fifa/contracts', function () {
        return view('fifa.contracts');
    })->name('fifa.contracts');
    Route::get('/fifa/analytics', function () {
        return view('fifa.analytics');
    })->name('fifa.analytics');
    Route::get('/data-sync', function () {
        return view('data-sync.index');
    })->name('data-sync.index');
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    Route::get('/license-types', [LicenseTypeController::class, 'index'])->name('license-types.index');
    // Players resource routes
    Route::prefix('players')->name('players.')->group(function () {
        Route::get('/', [PlayerController::class, 'index'])->name('index');
        Route::get('/create', [PlayerController::class, 'create'])->name('create');
        Route::post('/', [PlayerController::class, 'store'])->name('store');
        Route::get('/{player}', [PlayerController::class, 'show'])->name('show');
        Route::get('/{player}/edit', [PlayerController::class, 'edit'])->name('edit');
        Route::put('/{player}', [PlayerController::class, 'update'])->name('update');
        Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('destroy');
        Route::get('/{player}/health-records', [PlayerController::class, 'healthRecords'])->name('health-records');
        Route::get('/{player}/predictions', [PlayerController::class, 'predictions'])->name('predictions');
        Route::get('/bulk-import', [PlayerController::class, 'bulkImportForm'])->name('bulk-import-form');
        Route::post('/bulk-import', [PlayerController::class, 'bulkImport'])->name('bulk-import-store');
        Route::get('/search', [PlayerController::class, 'search'])->name('search');
        Route::get('/api/players', [PlayerController::class, 'apiPlayers'])->name('api.players');
        Route::post('/import-from-fifa', [PlayerController::class, 'importFromFifa'])->name('import-from-fifa');
        Route::post('/export', [PlayerController::class, 'exportPlayers'])->name('export');
    });
    Route::get('/teams', [ClubManagementController::class, 'teams'])->name('teams.index');
    // Redirections pour compatibilité navigation back-office
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail.index');
    
    Route::get('/dynamic-dashboard', function () {
        return view('dynamic-dashboard');
    })->name('dynamic-dashboard');
    
    // Route::get('/fit-dashboard', function () {
    //     return view('fit-dashboard');
    // })->name('fit-dashboard');
    
    Route::get('/football-type-selector', function () {
        return view('football-type-selector');
    })->name('football-type-selector');
    
    Route::get('/profile-selector', function () {
        return view('profile-selector');
    })->name('profile-selector');

    Route::get('/logs', [BackOfficeController::class, 'logs'])->name('logs.index');
    
    Route::get('/system-status', [BackOfficeController::class, 'systemStatus'])->name('system-status.index');
    Route::get('/settings', [BackOfficeController::class, 'settings'])->name('settings.index');
});

// Performance Management Routes
Route::middleware(['auth'])->group(function () {
    // Performance routes - explicit definitions to avoid conflicts
    Route::get('/performances', [PerformanceController::class, 'index'])->name('performances.index');
    Route::get('/performances/create', [PerformanceController::class, 'create'])->name('performances.create');
    Route::post('/performances', [PerformanceController::class, 'store'])->name('performances.store');
    Route::get('/performances/{performance}', [PerformanceController::class, 'show'])->name('performances.show');
    Route::get('/performances/{performance}/edit', [PerformanceController::class, 'edit'])->name('performances.edit');
    Route::put('/performances/{performance}', [PerformanceController::class, 'update'])->name('performances.update');
    Route::delete('/performances/{performance}', [PerformanceController::class, 'destroy'])->name('performances.destroy');
    Route::get('/performances/dashboard', [PerformanceController::class, 'dashboard'])->name('performances.dashboard');
    Route::get('/performances/analytics', [PerformanceController::class, 'analytics'])->name('performances.analytics');
    Route::get('/performances/export', [PerformanceController::class, 'export'])->name('performances.export');
    Route::post('/performances/bulk-import', [PerformanceController::class, 'bulkImport'])->name('performances.bulk-import');
    Route::get('/performances/compare', [PerformanceController::class, 'compare'])->name('performances.compare');
    Route::get('/performances/trends', [PerformanceController::class, 'trends'])->name('performances.trends');
    Route::post('/performances/generate-alerts', [PerformanceController::class, 'generateAlerts'])->name('performances.generate-alerts');
    
    // Performance recommendations
    Route::resource('performance-recommendations', PerformanceRecommendationController::class);
    
    // Player passports
    Route::resource('player-passports', PlayerPassportController::class);
    
    // FIFA Connect routes
    Route::prefix('fifa')->group(function () {
        Route::get('/dashboard', [FifaController::class, 'dashboard'])->name('fifa.dashboard');
        Route::post('/sync-player/{fifaId}', [FifaController::class, 'syncPlayer'])->name('fifa.sync-player');
        Route::get('/compliance/{fifaId}', [FifaController::class, 'checkCompliance'])->name('fifa.compliance');
        Route::get('/sync-dashboard', [FifaController::class, 'syncDashboard'])->name('fifa.sync-dashboard');
        Route::get('/statistics', [FifaController::class, 'statistics'])->name('fifa.statistics');
        Route::get('/statistics/api', [FifaController::class, 'statisticsApi'])->name('fifa.statistics.api');
        Route::get('/connectivity', [FifaController::class, 'connectivity'])->name('fifa.connectivity');
        Route::get('/connectivity/status', [FifaController::class, 'connectivityStatus'])->name('fifa.connectivity.status');
        Route::get('/players/search', function () {
            return view('fifa.players.search');
        })->name('fifa.players.search');
        Route::post('/sync-players', [FifaController::class, 'syncPlayers'])->name('fifa.sync-players');
    });
    
    // HL7 FHIR routes
    Route::prefix('hl7')->group(function () {
        Route::post('/sync-performance/{performance}', [Hl7Controller::class, 'syncPerformance'])->name('hl7.sync-performance');
    });
    
    // Alerts
    Route::get('/alerts/performance', function () {
        return view('alerts.performance');
    })->name('alerts.performance');
});

// Vue Component Routes
Route::middleware(['auth'])->group(function () {
    // Player Dashboard
    Route::get('/player-dashboard', [PlayerDashboardController::class, 'index'])->name('player-dashboard.index');
    
    // League Championship - Redirect to Competition Management
    Route::prefix('league-championship')->group(function () {
        Route::get('/', function () {
            return redirect()->route('competition-management.index');
        })->name('league-championship.index');
        
        Route::get('/match/{id}', function ($id) {
            return redirect()->route('competition-management.matches.show', $id);
        })->name('league-championship.match');
    });
    
    // Transfers
    Route::prefix('transfers')->name('transfers.')->group(function () {
        Route::get('/', [TransferController::class, 'index'])->name('index');
        Route::get('/create', [TransferController::class, 'create'])->name('create');
        Route::post('/', [TransferController::class, 'store'])->name('store');
        Route::get('/{transfer}', [TransferController::class, 'show'])->name('show');
        Route::get('/{transfer}/edit', [TransferController::class, 'edit'])->name('edit');
        Route::put('/{transfer}', [TransferController::class, 'update'])->name('update');
        Route::delete('/{transfer}', [TransferController::class, 'destroy'])->name('destroy');
        Route::post('/{transfer}/submit-to-fifa', [TransferController::class, 'submitToFifa'])->name('submit-to-fifa');
        Route::get('/{transfer}/check-itc-status', [TransferController::class, 'checkItcStatus'])->name('check-itc-status');
        Route::get('/statistics', [TransferController::class, 'statistics'])->name('statistics');
    });
    
    // Daily Passport
    Route::get('/daily-passport', function () {
        return view('daily-passport.index');
    })->name('daily-passport.index');
    
    // Medical Predictions
    Route::prefix('medical-predictions')->name('medical-predictions.')->group(function () {
        Route::get('/', [MedicalPredictionController::class, 'index'])->name('index');
        Route::get('/dashboard', [MedicalPredictionController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [MedicalPredictionController::class, 'create'])->name('create');
        Route::post('/', [MedicalPredictionController::class, 'store'])->name('store');
        Route::get('/{medicalPrediction}', [MedicalPredictionController::class, 'show'])->name('show');
        Route::get('/{medicalPrediction}/edit', [MedicalPredictionController::class, 'edit'])->name('edit');
        Route::put('/{medicalPrediction}', [MedicalPredictionController::class, 'update'])->name('update');
        Route::delete('/{medicalPrediction}', [MedicalPredictionController::class, 'destroy'])->name('destroy');
    });
    
    // Health Records
    Route::prefix('health-records')->group(function () {
        Route::get('/', function () {
            // Données par défaut pour la vue
            $healthRecords = collect([]); // Collection vide pour l'instant
            return view('health-records.index', compact('healthRecords'));
        })->name('health-records.index');
        
        Route::get('/create', function () {
            return view('health-records.create');
        })->name('health-records.create');
        
        Route::get('/{id}', function ($id) {
            return view('health-records.show', ['id' => $id]);
        })->name('health-records.show');
    });
    
    // Club Management
    Route::prefix('club-management')->group(function () {
        Route::get('/', function () {
            return view('club-management.index');
        })->name('club-management.index');
        
        Route::get('/dashboard', function () {
            // Dashboard data par défaut pour l'instant
            $dashboardData = [
                'is_association_admin' => false,
                'stats' => [
                    'total_players' => 0,
                    'total_teams' => 0,
                    'total_licenses' => 0,
                    'active_licenses' => 0,
                    'pending_licenses' => 0,
                    'expired_licenses' => 0,
                    'expiring_licenses' => 0
                ],
                'club' => null,
                'association' => null,
                'recent_activities' => [],
                'top_players' => [],
                'license_status_chart' => [],
                'monthly_registrations' => []
            ];
            
            return view('club-management.dashboard', compact('dashboardData'));
        })->name('club-management.dashboard');
        
        Route::get('/players', [ClubManagementController::class, 'players'])->name('club-management.players.index');
        
        Route::get('/players/import', function () {
            return view('club-management.players.import');
        })->name('club-management.players.import');
        
        Route::get('/players/bulk-import', function () {
            return view('club-management.players.bulk-import');
        })->name('club-management.players.bulk-import');
        
        Route::get('/players/export', function () {
            return view('club-management.players.export');
        })->name('club-management.players.export');
        

        
        // Teams routes
        Route::prefix('teams')->name('club-management.teams.')->group(function () {
            Route::get('/create', [ClubManagementController::class, 'createTeam'])->name('create');
            Route::post('/', [ClubManagementController::class, 'storeTeam'])->name('store');
            Route::get('/{team}', [ClubManagementController::class, 'showTeam'])->name('show');
            Route::get('/{team}/edit', [ClubManagementController::class, 'editTeam'])->name('edit');
            Route::put('/{team}', [ClubManagementController::class, 'updateTeam'])->name('update');
            Route::delete('/{team}', [ClubManagementController::class, 'destroyTeam'])->name('destroy');
            Route::get('/{team}/manage-players', [ClubManagementController::class, 'manageTeamPlayers'])->name('manage-players');
            Route::post('/{team}/add-player', [ClubManagementController::class, 'addPlayerToTeam'])->name('add-player');
            Route::delete('/{team}/remove-player/{player}', [ClubManagementController::class, 'removePlayerFromTeam'])->name('remove-player');
        });
        
        Route::get('/licenses', [ClubManagementController::class, 'licenses'])->name('club-management.licenses.index');
        
        // Licenses routes
        Route::prefix('licenses')->name('club-management.licenses.')->group(function () {
            Route::get('/create', [ClubManagementController::class, 'createLicense'])->name('license.create');
            Route::post('/', [ClubManagementController::class, 'storeLicense'])->name('license.store');
            Route::get('/{license}', [ClubManagementController::class, 'showLicense'])->name('license.show');
            Route::get('/{license}/edit', [ClubManagementController::class, 'editLicense'])->name('licenedit');
            Route::put('/{license}', [ClubManagementController::class, 'updateLicense'])->name('license.update');
            Route::delete('/{license}', [ClubManagementController::class, 'destroyLicense'])->name('license.destroy');
            Route::get('/{license}/print', [ClubManagementController::class, 'printLicense'])->name('print');
            Route::post('/{license}/approve', [ClubManagementController::class, 'approveLicense'])->name('approve');
            Route::post('/{license}/reject', [ClubManagementController::class, 'rejectLicense'])->name('reject');
            Route::post('/{license}/renew', [ClubManagementController::class, 'renewLicense'])->name('renew');
            Route::post('/{license}/suspend', [ClubManagementController::class, 'suspendLicense'])->name('suspend');
            
            // License templates
            Route::get('/templates', [ClubManagementController::class, 'licenseTemplates'])->name('templates');
            Route::get('/templates/create', [ClubManagementController::class, 'createLicenseTemplate'])->name('templates.create');
            Route::post('/templates', [ClubManagementController::class, 'storeLicenseTemplate'])->name('templates.store');
            Route::get('/templates/{template}/edit', [ClubManagementController::class, 'editLicenseTemplate'])->name('templates.edit');
            Route::put('/templates/{template}', [ClubManagementController::class, 'updateLicenseTemplate'])->name('templates.update');
            Route::delete('/templates/{template}', [ClubManagementController::class, 'destroyLicenseTemplate'])->name('templates.destroy');
            
            // Compliance and audit
            Route::get('/compliance-report', [ClubManagementController::class, 'licenseComplianceReport'])->name('compliance-report');
            Route::get('/{license}/audit-trail', [ClubManagementController::class, 'licenseAuditTrail'])->name('audit-trail');
        });
        
        Route::get('/lineups', [ClubManagementController::class, 'lineups'])->name('club-management.lineups.index');
        
        // Lineups routes
        Route::prefix('lineups')->name('club-management.lineups.')->group(function () {
            Route::get('/create', [ClubManagementController::class, 'createLineup'])->name('lineup.create');
            Route::post('/', [ClubManagementController::class, 'storeLineup'])->name('lineup.store');
            Route::get('/{lineup}', [ClubManagementController::class, 'showLineup'])->name('lineupshow');
            Route::get('/{lineup}/edit', [ClubManagementController::class, 'editLineup'])->name('lineup.edit');
            Route::put('/{lineup}', [ClubManagementController::class, 'updateLineup'])->name('lineup.update');
            Route::delete('/{lineup}', [ClubManagementController::class, 'destroyLineup'])->name('lineup.destroy');
            Route::get('/generator', [ClubManagementController::class, 'lineupGenerator'])->name('lineup.generator');
            Route::post('/generate', [ClubManagementController::class, 'generateLineup'])->name('lineupgenerate');
            Route::post('/bulk-generate', [ClubManagementController::class, 'bulkGenerateLineups'])->name('bulk-generate');
        });
    });
    
    // Referee Portal
    Route::prefix('referee')->name('referee.')->group(function () {
        Route::get('/dashboard', [RefereeController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/match-assignments', [RefereeController::class, 'matchAssignments'])->name('match-assignments');
        
        Route::get('/competition-schedule', [RefereeController::class, 'competitionSchedule'])->name('competition-schedule');
        
        Route::get('/create-match-report', [RefereeController::class, 'createMatchReport'])->name('create-match-report');
        
        Route::get('/performance-stats', [RefereeController::class, 'performanceStats'])->name('performance-stats');
        
        Route::get('/settings', [RefereeController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [RefereeController::class, 'updateSettings'])->name('settings.update');
        
        Route::get('/match-sheet/{id}', [RefereeController::class, 'matchSheet'])->name('match-sheet');
    });
    
    // Rankings
    Route::prefix('rankings')->name('rankings.')->group(function () {
        Route::get('/', [RankingsController::class, 'index'])->name('index');
    });
    
    // Match Sheet
    Route::prefix('match-sheet')->group(function () {
        Route::get('/{id}', function ($id) {
            return view('match-sheet.show', ['id' => $id]);
        })->name('match-sheet.show');
        
        // Route::get('/{id}/edit', function ($id) {
        //     return view('match-sheet.edit', ['id' => $id]);
        
        // Route::get('/{id}/import-players', function ($id) {
        //     return view('match-sheet.import-players', ['id' => $id]);
    });
    
    // Stakeholder Gallery
    Route::get('/stakeholder-gallery', [StakeholderGalleryController::class, 'index'])->name('stakeholder-gallery.index');
    
    // Player Registration
    Route::prefix('player-registration')->name('player-registration.')->group(function () {
        Route::get('/', [PlayerRegistrationController::class, 'index'])->name('index');
        Route::get('/create', [PlayerRegistrationController::class, 'create'])->name('create');
        Route::post('/', [PlayerRegistrationController::class, 'store'])->name('store');
        Route::get('/{player}', [PlayerRegistrationController::class, 'show'])->name('show');
        Route::get('/{player}/edit', [PlayerRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{player}', [PlayerRegistrationController::class, 'update'])->name('update');
        Route::delete('/{player}', [PlayerRegistrationController::class, 'destroy'])->name('destroy');
        
        // Additional routes needed by the view
        Route::get('/export', [PlayerRegistrationController::class, 'export'])->name('export');
        Route::get('/create-stakeholder', [PlayerRegistrationController::class, 'createStakeholder'])->name('create-stakeholder');
        Route::post('/store-stakeholder', [PlayerRegistrationController::class, 'storeStakeholder'])->name('store-stakeholder');
        Route::post('/bulk-sync', [PlayerRegistrationController::class, 'bulkSync'])->name('bulk-sync');
        Route::post('/{player}/sync', [PlayerRegistrationController::class, 'sync'])->name('sync');
        Route::get('/{player}/health-records', [PlayerRegistrationController::class, 'healthRecords'])->name('health-records');
    });
    
    // Competition Management
    Route::prefix('competition-management')->name('competition-management.')->group(function () {
        
        Route::get('/', [CompetitionManagementController::class, 'index'])->name('index');
        
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [CompetitionManagementController::class, 'competitionsIndex'])->name('index');
            Route::get('/create', [CompetitionManagementController::class, 'create'])->name('create');
            Route::post('/', [CompetitionManagementController::class, 'store'])->name('store');
            Route::get('/{competition}', [CompetitionManagementController::class, 'show'])->name('show');
            Route::get('/{competition}/edit', [CompetitionManagementController::class, 'edit'])->name('edit');
            Route::put('/{competition}', [CompetitionManagementController::class, 'update'])->name('update');
            Route::delete('/{competition}', [CompetitionManagementController::class, 'destroy'])->name('destroy');
            Route::get('/{competition}/fixtures', [CompetitionManagementController::class, 'fixtures'])->name('fixtures');
            Route::get('/{competition}/standings', [CompetitionManagementController::class, 'standings'])->name('standings');
            Route::get('/{competition}/register-team-form', [CompetitionManagementController::class, 'showRegisterTeamForm'])->name('register-team-form');
            Route::post('/{competition}/register-team', [CompetitionManagementController::class, 'registerTeam'])->name('register-team');
            Route::post('/{competition}/sync', [CompetitionManagementController::class, 'sync'])->name('sync');
            Route::get('/{competition}/manual-fixtures', [CompetitionManagementController::class, 'showManualFixturesForm'])->name('manual-fixtures');
            Route::post('/{competition}/manual-fixtures', [CompetitionManagementController::class, 'storeManualFixtures'])->name('manual-fixtures.store');
            Route::get('/{competition}/regenerate-fixtures', [CompetitionManagementController::class, 'regenerateFixtures'])->name('regenerate-fixtures');
            Route::get('/{competition}/validate-fixtures', [CompetitionManagementController::class, 'validateFixtures'])->name('validate-fixtures');
        });
        
        // Match-related routes (without competitions prefix to match view expectations)
        Route::prefix('matches')->name('matches.')->group(function () {
            Route::get('/', [CompetitionManagementController::class, 'matchesIndex'])->name('index');
            Route::get('/create', [CompetitionManagementController::class, 'createMatch'])->name('create');
            Route::post('/', [CompetitionManagementController::class, 'storeMatch'])->name('store');
            Route::get('/{match}', [CompetitionManagementController::class, 'showMatch'])->name('show');
            Route::get('/{match}/edit', [CompetitionManagementController::class, 'editMatch'])->name('edit');
            Route::put('/{match}', [CompetitionManagementController::class, 'updateMatch'])->name('update');
            Route::delete('/{match}', [CompetitionManagementController::class, 'destroyMatch'])->name('destroy');
            Route::get('/{match}/match-sheet', [CompetitionManagementController::class, 'matchSheet'])->name('match-sheet');
            Route::get('/{match}/match-sheet/edit', [CompetitionManagementController::class, 'editMatchSheet'])->name('match-sheet.edit');
            Route::put('/{match}/match-sheet', [CompetitionManagementController::class, 'updateMatchSheet'])->name('match-sheet.update');
            Route::post('/{match}/match-sheet/submit', [CompetitionManagementController::class, 'submitMatchSheet'])->name('match-sheet.submit');
            Route::post('/{match}/match-sheet/sign-team', [CompetitionManagementController::class, 'signTeamMatchSheet'])->name('match-sheet.sign-team');
            Route::post('/{match}/match-sheet/sign-lineup', [CompetitionManagementController::class, 'signLineupMatchSheet'])->name('match-sheet.sign-lineup');
            Route::post('/{match}/match-sheet/sign-post-match', [CompetitionManagementController::class, 'signPostMatchSheet'])->name('match-sheet.sign-post-match');
            Route::post('/{match}/match-sheet/assign-referee', [CompetitionManagementController::class, 'assignRefereeMatchSheet'])->name('match-sheet.assign-referee');
            Route::post('/{match}/match-sheet/fa-validate', [CompetitionManagementController::class, 'faValidateMatchSheet'])->name('match-sheet.fa-validate');
            Route::get('/{match}/match-sheet/export', [CompetitionManagementController::class, 'exportMatchSheet'])->name('match-sheet.export');
            Route::get('/{match}/match-sheet/import-players', [CompetitionManagementController::class, 'importPlayersMatchSheet'])->name('match-sheet.import-players');
            Route::post('/{match}/match-sheet/import-players/process', [CompetitionManagementController::class, 'processImportPlayersMatchSheet'])->name('match-sheet.import-players.process');
            Route::get('/{match}/match-sheet/get-club-players', [CompetitionManagementController::class, 'getClubPlayers'])->name('match-sheet.get-club-players');
            

            Route::post('/{match}/match-sheet/upload-signed', [CompetitionManagementController::class, 'uploadSignedMatchSheet'])->name('match-sheet.upload-signed');
            Route::post('/{match}/match-sheet/add-event', [CompetitionManagementController::class, 'addEventMatchSheet'])->name('match-sheet.add-event');
            
            // Export all match sheets for a competition (moved here to match view expectations)
            Route::get('/match-sheet/export-all/{competition}', [CompetitionManagementController::class, 'exportAllMatchSheets'])->name('match-sheet.export-all');
        });
        
        Route::post('/bulk-sync', [CompetitionManagementController::class, 'bulkSync'])->name('bulk-sync');
    });
    
    // Healthcare
    Route::prefix('healthcare')->name('healthcare.')->group(function () {
        Route::get('/', [HealthcareController::class, 'index'])->name('index');
        Route::get('/predictions', [HealthcareController::class, 'predictions'])->name('predictions');
        Route::get('/export', [HealthcareController::class, 'export'])->name('export');
        // Healthcare Records
        Route::get('/records/create', [HealthcareController::class, 'create'])->name('records.create');
        Route::post('/records', [HealthcareController::class, 'store'])->name('records.store');
        Route::get('/records/{record}', [HealthcareController::class, 'show'])->name('records.show')->where('record', '[0-9]+');
        Route::get('/records', [HealthcareController::class, 'index'])->name('records.index');
        Route::get('/records/{record}/edit', [HealthcareController::class, 'edit'])->name('records.edit')->where('record', '[0-9]+');
        Route::put('/records/{record}', [HealthcareController::class, 'update'])->name('records.update')->where('record', '[0-9]+');
        Route::post('/records/{record}/sync', [HealthcareController::class, 'sync'])->name('records.sync')->where('record', '[0-9]+');
        Route::delete('/records/{record}', [HealthcareController::class, 'destroy'])->name('records.destroy')->where('record', '[0-9]+');
        Route::post('/bulk-sync', [HealthcareController::class, 'bulkSync'])->name('bulk-sync');
    });
    
    // Device Connections
    Route::prefix('device-connections')->group(function () {
        Route::get('/', function () {
            return view('device-connections.index');
        })->name('device-connections.index');
        
        Route::get('/catapult', function () {
            return view('device-connections.catapult');
        })->name('catapult-connect.index');
        
        Route::get('/apple-health-kit', function () {
            return view('device-connections.apple-health-kit');
        })->name('apple-health-kit.index');
        
        Route::get('/garmin-connect', function () {
            return view('device-connections.garmin-connect');
        })->name('garmin-connect.index');
        
        // OAuth2 Routes for Device Connections
        Route::prefix('oauth2')->group(function () {
            Route::post('/auth-url', function () {
                return response()->json([
                    'success' => true,
                    'authUrl' => 'https://connect.catapultsports.com/oauth/authorize?client_id=test&redirect_uri=' . url('/device-connections/oauth2/callback')
                ]);
            })->name('device-connections.oauth2.auth-url');
            
            Route::get('/callback', function () {
                return view('device-connections.oauth2-callback');
            })->name('device-connections.oauth2.callback');
            
            Route::get('/tokens', function () {
                return view('device-connections.oauth2.tokens');
            })->name('device-connections.oauth2.tokens');
        });
    });
    
    // FIFA Dashboard
    Route::prefix('fifa-dashboard')->group(function () {
        Route::get('/', function () {
            return view('fifa.dashboard');
        })->name('fifa-dashboard.index');
        
        Route::get('/transfers', function () {
            return view('fifa.transfers');
        })->name('fifa-dashboard.transfers');
        
        Route::get('/contracts', function () {
            return view('fifa.contracts');
        })->name('fifa-dashboard.contracts');
        
        Route::get('/federations', function () {
            return view('fifa.federations');
        })->name('fifa-dashboard.federations');
        
        Route::get('/analytics', function () {
            return view('fifa.analytics');
        })->name('fifa-dashboard.analytics');
        
        Route::get('/reports', function () {
            return view('fifa.reports');
        })->name('fifa-dashboard.reports');
    });
    
    // Back Office
    Route::prefix('back-office')->group(function () {
        Route::get('/dashboard', [BackOfficeController::class, 'dashboard'])->name('back-office.dashboard');
        
        Route::get('/system-status', [BackOfficeController::class, 'systemStatus'])->name('back-office.system-status');
        
        Route::get('/logs', [BackOfficeController::class, 'logs'])->name('back-office.logs');
        Route::get('/logs/download', [BackOfficeController::class, 'downloadLogs'])->name('back-office.logs.download');
        
        Route::get('/settings', [BackOfficeController::class, 'settings'])->name('back-office.settings');
        Route::post('/settings/update', [BackOfficeController::class, 'updateSettings'])->name('back-office.settings.update');
        Route::post('/settings/email', [BackOfficeController::class, 'updateEmailSettings'])->name('back-office.settings.email');
        Route::post('/settings/fifa', [BackOfficeController::class, 'updateFifaSettings'])->name('back-office.settings.fifa');
        Route::post('/settings/security', [BackOfficeController::class, 'updateSecuritySettings'])->name('back-office.settings.security');
        
        Route::post('/clear-cache', [BackOfficeController::class, 'clearCache'])->name('back-office.clear-cache');
        Route::post('/optimize-database', [BackOfficeController::class, 'optimizeDatabase'])->name('back-office.optimize-database');
        Route::post('/backup-system', [BackOfficeController::class, 'backupSystem'])->name('back-office.backup-system');
        Route::post('/toggle-maintenance', [BackOfficeController::class, 'toggleMaintenanceMode'])->name('back-office.toggle-maintenance');
        
        Route::prefix('audit-trail')->group(function () {
            Route::get('/', [AuditTrailController::class, 'index'])->name('back-office.audit-trail.index');
            Route::get('/export', [AuditTrailController::class, 'export'])->name('back-office.audit-trail.export');
            Route::get('/realtime', [AuditTrailController::class, 'realtime'])->name('back-office.audit-trail.realtime');
            Route::get('/{auditTrail}', [AuditTrailController::class, 'show'])->name('back-office.audit-trail.show');
            Route::post('/clear-old-entries', [AuditTrailController::class, 'clearOldEntries'])->name('back-office.audit-trail.clear-old-entries');
        });
        
        Route::prefix('seasons')->group(function () {
            Route::get('/', [SeasonManagementController::class, 'index'])->name('back-office.seasons.index');
            
            Route::get('/create', [SeasonManagementController::class, 'create'])->name('back-office.seasons.create');
            
            Route::post('/store', [SeasonManagementController::class, 'store'])->name('back-office.seasons.store');
            
            Route::get('/{season}', [SeasonManagementController::class, 'show'])->name('back-office.seasons.show');
            
            Route::get('/{season}/edit', [SeasonManagementController::class, 'edit'])->name('back-office.seasons.edit');
            
            Route::put('/{season}', [SeasonManagementController::class, 'update'])->name('back-office.seasons.update');
            
            Route::post('/{season}/set-current', [SeasonManagementController::class, 'setCurrent'])->name('back-office.seasons.set-current');
            
            Route::post('/{season}/activate', [SeasonManagementController::class, 'activate'])->name('back-office.seasons.activate');
            
            Route::post('/{season}/complete', [SeasonManagementController::class, 'complete'])->name('back-office.seasons.complete');
            
            Route::post('/{season}/archive', [SeasonManagementController::class, 'archive'])->name('back-office.seasons.archive');
            
            Route::delete('/{season}', [SeasonManagementController::class, 'destroy'])->name('back-office.seasons.destroy');
        });
        
        Route::prefix('license-types')->group(function () {
            Route::get('/', [LicenseTypeController::class, 'index'])->name('back-office.license-types.index');
            
            Route::get('/create', [LicenseTypeController::class, 'create'])->name('back-office.license-types.create');
            
            Route::post('/store', [LicenseTypeController::class, 'store'])->name('back-office.license-types.store');
            
            Route::get('/{licenseType}', [LicenseTypeController::class, 'show'])->name('back-office.license-types.show');
            
            Route::get('/{licenseType}/edit', [LicenseTypeController::class, 'edit'])->name('back-office.license-types.edit');
            
            Route::put('/{licenseType}', [LicenseTypeController::class, 'update'])->name('back-office.license-types.update');
            
            Route::post('/{licenseType}/toggle-status', [LicenseTypeController::class, 'toggleStatus'])->name('back-office.license-types.toggle-status');
            
            Route::delete('/{licenseType}', [LicenseTypeController::class, 'destroy'])->name('back-office.license-types.destroy');
        });
        
        Route::prefix('content')->group(function () {
            Route::get('/', [ContentManagementController::class, 'index'])->name('back-office.content.index');
            
            Route::get('/create', [ContentManagementController::class, 'create'])->name('back-office.content.create');
            
            Route::post('/store', [ContentManagementController::class, 'store'])->name('back-office.content.store');
            
            Route::get('/{content}', [ContentManagementController::class, 'show'])->name('back-office.content.show');
            
            Route::get('/{content}/edit', [ContentManagementController::class, 'edit'])->name('back-office.content.edit');
            
            Route::put('/{content}', [ContentManagementController::class, 'update'])->name('back-office.content.update');
            
            Route::post('/{content}/toggle-status', [ContentManagementController::class, 'toggleStatus'])->name('back-office.content.toggle-status');
            
            Route::delete('/{content}', [ContentManagementController::class, 'destroy'])->name('back-office.content.destroy');
        });
    });
    
    // User Management
    Route::prefix('user-management')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/dashboard', [UserManagementController::class, 'dashboard'])->name('user-management.dashboard');
        
        
        Route::get('/create', [UserManagementController::class, 'create'])->name('user-management.create');
        
        Route::post('/store', [UserManagementController::class, 'store'])->name('user-management.store');
        
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('user-management.show');
        
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
        
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
        
        Route::post('/bulk-action', [UserManagementController::class, 'bulkAction'])->name('user-management.bulk-action');
        
        Route::get('/export', [UserManagementController::class, 'export'])->name('user-management.export');
    });
    
    // Role Management
    Route::prefix('role-management')->group(function () {
        Route::get('/', [RoleManagementController::class, 'index'])->name('role-management.index');
        
        Route::get('/create', [RoleManagementController::class, 'create'])->name('role-management.create');
        
        Route::post('/store', [RoleManagementController::class, 'store'])->name('role-management.store');
        
        Route::get('/{role}', [RoleManagementController::class, 'show'])->name('role-management.show');
        
        Route::get('/{role}/edit', [RoleManagementController::class, 'edit'])->name('role-management.edit');
        
        Route::put('/{role}', [RoleManagementController::class, 'update'])->name('role-management.update');
        
        Route::delete('/{role}', [RoleManagementController::class, 'destroy'])->name('role-management.destroy');
        
        Route::post('/{role}/duplicate', [RoleManagementController::class, 'duplicate'])->name('role-management.duplicate');
        
        Route::post('/{role}/toggle-status', [RoleManagementController::class, 'toggleStatus'])->name('role-management.toggle-status');
    });
    
    // Module Routes
    Route::prefix('modules')->group(function () {
        // Competitions Module
        Route::get('/competitions', [ModuleController::class, 'competitionsIndex'])->name('competitions.index');
        
        // Healthcare Module
        Route::get('/healthcare', function () {
            return view('modules.healthcare.index');
        })->name('modules.healthcare.index');
        
        // Medical Module
        Route::get('/medical', function () {
            return view('modules.medical.index');
        })->name('modules.medical.index');
        
        // Licenses Module
        Route::get('/licenses', function () {
            return view('modules.licenses.index');
        })->name('modules.licenses.index');
    });
    
    // Admin Routes
    Route::prefix('admin')->group(function () {
        //Route::get('/registration-requests', [App\Http\Controllers\RegistrationRequestController::class, 'index'])->name('registration-requests.index');
    });
});

// Test routes for component testing
Route::get('/test-performance-chart', function () {
    // Validate chart data format if provided
    if (request('chartData')) {
        $chartData = json_decode(request('chartData'), true);
        
        // Check if chartData is a valid array with required keys
        if (!is_array($chartData) || !array_key_exists('labels', $chartData) || !array_key_exists('datasets', $chartData)) {
            return response()->json(['error' => 'Invalid chart data format'], 422);
        }
        // Validate that labels and datasets are arrays (can be empty)
        if (!is_array($chartData['labels']) || !is_array($chartData['datasets'])) {
            return response()->json(['error' => 'Labels and datasets must be arrays'], 422);
        }
        // Validate each dataset (if any)
        foreach ($chartData['datasets'] as $dataset) {
            if (!is_array($dataset) || !array_key_exists('label', $dataset) || !array_key_exists('data', $dataset)) {
                return response()->json(['error' => 'Invalid dataset format'], 422);
            }
            if (!is_array($dataset['data'])) {
                return response()->json(['error' => 'Dataset data must be an array'], 422);
            }
        }
    }
    
    return view('test.performance-chart');
})->name('test.performance-chart');

Route::post('/test-performance-chart-click', function () {
    return response()->json(['success' => true]);
})->name('test.performance-chart-click');

// FIT Dashboard KPIs
Route::get('/api/fit/kpis', [FitDashboardController::class, 'kpis'])->name('fit.kpis');

// Authentication Routes (if not already included)
Route::middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    // Route::get('register', function () {
    //     return view('auth.register');
    // Route::get('forgot-password', function () {
    //     return view('auth.forgot-password');
});

Route::middleware('auth')->group(function () {
    
    // Profile routes
    // Route::get('/profile', function () {
    //     //return view('profile.edit');
    
    // Route::get('/profile/settings', function () {
        //return view('profile.settings');
});
    // FIFA Dashboard Routes (duplicate removed - using the one above)
    // Transfer Documents Routes
    Route::get('/transfer-documents', function () {
        return view('fifa.transfer-documents');
    })->name('transfer-documents.index');
    // Contracts Routes
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
    // Transfers Routes
    Route::get('/transfers/create', function () {
        return view('transfers.create');
    })->name('transfers.create');
    
    Route::get('/transfers/{transfer}', function ($transfer) {
        return view('transfers.show', compact('transfer'));
    })->name('transfers.show');
    
    Route::get('/transfers/{transfer}/edit', function ($transfer) {
        return view('transfers.edit', compact('transfer'));
    })->name('transfers.edit');
    // Federations Routes
    Route::get('/federations/create', function () {
        return view('federations.create');
    })->name('federations.create');
    
    Route::get('/federations/{federation}', function ($federation) {
        return view('federations.show', compact('federation'));
    })->name('federations.show');
    
    Route::get('/federations/{federation}/edit', function ($federation) {
        return view('federations.edit', compact('federation'));
    })->name('federations.edit');

Route::post('notifications/{id}/mark-as-read', function($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return back();
})->name('notifications.markAsRead')->middleware('auth');

// Player Registration Store Route
Route::post('/player-registration', [PlayerRegistrationController::class, 'store'])->name('player-registration.store');
// Player Registration Create Route
Route::get('/player-registration/create', [PlayerRegistrationController::class, 'create'])->name('player-registration.create');

// Player License Review for Association Agents
Route::middleware(['auth', 'role:association_admin,association_registrar,association_medical,system_admin'])
    ->prefix('player-licenses')->name('player-licenses.')->group(function () {
        Route::get('/', [PlayerLicenseReviewController::class, 'index'])->name('index');
        Route::get('/{license}', [PlayerLicenseReviewController::class, 'show'])->name('show');
        Route::post('/{license}/approve', [PlayerLicenseReviewController::class, 'approve'])->name('approve');
        Route::post('/{license}/reject', [PlayerLicenseReviewController::class, 'reject'])->name('reject');
        Route::post('/{license}/request-explanation', [PlayerLicenseReviewController::class, 'requestExplanation'])->name('request-explanation');
        Route::post('/{license}/reanalyze', [PlayerLicenseReviewController::class, 'reanalyze'])->name('reanalyze');
    });

// Club Player License Status
Route::middleware(['auth', 'role:club_admin,club_manager,club_medical,system_admin'])
    ->name('club.player-licenses.')
    ->group(function () {
        Route::get('/club/player-licenses', [ClubPlayerLicenseController::class, 'index'])->name('index');
        Route::get('/club/player-licenses/{license}', [ClubPlayerLicenseController::class, 'show'])->name('show');
    });

Route::prefix('player-licenses/request')->name('player-licenses.request.')->group(function () {
    Route::get('/{player}', [PlayerLicenseController::class, 'requestForm'])->name('request');
    Route::post('/{player}', [PlayerLicenseController::class, 'storeRequest'])->name('store');
    Route::get('/{license}/edit', [PlayerLicenseController::class, 'editRequest'])->name('edit');
    Route::post('/{license}/edit', [PlayerLicenseController::class, 'updateRequest'])->name('update');
});

// Modules DTN et RPM Routes
Route::middleware(['auth'])->group(function () {
    
    // Routes DTN Manager
    Route::prefix('dtn')->name('dtn.')->group(function () {
        Route::get('/', [ModuleController::class, 'dtnDashboard'])->name('dashboard');
        Route::get('/teams', [ModuleController::class, 'dtnTeams'])->name('teams');
        Route::get('/selections', [ModuleController::class, 'dtnSelections'])->name('selections');
        Route::get('/expats', [ModuleController::class, 'dtnExpats'])->name('expats');
        Route::get('/medical', [ModuleController::class, 'dtnMedical'])->name('medical');
        Route::get('/planning', [ModuleController::class, 'dtnPlanning'])->name('planning');
        Route::get('/reports', [ModuleController::class, 'dtnReports'])->name('reports');
        Route::get('/settings', [ModuleController::class, 'dtnSettings'])->name('settings');
    });

    // Routes RPM (Régulation & Préparation Matchs)
    Route::prefix('rpm')->name('rpm.')->group(function () {
        Route::get('/', [ModuleController::class, 'rpmDashboard'])->name('dashboard');
        Route::get('/calendar', [ModuleController::class, 'rpmCalendar'])->name('calendar');
        Route::get('/sessions', [ModuleController::class, 'rpmSessions'])->name('sessions');
        Route::get('/matches', [ModuleController::class, 'rpmMatches'])->name('matches');
        Route::get('/load', [ModuleController::class, 'rpmLoad'])->name('load');
        Route::get('/attendance', [ModuleController::class, 'rpmAttendance'])->name('attendance');
        Route::get('/reports', [ModuleController::class, 'rpmReports'])->name('reports');
        Route::get('/sync', [ModuleController::class, 'rpmSync'])->name('sync');
        Route::get('/settings', [ModuleController::class, 'rpmSettings'])->name('settings');
    });
});
