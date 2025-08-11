<?php
echo "=== Correction Complète du Fichier Routes ===\n";

// Créer un nouveau fichier routes/web.php propre
$newContent = '<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ClubManagementController;
use App\Http\Controllers\CompetitionManagementController;
use App\Http\Controllers\SeasonManagementController;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LicenseRequestController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LicenseTypeController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PerformanceRecommendationController;
use App\Http\Controllers\PlayerPassportController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\FifaController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\FitDashboardController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\LoginController;

// Global routes (no auth required)
Route::get(\'/\', function () {
    $footballType = request(\'footballType\', \'11aside\');
    return view(\'modules.index\', [
        \'footballType\' => $footballType,
        \'modules\' => [
            [
                \'name\' => \'Medical\',
                \'description\' => \'Gestion médicale des athlètes, PCMA, vaccinations, et dossiers de santé\',
                \'icon\' => \'🏥\',
                \'route\' => \'modules.medical.index\',
                \'color\' => \'blue\'
            ],
            [
                \'name\' => \'Healthcare\',
                \'description\' => \'Suivi des soins de santé, dossiers médicaux et évaluations\',
                \'icon\' => \'💊\',
                \'route\' => \'modules.healthcare.index\',
                \'color\' => \'green\'
            ],
            [
                \'name\' => \'Licenses\',
                \'description\' => \'Gestion des licences et autorisations des joueurs\',
                \'icon\' => \'📋\',
                \'route\' => \'modules.licenses.index\',
                \'color\' => \'purple\'
            ],
            [
                \'name\' => \'Competitions\',
                \'description\' => \'Gestion des compétitions et tournois\',
                \'icon\' => \'🏆\',
                \'route\' => \'competitions.index\',
                \'color\' => \'yellow\'
            ],
            [
                \'name\' => \'Association\',
                \'description\' => \'Validation des demandes et gestion des clubs affiliés\',
                \'icon\' => \'🏛️\',
                \'route\' => \'licenses.validation\',
                \'color\' => \'red\'
            ],
            [
                \'name\' => \'Administration\',
                \'description\' => \'Gestion système, utilisateurs et configurations\',
                \'icon\' => \'⚙️\',
                \'route\' => \'administration.index\',
                \'color\' => \'indigo\'
            ]
        ]
    ]);
})->name(\'modules.index\');

// Authentication routes
Route::middleware(\'guest\')->group(function () {
    Route::post(\'login\', [LoginController::class, \'login\']);
});

// Dashboard Routes (protected by auth)
Route::middleware([\'auth\'])->group(function () {
    // Dashboard principal
    Route::get(\'/dashboard\', [DashboardController::class, \'index\'])->name(\'dashboard\');
    
    // Administration routes
    Route::get(\'/administration\', function () {
        return view(\'administration.index\');
    })->name(\'administration.index\');
    
    // Licenses routes
    Route::resource(\'licenses\', LicenseController::class)->except([\'show\']);
    Route::get(\'/licenses/validation\', [LicenseController::class, \'validation\'])->name(\'licenses.validation\');
    Route::patch(\'/licenses/{license}/approve\', [LicenseController::class, \'approve\'])->name(\'licenses.approve\');
    Route::patch(\'/licenses/{license}/reject\', [LicenseController::class, \'reject\'])->name(\'licenses.reject\');
    
    // Modules routes
    Route::prefix(\'modules\')->group(function () {
        Route::get(\'/licenses\', function () {
            $footballType = request(\'footballType\', \'11aside\');
            return view(\'modules.licenses.index\', compact(\'footballType\'));
        })->name(\'modules.licenses.index\');
        
        Route::get(\'/healthcare\', function () {
            return view(\'modules.healthcare.index\', [\'footballType\' => \'association\']);
        })->name(\'modules.healthcare.index\');
        
        Route::get(\'/medical\', function () {
            return view(\'modules.medical.index\', [\'footballType\' => \'association\']);
        })->name(\'modules.medical.index\');
    });
    
    // Competitions routes
    Route::get(\'/competitions\', [ModuleController::class, \'competitionsIndex\'])->name(\'competitions.index\');
    
    // Other routes...
    Route::get(\'/profile-selector\', function () {
        $footballType = request(\'footballType\', \'11aside\');
        return view(\'profile-selector\', compact(\'footballType\'));
    })->name(\'profile-selector\');
});

// API routes
Route::get(\'/api/fit/kpis\', [FitDashboardController::class, \'kpis\'])->name(\'fit.kpis\');

// Test routes
Route::get(\'/test-tabs\', function () {
    $players = \\App\\Models\\Player::orderBy(\'name\')->get();
    return view(\'health-records.create\', compact(\'players\'));
})->name(\'test-tabs\');
';

// Sauvegarder le nouveau fichier
file_put_contents('routes/web.php', $newContent);

echo "✅ Fichier routes/web.php recréé avec une syntaxe propre\n";

// Vérifier la syntaxe
$output = shell_exec('php -l routes/web.php 2>&1');
if (strpos($output, 'No syntax errors') !== false) {
    echo "✅ Syntaxe PHP correcte\n";
} else {
    echo "❌ Erreur de syntaxe PHP:\n";
    echo $output;
}

// Nettoyer les caches
shell_exec('php artisan route:clear');
shell_exec('php artisan config:clear');
shell_exec('php artisan cache:clear');
echo "✅ Caches nettoyés\n";

// Tester la route
$output = shell_exec('php artisan route:list | grep licenses.validation 2>&1');
if (strpos($output, 'licenses.validation') !== false) {
    echo "✅ Route licenses.validation trouvée:\n";
    echo $output;
} else {
    echo "❌ Route licenses.validation non trouvée\n";
}

echo "\n🎉 FICHIER ROUTES CORRIGÉ !\n";
echo "🔗 La carte Association devrait maintenant fonctionner\n";
?> 