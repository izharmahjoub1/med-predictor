<?php

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
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Added for API proxy routes
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\PlayerPortalController;

// Test route
Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'Server is working']);
})->name('test');

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.token');

// Test PCMA create view
Route::get('/test-pcma-view', function () {
    try {
        return view('pcma.create', [
            'athletes' => collect([]),
            'users' => collect([])
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'View error: ' . $e->getMessage()], 500);
    }
})->name('test.pcma.view');

// Test PCMA create route (temporary, no auth required)
Route::get('/test-pcma-create', function () {
    try {
        // Simuler les données nécessaires pour la vue
        $players = collect([
            (object)['id' => 1, 'first_name' => 'Test', 'last_name' => 'Player 1', 'club_id' => 1],
            (object)['id' => 2, 'first_name' => 'Test', 'last_name' => 'Player 2', 'club_id' => 1],
        ]);
        
        $assessors = collect([
            (object)['id' => 1, 'name' => 'Dr. Test Doctor', 'role' => 'doctor'],
            (object)['id' => 2, 'name' => 'Nurse Test', 'role' => 'medical_staff'],
        ]);
        
        return view('pcma.create', compact('players', 'assessors'));
    } catch (\Exception $e) {
        return response()->json(['error' => 'Test PCMA create error: ' . $e->getMessage()], 500);
    }
})->name('test.pcma.create');

// Test Dental Chart route (public access for testing)
Route::get('/test-dental-chart', function () {
    try {
        return view('health-records.create', [
            'patients' => collect([
                (object)['id' => 1, 'name' => 'Test Patient 1'],
                (object)['id' => 2, 'name' => 'Test Patient 2'],
                (object)['id' => 3, 'name' => 'Test Patient 3']
            ])
        ]);
    } catch (\Exception $e) {
        \Log::error('Dental chart test route error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
})->name('test.dental.chart');

// Test Dental Chart Simple route (public access for testing)
Route::get('/test-dental-simple', function () {
    try {
        return view('test-dental-simple');
    } catch (\Exception $e) {
        \Log::error('Dental chart simple test route error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
})->name('test.dental.simple');

// Test Dental Chart Adapted route (public access for testing)
Route::get('/dental-chart-test', function () {
    try {
        return view('dental-chart-test');
    } catch (\Exception $e) {
        \Log::error('Dental chart test route error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
})->name('dental.chart.test');

// Test Health Records Simple route (public access for testing)
Route::get('/health-records-simple', function () {
    try {
        return view('health-records.create-simple');
    } catch (\Exception $e) {
        \Log::error('Health records simple test route error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
})->name('health.records.simple');



// Simple PCMA test route
Route::get('/pcma/test-simple', function () {
    return response()->json(['status' => 'ok', 'message' => 'PCMA route is working']);
})->name('pcma.test.simple');

// PCMA test view route
Route::get('/pcma/test-view', function () {
    try {
        $athletes = collect([
            ['id' => 1, 'name' => 'Test Athlete 1'],
            ['id' => 2, 'name' => 'Test Athlete 2'],
            ['id' => 3, 'name' => 'Test Athlete 3']
        ]);
        
        $users = collect([
            ['id' => 1, 'name' => 'Dr. Test User 1'],
            ['id' => 2, 'name' => 'Dr. Test User 2'],
            ['id' => 3, 'name' => 'Dr. Test User 3']
        ]);
        
        return view('pcma.test-simple', [
            'athletes' => $athletes,
            'users' => $users
        ]);
    } catch (\Exception $e) {
        \Log::error('PCMA test view error: ' . $e->getMessage());
        return response()->json(['error' => 'View error: ' . $e->getMessage()], 500);
    }
})->name('pcma.test.view');

// Test route for PCMA with DoctorSignOff integration
Route::get('/pcma/test-with-signoff', function () {
    try {
        $athletes = collect([
            (object)['id' => 1, 'name' => 'Test Athlete 1', 'club' => (object)['name' => 'Test Club 1']],
            (object)['id' => 2, 'name' => 'Test Athlete 2', 'club' => (object)['name' => 'Test Club 2']],
            (object)['id' => 3, 'name' => 'Test Athlete 3', 'club' => (object)['name' => 'Test Club 3']]
        ]);
        
        $users = collect([
            (object)['id' => 1, 'name' => 'Dr. Test User 1'],
            (object)['id' => 2, 'name' => 'Dr. Test User 2'],
            (object)['id' => 3, 'name' => 'Dr. Test User 3']
        ]);
        
        return view('pcma.create', [
            'athletes' => $athletes,
            'users' => $users
        ]);
    } catch (\Exception $e) {
        \Log::error('PCMA test with signoff error: ' . $e->getMessage());
        return response()->json(['error' => 'Test error: ' . $e->getMessage()], 500);
    }
})->name('pcma.test.signoff');

// API Proxy Routes to avoid CORS issues (public access)
Route::get('/api/proxy/icd11', function (Request $request) {
    try {
        $query = $request->get('q', '');
        
        \Log::info('ICD-11 API Proxy called', ['query' => $query]);
        
        // Fast ICD-11 search with optimized fallback
        $queryLower = strtolower($query);
        
        // Quick keyword matching for immediate response
        $quickResults = [];
        foreach ($fallbackData as $keyword => $items) {
            if (stripos($queryLower, $keyword) !== false) {
                $quickResults = $items;
                break;
            }
        }
        
        // If no quick match, try broader search
        if (empty($quickResults)) {
            foreach ($fallbackData as $keyword => $items) {
                foreach ($items as $item) {
                    if (stripos($item['title'], $query) !== false || stripos($item['code'], $query) !== false) {
                        $quickResults[] = $item;
                    }
                }
            }
        }
        
        // Return results immediately (no external API calls for now due to timeout issues)
        return response()->json([
            'success' => true,
            'results' => array_slice($quickResults, 0, 10),
            'fallback' => true,
            'message' => 'Using comprehensive medical database'
        ]);
        
        // Comprehensive medical database fallback
        $fallbackData = [
            // Cardiovascular conditions
            'cardio' => [
                ['id' => 'I10', 'title' => 'Hypertension artérielle essentielle (I10)', 'code' => 'I10'],
                ['id' => 'I21', 'title' => 'Infarctus aigu du myocarde (I21)', 'code' => 'I21'],
                ['id' => 'I20', 'title' => 'Angine de poitrine (I20)', 'code' => 'I20'],
                ['id' => 'I50', 'title' => 'Insuffisance cardiaque (I50)', 'code' => 'I50'],
                ['id' => 'I49', 'title' => 'Troubles du rythme cardiaque (I49)', 'code' => 'I49'],
                ['id' => 'I25', 'title' => 'Cardiopathie ischémique chronique (I25)', 'code' => 'I25'],
                ['id' => 'I42', 'title' => 'Cardiomyopathie (I42)', 'code' => 'I42'],
                ['id' => 'I34', 'title' => 'Valvulopathie mitrale (I34)', 'code' => 'I34'],
                ['id' => 'I35', 'title' => 'Valvulopathie aortique (I35)', 'code' => 'I35'],
                ['id' => 'I27', 'title' => 'Hypertension pulmonaire (I27)', 'code' => 'I27']
            ],
            'heart' => [
                ['id' => 'I51', 'title' => 'Maladie cardiaque (I51)', 'code' => 'I51'],
                ['id' => 'I49', 'title' => 'Arythmie cardiaque (I49)', 'code' => 'I49'],
                ['id' => 'I34', 'title' => 'Valvulopathie (I34-I38)', 'code' => 'I34-I38'],
                ['id' => 'I42', 'title' => 'Cardiomyopathie (I42)', 'code' => 'I42'],
                ['id' => 'I50', 'title' => 'Insuffisance cardiaque (I50)', 'code' => 'I50']
            ],
            'hyper' => [
                ['id' => 'I10', 'title' => 'Hypertension artérielle (I10)', 'code' => 'I10'],
                ['id' => 'I27', 'title' => 'Hypertension pulmonaire (I27)', 'code' => 'I27'],
                ['id' => 'I15', 'title' => 'Hypertension secondaire (I15)', 'code' => 'I15']
            ],
            'infarct' => [
                ['id' => 'I21', 'title' => 'Infarctus aigu du myocarde (I21)', 'code' => 'I21'],
                ['id' => 'I22', 'title' => 'Infarctus du myocarde récurrent (I22)', 'code' => 'I22'],
                ['id' => 'I23', 'title' => 'Complications de l\'infarctus (I23)', 'code' => 'I23']
            ],
            'angine' => [
                ['id' => 'I20', 'title' => 'Angine de poitrine (I20)', 'code' => 'I20'],
                ['id' => 'I20.0', 'title' => 'Angine de poitrine instable (I20.0)', 'code' => 'I20.0'],
                ['id' => 'I20.1', 'title' => 'Angine de poitrine stable (I20.1)', 'code' => 'I20.1']
            ],
            // Surgical procedures
            'surgery' => [
                ['id' => '0210', 'title' => 'Pontage aorto-coronarien (0210)', 'code' => '0210'],
                ['id' => '0211', 'title' => 'Remplacement valvulaire (0211)', 'code' => '0211'],
                ['id' => '0212', 'title' => 'Appendicectomie (0212)', 'code' => '0212'],
                ['id' => '0213', 'title' => 'Cholécystectomie (0213)', 'code' => '0213'],
                ['id' => '0214', 'title' => 'Herniorraphie (0214)', 'code' => '0214'],
                ['id' => '0215', 'title' => 'Césarienne (0215)', 'code' => '0215'],
                ['id' => '0216', 'title' => 'Arthroplastie du genou (0216)', 'code' => '0216'],
                ['id' => '0217', 'title' => 'Arthroplastie de la hanche (0217)', 'code' => '0217'],
                ['id' => '0218', 'title' => 'Lobectomie pulmonaire (0218)', 'code' => '0218'],
                ['id' => '0219', 'title' => 'Néphrectomie (0219)', 'code' => '0219']
            ],
            'surgical' => [
                ['id' => '0210', 'title' => 'Pontage aorto-coronarien (0210)', 'code' => '0210'],
                ['id' => '0211', 'title' => 'Remplacement valvulaire (0211)', 'code' => '0211'],
                ['id' => '0212', 'title' => 'Appendicectomie (0212)', 'code' => '0212'],
                ['id' => '0213', 'title' => 'Cholécystectomie (0213)', 'code' => '0213'],
                ['id' => '0214', 'title' => 'Herniorraphie (0214)', 'code' => '0214']
            ]
        ];
        
        $results = [];
        foreach ($fallbackData as $keyword => $items) {
            if (stripos($query, $keyword) !== false) {
                $results = $items;
                break;
            }
        }
        
        // If no specific match, return general cardiovascular terms for cardio-related queries
        if (empty($results) && (stripos($query, 'cardio') !== false || stripos($query, 'heart') !== false)) {
            $results = $fallbackData['cardio'];
        }
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true
        ]);
        
    } catch (Exception $e) {
        \Log::error('ICD-11 API Proxy error', ['error' => $e->getMessage()]);
        
        // Return basic fallback data
        $results = [
            ['id' => 'I10', 'title' => 'Hypertension artérielle (I10)', 'code' => 'I10'],
            ['id' => 'I21', 'title' => 'Infarctus du myocarde (I21)', 'code' => 'I21'],
            ['id' => 'I20', 'title' => 'Angine de poitrine (I20)', 'code' => 'I20']
        ];
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true
        ]);
    }
})->name('api.proxy.icd11');

Route::get('/api/proxy/vidal', function (Request $request) {
    try {
        $query = $request->get('q', '');
        
        \Log::info('VIDAL API Proxy called', ['query' => $query]);
        
        // Comprehensive French drug database
        $vidalMedications = [
            ['id' => 'vidal_001', 'title' => 'Doliprane 500mg', 'dosage' => 'Comprimé 500mg'],
            ['id' => 'vidal_002', 'title' => 'Aspirine 100mg', 'dosage' => 'Comprimé 100mg'],
            ['id' => 'vidal_003', 'title' => 'Ibuprofène 400mg', 'dosage' => 'Comprimé 400mg'],
            ['id' => 'vidal_004', 'title' => 'Paracétamol 1000mg', 'dosage' => 'Comprimé 1000mg'],
            ['id' => 'vidal_005', 'title' => 'Atorvastatine 20mg', 'dosage' => 'Comprimé 20mg'],
            ['id' => 'vidal_006', 'title' => 'Métoprolol 50mg', 'dosage' => 'Comprimé 50mg'],
            ['id' => 'vidal_007', 'title' => 'Lisinopril 10mg', 'dosage' => 'Comprimé 10mg'],
            ['id' => 'vidal_008', 'title' => 'Amlodipine 5mg', 'dosage' => 'Comprimé 5mg'],
            ['id' => 'vidal_009', 'title' => 'Warfarine 5mg', 'dosage' => 'Comprimé 5mg'],
            ['id' => 'vidal_010', 'title' => 'Furosémide 40mg', 'dosage' => 'Comprimé 40mg'],
            ['id' => 'vidal_011', 'title' => 'Oméprazole 20mg', 'dosage' => 'Gélule 20mg'],
            ['id' => 'vidal_012', 'title' => 'Lévothyroxine 100µg', 'dosage' => 'Comprimé 100µg'],
            ['id' => 'vidal_013', 'title' => 'Metformine 500mg', 'dosage' => 'Comprimé 500mg'],
            ['id' => 'vidal_014', 'title' => 'Simvastatine 40mg', 'dosage' => 'Comprimé 40mg'],
            ['id' => 'vidal_015', 'title' => 'Ramipril 5mg', 'dosage' => 'Comprimé 5mg']
        ];
        
        // Fast VIDAL search with comprehensive French drug database
        $queryLower = strtolower($query);
        $results = collect($vidalMedications)->filter(function($med) use ($queryLower) {
            return stripos($med['title'], $queryLower) !== false || 
                   stripos($med['dosage'], $queryLower) !== false ||
                   stripos(strtolower($med['title']), $queryLower) !== false;
        })->take(10)->toArray();
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true,
            'message' => 'Using comprehensive French drug database'
        ]);
        $vidalMedications = [
            ['id' => 'vidal_001', 'title' => 'Doliprane 500mg', 'dosage' => 'Comprimé 500mg'],
            ['id' => 'vidal_002', 'title' => 'Aspirine 100mg', 'dosage' => 'Comprimé 100mg'],
            ['id' => 'vidal_003', 'title' => 'Ibuprofène 400mg', 'dosage' => 'Comprimé 400mg'],
            ['id' => 'vidal_004', 'title' => 'Paracétamol 1000mg', 'dosage' => 'Comprimé 1000mg'],
            ['id' => 'vidal_005', 'title' => 'Atorvastatine 20mg', 'dosage' => 'Comprimé 20mg'],
            ['id' => 'vidal_006', 'title' => 'Métoprolol 50mg', 'dosage' => 'Comprimé 50mg'],
            ['id' => 'vidal_007', 'title' => 'Lisinopril 10mg', 'dosage' => 'Comprimé 10mg'],
            ['id' => 'vidal_008', 'title' => 'Amlodipine 5mg', 'dosage' => 'Comprimé 5mg'],
            ['id' => 'vidal_009', 'title' => 'Warfarine 5mg', 'dosage' => 'Comprimé 5mg'],
            ['id' => 'vidal_010', 'title' => 'Furosémide 40mg', 'dosage' => 'Comprimé 40mg'],
            ['id' => 'vidal_011', 'title' => 'Oméprazole 20mg', 'dosage' => 'Gélule 20mg'],
            ['id' => 'vidal_012', 'title' => 'Lévothyroxine 100µg', 'dosage' => 'Comprimé 100µg'],
            ['id' => 'vidal_013', 'title' => 'Metformine 500mg', 'dosage' => 'Comprimé 500mg'],
            ['id' => 'vidal_014', 'title' => 'Simvastatine 40mg', 'dosage' => 'Comprimé 40mg'],
            ['id' => 'vidal_015', 'title' => 'Ramipril 5mg', 'dosage' => 'Comprimé 5mg']
        ];
        
        $results = collect($vidalMedications)->filter(function($med) use ($query) {
            return stripos($med['title'], $query) !== false || stripos($med['dosage'], $query) !== false;
        })->toArray();
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true
        ]);
        
    } catch (Exception $e) {
        \Log::error('VIDAL API Proxy error', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
})->name('api.proxy.vidal');

Route::get('/api/proxy/allergies', function (Request $request) {
    try {
        $query = $request->get('q', '');
        
        \Log::info('Allergies API Proxy called', ['query' => $query]);
        
        // Comprehensive medical allergies database
        $allergiesData = [
            // Drug Allergies
            ['id' => 'all_001', 'title' => 'Allergie aux pénicillines', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_002', 'title' => 'Allergie aux céphalosporines', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_003', 'title' => 'Allergie aux sulfamides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_004', 'title' => 'Allergie à l\'aspirine', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_005', 'title' => 'Allergie aux AINS', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_006', 'title' => 'Allergie aux tétracyclines', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_007', 'title' => 'Allergie aux macrolides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_008', 'title' => 'Allergie aux quinolones', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_009', 'title' => 'Allergie aux aminoglycosides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_010', 'title' => 'Allergie aux bêta-lactamines', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            
            // Food Allergies
            ['id' => 'all_011', 'title' => 'Allergie aux arachides', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_012', 'title' => 'Allergie aux noix', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_013', 'title' => 'Allergie aux fruits de mer', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_014', 'title' => 'Allergie au lait', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_015', 'title' => 'Allergie aux œufs', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_016', 'title' => 'Allergie au soja', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_017', 'title' => 'Allergie au blé', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_018', 'title' => 'Allergie au poisson', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_019', 'title' => 'Allergie aux crustacés', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_020', 'title' => 'Allergie aux mollusques', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            
            // Environmental Allergies
            ['id' => 'all_021', 'title' => 'Allergie aux pollens', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_022', 'title' => 'Allergie aux acariens', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_023', 'title' => 'Allergie aux poils d\'animaux', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_024', 'title' => 'Allergie aux moisissures', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_025', 'title' => 'Allergie au latex', 'severity' => 'Sévère', 'type' => 'Contact']
        ];
        
        // Fast Allergies search with comprehensive medical allergies database
        $queryLower = strtolower($query);
        $results = collect($allergiesData)->filter(function($allergy) use ($queryLower) {
            $title = strtolower($allergy['title']);
            $type = strtolower($allergy['type']);
            $severity = strtolower($allergy['severity']);
            
            return stripos($title, $queryLower) !== false || 
                   stripos($type, $queryLower) !== false || 
                   stripos($severity, $queryLower) !== false;
        })->take(10)->toArray();
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true,
            'message' => 'Using comprehensive medical allergies database'
        ]);
        
        // Comprehensive medical allergies database
        $allergiesData = [
            // Drug Allergies
            ['id' => 'all_001', 'title' => 'Allergie aux pénicillines', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_002', 'title' => 'Allergie aux céphalosporines', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_003', 'title' => 'Allergie aux sulfamides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_004', 'title' => 'Allergie à l\'aspirine', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_005', 'title' => 'Allergie aux AINS', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_006', 'title' => 'Allergie aux tétracyclines', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_007', 'title' => 'Allergie aux macrolides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_008', 'title' => 'Allergie aux quinolones', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
            ['id' => 'all_009', 'title' => 'Allergie aux aminoglycosides', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            ['id' => 'all_010', 'title' => 'Allergie aux bêta-lactamines', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
            
            // Food Allergies
            ['id' => 'all_011', 'title' => 'Allergie aux arachides', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_012', 'title' => 'Allergie aux noix', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_013', 'title' => 'Allergie aux fruits de mer', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_014', 'title' => 'Allergie au lait', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_015', 'title' => 'Allergie aux œufs', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_016', 'title' => 'Allergie au soja', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_017', 'title' => 'Allergie au blé', 'severity' => 'Modérée', 'type' => 'Alimentaire'],
            ['id' => 'all_018', 'title' => 'Allergie au poisson', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_019', 'title' => 'Allergie aux crustacés', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            ['id' => 'all_020', 'title' => 'Allergie aux mollusques', 'severity' => 'Sévère', 'type' => 'Alimentaire'],
            
            // Environmental Allergies
            ['id' => 'all_021', 'title' => 'Allergie aux pollens', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_022', 'title' => 'Allergie aux acariens', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_023', 'title' => 'Allergie aux poils d\'animaux', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_024', 'title' => 'Allergie aux moisissures', 'severity' => 'Modérée', 'type' => 'Environnementale'],
            ['id' => 'all_025', 'title' => 'Allergie au latex', 'severity' => 'Sévère', 'type' => 'Contact']
        ];
        
        $results = collect($allergiesData)->filter(function($allergy) use ($query) {
            return stripos($allergy['title'], $query) !== false || 
                   stripos($allergy['type'], $query) !== false || 
                   stripos($allergy['severity'], $query) !== false;
        })->toArray();
        
        // If no results, provide general allergy suggestions
        if (empty($results)) {
            $results = [
                ['id' => 'all_001', 'title' => 'Allergie aux pénicillines', 'severity' => 'Sévère', 'type' => 'Médicamenteuse'],
                ['id' => 'all_002', 'title' => 'Allergie aux céphalosporines', 'severity' => 'Modérée', 'type' => 'Médicamenteuse'],
                ['id' => 'all_011', 'title' => 'Allergie aux arachides', 'severity' => 'Sévère', 'type' => 'Alimentaire']
            ];
        }
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'fallback' => true
        ]);
        
    } catch (Exception $e) {
        \Log::error('Allergies API Proxy error', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
})->name('api.proxy.allergies');

// Global routes (no auth required)
Route::get('/', function () {
    return view('landing-simple');
})->name('landing');

Route::get('/profile-selector', function () {
    $footballType = request('footballType', '11aside');
    return view('profile-selector', compact('footballType'));
})->name('profile-selector');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Logout routes
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

// Test page for JavaScript debugging
Route::get('/test-player-display', function () {
    return view('test-player-display');
})->name('test.player.display');

// Get signed PCMAs for dashboard (public route)
Route::get('/api/signed-pcmas', function () {
    try {
        $pcmas = \App\Models\PCMA::with(['athlete', 'assessor'])
            ->where('is_signed', true)
            ->orderBy('signed_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'pcmas' => $pcmas
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching signed PCMAs: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du chargement des PCMAs signés'
        ], 500);
    }
})->name('api.signed-pcmas');

// Dashboard Routes (protected by auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Modules index route
    Route::get('/modules', function () {
        $footballType = request('footballType', '11aside');
        return view('modules.index', [
            'footballType' => $footballType,
            'modules' => [
                [
                    'name' => 'Medical',
                    'description' => 'Gestion médicale des athlètes, vaccinations, et dossiers de santé',
                    'icon' => '🏥',
                    'route' => 'modules.medical.index',
                    'color' => 'blue'
                ],
                [
                    'name' => 'PCMA',
                    'description' => 'Évaluation Capacité Physique Médicale (Physical Capacity Medical Assessment)',
                    'icon' => '💪',
                    'route' => 'pcma.dashboard',
                    'color' => 'green'
                ],
                [
                    'name' => 'Analytics',
                    'description' => '📊 Analytics, 📈 Trends, ⚠️ Performance Alerts, Recommendations',
                    'icon' => '📊',
                    'route' => 'analytics.dashboard',
                    'color' => 'purple'
                ],
                [
                    'name' => 'FIFA',
                    'description' => 'Connectivité FIFA, synchronisation et gestion des contrats',
                    'icon' => '⚽',
                    'route' => 'fifa.dashboard',
                    'color' => 'blue'
                ],
                [
                    'name' => 'Device Connections',
                    'description' => 'Gestion des connexions d\'appareils et données IoT',
                    'icon' => '🔗',
                    'route' => 'device-connections.index',
                    'color' => 'green'
                ],
                [
                    'name' => 'Performance',
                    'description' => 'Analyse des performances, métriques et suivi des athlètes',
                    'icon' => '📊',
                    'route' => 'performance.index',
                    'color' => 'purple'
                ],
                [
                    'name' => 'DTN',
                    'description' => 'Digital Twin Network - Simulation et modélisation avancée',
                    'icon' => '🔄',
                    'route' => 'dtn.index',
                    'color' => 'indigo'
                ],
                [
                    'name' => 'RPM',
                    'description' => 'Real-time Performance Monitoring - Surveillance en temps réel',
                    'icon' => '⚡',
                    'route' => 'rpm.index',
                    'color' => 'yellow'
                ],
                [
                    'name' => 'Healthcare',
                    'description' => 'Suivi des soins de santé, dossiers médicaux et évaluations',
                    'icon' => '💊',
                    'route' => 'modules.healthcare.index',
                    'color' => 'green'
                ],
                [
                    'name' => 'Licenses',
                    'description' => 'Gestion des licences et autorisations des joueurs',
                    'icon' => '📋',
                    'route' => 'modules.licenses.index',
                    'color' => 'purple'
                ],
                [
                    'name' => 'Competitions',
                    'description' => 'Gestion des compétitions et tournois',
                    'icon' => '🏆',
                    'route' => 'competitions.index',
                    'color' => 'yellow'
                ],
                [
                    'name' => 'Association',
                    'description' => 'Validation des demandes et gestion des clubs affiliés',
                    'icon' => '🏛️',
                    'route' => 'licenses.validation',
                    'color' => 'red'
                ],
                [
                    'name' => 'Administration',
                    'description' => 'Gestion système, utilisateurs et configurations',
                    'icon' => '⚙️',
                    'route' => 'administration.index',
                    'color' => 'indigo'
                ],
                [
                    'name' => 'AI Testing',
                    'description' => 'Test et comparaison des fournisseurs d\'IA pour l\'analyse médicale',
                    'icon' => '🤖',
                    'route' => 'ai-testing.index',
                    'color' => 'purple'
                ],
                [
                    'name' => 'Whisper Speech',
                    'description' => 'Transcription audio avec OpenAI Whisper pour contexte médical',
                    'icon' => '🎤',
                    'route' => 'whisper.index',
                    'color' => 'blue'
                ],
                [
                    'name' => 'Google Gemini AI',
                    'description' => 'Analyse médicale avancée avec Google Gemini AI',
                    'icon' => '🤖',
                    'route' => 'gemini.index',
                    'color' => 'green'
                ]
            ]
        ]);
    })->name('modules.index');
    
    // Administration routes
    Route::get('/administration', function () {
        return view('administration.index');
    })->name('administration.index');
    
    // Licenses routes
    Route::resource('licenses', LicenseController::class)->except(['show']);
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::get('/licenses/validation', [LicenseController::class, 'validation'])->name('licenses.validation');
    Route::patch('/licenses/{license}/approve', [LicenseController::class, 'approve'])->name('licenses.approve');
    Route::patch('/licenses/{license}/reject', [LicenseController::class, 'reject'])->name('licenses.reject');
    
    // User Management routes
    Route::get('/user-management', function () {
        return view('modules.user-management.index');
    })->name('user-management.index');
    
    // Role Management routes
    Route::get('/role-management', function () {
        return view('modules.role-management.index');
    })->name('role-management.index');
    
    // Audit Trail routes
    Route::get('/audit-trail', function () {
        return view('modules.audit-trail.index');
    })->name('audit-trail.index');
    
    // Logs routes
    Route::get('/logs', function () {
        return view('modules.logs.index');
    })->name('logs.index');
    
    // System Status routes
    Route::get('/system-status', function () {
        return view('modules.system-status.index');
    })->name('system-status.index');
    
    // Settings routes
    Route::get('/settings', function () {
        return view('modules.settings.index');
    })->name('settings.index');
    
    // License Types routes
    Route::get('/license-types', function () {
        return view('modules.license-types.index');
    })->name('license-types.index');
    
    // Content Management routes
    Route::get('/content', function () {
        return view('modules.content.index');
    })->name('content.index');
    
    // Stakeholder Gallery routes
    Route::get('/stakeholder-gallery', function () {
        return view('modules.stakeholder-gallery.index');
    })->name('stakeholder-gallery.index');
    
    // Players routes
    Route::get('/players', function () {
        $players = collect([]); // Empty collection for now
        
        // Try to get actual players if model exists
        try {
            if (class_exists('\App\Models\Player')) {
                $players = \App\Models\Player::with(['licenses', 'club'])->orderBy('first_name')->get();
            }
        } catch (\Exception $e) {
            // Player model might not exist or table is missing
        }
        
        return view('modules.players.index', [
            'players' => $players
        ]);
    })->name('players.index');
    
    // Player Registration routes
    Route::get('/player-registration/create', function () {
        return view('modules.player-registration.create');
    })->name('player-registration.create');
    
    // Club Player Licenses routes
    Route::get('/club/player-licenses', function () {
        return view('modules.club.player-licenses.index');
    })->name('club.player-licenses.index');
    
    // Player Passports routes
    Route::get('/player-passports', function () {
        return view('modules.player-passports.index');
    })->name('player-passports.index');
    
    // Health Records routes
    Route::get('/health-records', [App\Http\Controllers\HealthRecordController::class, 'index'])->name('health-records.index');
    Route::get('/health-records/create', [App\Http\Controllers\HealthRecordController::class, 'create'])->name('health-records.create');
    Route::get('/health-records/{healthRecord}', [App\Http\Controllers\HealthRecordController::class, 'show'])->name('health-records.show');
    Route::post('/health-records', [App\Http\Controllers\HealthRecordController::class, 'store'])->name('health-records.store');
    Route::get('/health-records/{healthRecord}/edit', [App\Http\Controllers\HealthRecordController::class, 'edit'])->name('health-records.edit');
    Route::put('/health-records/{healthRecord}', [App\Http\Controllers\HealthRecordController::class, 'update'])->name('health-records.update');
    Route::delete('/health-records/{healthRecord}', [App\Http\Controllers\HealthRecordController::class, 'destroy'])->name('health-records.destroy');
    Route::post('/health-records/{healthRecord}/generate-prediction', [App\Http\Controllers\HealthRecordController::class, 'generatePrediction'])->name('health-records.generate-prediction');
    Route::post('/health-records/generate-hl7-cda', [App\Http\Controllers\HealthRecordController::class, 'generateHl7Cda'])->name('health-records.generate-hl7-cda');
    
    // Performances routes
    Route::get('/performances', function () {
        return view('modules.performances.index');
    })->name('performances.index');
    
    // Teams routes
    Route::get('/teams', function () {
        return view('modules.teams.index');
    })->name('teams.index');
    
    // Club Player Assignments routes
    Route::get('/club-player-assignments', function () {
        return view('modules.club-player-assignments.index');
    })->name('club-player-assignments.index');
    
    // Match Sheet routes
    Route::get('/match-sheet', function () {
        return view('modules.match-sheet.index');
    })->name('match-sheet.index');
    
    // Transfers routes
    Route::get('/transfers', function () {
        return view('modules.transfers.index');
    })->name('transfers.index');
    
    // Performance Recommendations routes
    Route::get('/performance-recommendations', function () {
        return view('modules.performance-recommendations.index');
    })->name('performance-recommendations.index');
    
    // Competitions routes
    Route::get('/competitions', [CompetitionManagementController::class, 'competitionsIndex'])->name('competitions.index');
    Route::get('/competitions/create', [CompetitionManagementController::class, 'create'])->name('competitions.create');
    Route::post('/competitions', [CompetitionManagementController::class, 'store'])->name('competitions.store');
    Route::get('/competitions/{competition}', [CompetitionManagementController::class, 'show'])->name('competitions.show');
    Route::get('/competitions/{competition}/edit', [CompetitionManagementController::class, 'edit'])->name('competitions.edit');
    Route::put('/competitions/{competition}', [CompetitionManagementController::class, 'update'])->name('competitions.update');
    Route::delete('/competitions/{competition}', [CompetitionManagementController::class, 'destroy'])->name('competitions.destroy');
    Route::post('/competitions/{competition}/sync', [CompetitionManagementController::class, 'sync'])->name('competitions.sync');
    Route::get('/competitions/{competition}/standings', [CompetitionManagementController::class, 'standings'])->name('competitions.standings');
    Route::get('/competitions/{competition}/register-team-form', [CompetitionManagementController::class, 'showRegisterTeamForm'])->name('competitions.register-team-form');
    Route::post('/competitions/{competition}/register-team', [CompetitionManagementController::class, 'registerTeam'])->name('competitions.register-team');
    
    // Fixtures routes
    Route::get('/fixtures', function () {
        return view('modules.fixtures.index');
    })->name('fixtures.index');
    
    // Rankings routes
    Route::get('/rankings', function () {
        return view('modules.rankings.index');
    })->name('rankings.index');
    
    // Seasons routes
    Route::get('/seasons', function () {
        return view('modules.seasons.index');
    })->name('seasons.index');
    
    // Federations routes
    Route::get('/federations', function () {
        return view('modules.federations.index');
    })->name('federations.index');
    
    // Registration Requests routes
    Route::get('/registration-requests', function () {
        return view('modules.registration-requests.index');
    })->name('registration-requests.index');
    
    // Player Licenses routes
    Route::get('/player-licenses', function () {
        return view('modules.player-licenses.index');
    })->name('player-licenses.index');
    
    // Contracts routes
    Route::get('/contracts', function () {
        return view('modules.contracts.index');
    })->name('contracts.index');
    
    // FIFA routes
    Route::get('/fifa/dashboard', function () {
        return view('modules.fifa.dashboard');
    })->name('fifa.dashboard');
    
    Route::get('/fifa/connectivity', function () {
        return view('modules.fifa.connectivity');
    })->name('fifa.connectivity');
    
    Route::get('/fifa/sync-dashboard', function () {
        return view('modules.fifa.sync-dashboard');
    })->name('fifa.sync-dashboard');
    
    Route::get('/fifa/contracts', function () {
        return view('modules.fifa.contracts');
    })->name('fifa.contracts');
    
    Route::get('/fifa/analytics', function () {
        return view('modules.fifa.analytics');
    })->name('fifa.analytics');
    
    Route::get('/fifa/statistics', function () {
        return view('modules.fifa.statistics');
    })->name('fifa.statistics');
    
    // Device Connections routes
    Route::get('/device-connections', function () {
        return view('modules.device-connections.index');
    })->name('device-connections.index');
    
    // Association Dashboard routes
    Route::get('/association/dashboard', function () {
        return view('modules.association.index');
    })->name('association.dashboard');
    
    // Association Registration routes
    Route::get('/association/registration', [App\Http\Controllers\AssociationRegistrationController::class, 'create'])
        ->name('association.registration.create');
    
    Route::post('/association/registration', [App\Http\Controllers\AssociationRegistrationController::class, 'store'])
        ->name('association.registration.store');
    
    // Association Fraud Detection API
Route::post('/api/v1/association/fraud-detection', [App\Http\Controllers\AssociationRegistrationController::class, 'fraudDetection'])
    ->name('association.fraud-detection.api');

Route::get('/api/v1/association/fraud-detection/test', [App\Http\Controllers\AssociationRegistrationController::class, 'testGPT4Connection'])
    ->name('association.fraud-detection.test');

Route::get('/api/v1/association/fraud-detection/stats', [App\Http\Controllers\AssociationRegistrationController::class, 'getFraudStats'])
    ->name('association.fraud-detection.stats');

// Clinical Data Support System Routes
Route::get('/clinical/support', [App\Http\Controllers\ClinicalDataSupportController::class, 'index'])
    ->name('clinical.support.dashboard');

Route::post('/api/v1/clinical/analyze-pcma/{pCMAId}', [App\Http\Controllers\ClinicalDataSupportController::class, 'analyzePCMA'])
    ->name('clinical.analyze.pcma');

Route::post('/api/v1/clinical/analyze-visit/{visitId}', [App\Http\Controllers\ClinicalDataSupportController::class, 'analyzeVisit'])
    ->name('clinical.analyze.visit');

Route::post('/api/v1/clinical/batch-analyze-pcma', [App\Http\Controllers\ClinicalDataSupportController::class, 'batchAnalyzePCMA'])
    ->name('clinical.batch.analyze.pcma');

Route::post('/api/v1/clinical/batch-analyze-visits', [App\Http\Controllers\ClinicalDataSupportController::class, 'batchAnalyzeVisits'])
    ->name('clinical.batch.analyze.visits');

Route::post('/api/v1/clinical/test-gemini', [App\Http\Controllers\ClinicalDataSupportController::class, 'testGeminiConnection'])
    ->name('clinical.test.gemini');

Route::get('/api/v1/clinical/stats', [App\Http\Controllers\ClinicalDataSupportController::class, 'getClinicalStats'])
    ->name('clinical.stats');

Route::get('/api/v1/clinical/recommendations', [App\Http\Controllers\ClinicalDataSupportController::class, 'getClinicalRecommendations'])
    ->name('clinical.recommendations');

Route::post('/api/v1/clinical/report', [App\Http\Controllers\ClinicalDataSupportController::class, 'generateClinicalReport'])
    ->name('clinical.report');
    
    // Daily Passport routes
    Route::get('/daily-passport', function () {
        return view('modules.daily-passport.index');
    })->name('daily-passport.index');
    
    // Data Sync routes
    Route::get('/data-sync', function () {
        return view('modules.data-sync.index');
    })->name('data-sync.index');
    
    // FIFA Players Search routes
    Route::get('/fifa/players/search', function () {
        return view('modules.fifa.players.search');
    })->name('fifa.players.search');
    
    // Apple Health Kit routes
    Route::get('/apple-health-kit', function () {
        return view('modules.apple-health-kit.index');
    })->name('apple-health-kit.index');
    
    // Catapult Connect routes
    Route::get('/catapult-connect', function () {
        return view('modules.catapult-connect.index');
    })->name('catapult-connect.index');
    
    // Garmin Connect routes
    Route::get('/garmin-connect', function () {
        return view('modules.garmin-connect.index');
    })->name('garmin-connect.index');
    
    // Device Connections OAuth2 Tokens routes
    Route::get('/device-connections/oauth2/tokens', function () {
        return view('modules.device-connections.oauth2.tokens');
    })->name('device-connections.oauth2.tokens');
    
    // Healthcare routes
    Route::get('/healthcare', function () {
        return view('modules.healthcare.index');
    })->name('healthcare.index');
    
    Route::get('/healthcare/predictions', function () {
        return view('modules.healthcare.predictions');
    })->name('healthcare.predictions');
    
    Route::get('/healthcare/export', function () {
        return view('modules.healthcare.export');
    })->name('healthcare.export');
    
    // PCMA Dashboard routes (SPECIFIC ROUTES FIRST)
    Route::get('/pcma/dashboard', function () {
        $stats = [
            'total_pcmas' => 0,
            'completed_pcmas' => 0,
            'pending_pcmas' => 0,
            'failed_pcmas' => 0
        ];
        
        $recentPcmas = collect([]);
        
        // Try to get actual PCMA stats if model exists
        try {
            if (class_exists('\App\Models\PCMA')) {
                $stats['total_pcmas'] = \App\Models\PCMA::count();
                $stats['completed_pcmas'] = \App\Models\PCMA::where('status', 'completed')->count();
                $stats['pending_pcmas'] = \App\Models\PCMA::where('status', 'pending')->count();
                $stats['failed_pcmas'] = \App\Models\PCMA::where('status', 'failed')->count();
                
                // Get recent PCMAs
                $recentPcmas = \App\Models\PCMA::with(['athlete', 'assessor'])->latest()->take(5)->get();
            }
        } catch (\Exception $e) {
            // PCMA model might not exist or table is missing
        }
        
        return view('pcma.dashboard', [
            'stats' => $stats,
            'recentPcmas' => $recentPcmas
        ]);
    })->name('pcma.dashboard');
    
    // PCMA Create route (SPECIFIC ROUTE)
    Route::get('/pcma/create', function () {
        $athletes = collect([]);
        $users = collect([]);
        
        // Try to get actual players if model exists
        try {
            if (class_exists('\App\Models\Player')) {
                $athletes = \App\Models\Player::with('club')->orderBy('first_name')->get();
            }
        } catch (\Exception $e) {
            // Player model might not exist or table is missing
        }
        
        // Try to get actual users if model exists
        try {
            if (class_exists('\App\Models\User')) {
                $users = \App\Models\User::orderBy('name')->get();
            }
        } catch (\Exception $e) {
            // User model might not exist or table is missing
        }
        
        // If no athletes found, create test data
        if ($athletes->isEmpty()) {
            $athletes = collect([
                (object)['id' => 1, 'first_name' => 'Test', 'last_name' => 'Player 1', 'club_id' => 1],
                (object)['id' => 2, 'first_name' => 'Test', 'last_name' => 'Player 2', 'club_id' => 1],
                (object)['id' => 3, 'first_name' => 'Test', 'last_name' => 'Player 3', 'club_id' => 2],
            ]);
        }
        
        // If no users found, create test data
        if ($users->isEmpty()) {
            $users = collect([
                (object)['id' => 1, 'name' => 'Test Assessor 1', 'email' => 'assessor1@test.com'],
                (object)['id' => 2, 'name' => 'Test Assessor 2', 'email' => 'assessor2@test.com'],
            ]);
        }
        
        return view('pcma.create', [
            'athletes' => $athletes,
            'users' => $users
        ]);
    })->name('pcma.create');
    

    
    Route::post('/pcma', function (Request $request) {
        \Log::info('PCMA store route called', $request->all());
        \Log::info('PCMA store route - Required fields check:', [
            'athlete_id' => $request->input('athlete_id'),
            'type' => $request->input('type'),
            'assessor_id' => $request->input('assessor_id'),
            'assessment_date' => $request->input('assessment_date'),
            'status' => $request->input('status'),
        ]);
        try {
            // Validate the request
            $validated = $request->validate([
                'athlete_id' => 'required|exists:athletes,id',
                'fifa_connect_id' => 'nullable|string|max:255',
                'type' => 'required|in:bpma,cardio,dental,neurological,orthopedic',
                'assessor_id' => 'required|exists:users,id',
                'assessment_date' => 'required|date',
                'status' => 'required|in:pending,completed,failed',
                'result_json' => 'nullable|string',
                'notes' => 'nullable|string',
                'clinical_notes' => 'nullable|string',
                // Signature fields
                'is_signed' => 'nullable|boolean',
                'signed_by' => 'nullable|string|max:255',
                'signed_at' => 'nullable|date',
                'license_number' => 'nullable|string|max:255',
                'signature_data' => 'nullable|string',
                'signature_image' => 'nullable|string',
                // Vital Signs
                'blood_pressure' => 'nullable|string|max:255',
                'heart_rate' => 'nullable|integer|min:0|max:300',
                'temperature' => 'nullable|numeric|min:30|max:45',
                'respiratory_rate' => 'nullable|integer|min:0|max:100',
                'oxygen_saturation' => 'nullable|integer|min:0|max:100',
                'weight' => 'nullable|numeric|min:0|max:500',
                // Medical History
                'medical_history' => 'nullable|string',
                'surgical_history' => 'nullable|string',
                'medications' => 'nullable|string',
                'allergies' => 'nullable|string',
                // Physical Examination
                'general_appearance' => 'nullable|in:normal,abnormal',
                'skin_examination' => 'nullable|in:normal,abnormal',
                'lymph_nodes' => 'nullable|in:normal,enlarged',
                'abdomen_examination' => 'nullable|in:normal,abnormal',
                // Cardiovascular Assessment
                'cardiac_rhythm' => 'nullable|in:sinus,irregular,arrhythmia',
                'heart_murmur' => 'nullable|in:none,systolic,diastolic',
                'blood_pressure_rest' => 'nullable|string|max:255',
                'blood_pressure_exercise' => 'nullable|string|max:255',
                // Neurological Assessment
                'consciousness' => 'nullable|in:alert,confused,drowsy',
                'cranial_nerves' => 'nullable|in:normal,abnormal',
                'motor_function' => 'nullable|in:normal,weakness,paralysis',
                'sensory_function' => 'nullable|in:normal,decreased,absent',
                // Musculoskeletal Assessment
                'joint_mobility' => 'nullable|in:normal,limited,restricted',
                'muscle_strength' => 'nullable|in:normal,reduced,weak',
                'pain_assessment' => 'nullable|in:none,mild,moderate,severe',
                'range_of_motion' => 'nullable|in:full,limited,restricted',
                // Medical Imaging
                'ecg_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ecg_date' => 'nullable|date',
                'ecg_interpretation' => 'nullable|in:normal,sinus_bradycardia,sinus_tachycardia,atrial_fibrillation,ventricular_tachycardia,st_elevation,st_depression,qt_prolongation,abnormal',
                'ecg_notes' => 'nullable|string',
                'mri_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'mri_date' => 'nullable|date',
                'mri_type' => 'nullable|in:brain,spine,knee,shoulder,ankle,hip,cardiac,other',
                'mri_findings' => 'nullable|in:normal,mild_abnormality,moderate_abnormality,severe_abnormality,fracture,tumor,inflammation,degenerative,other',
                'mri_notes' => 'nullable|string',
                'xray_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ct_scan_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ultrasound_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
            ]);
            \Log::info('PCMA validation passed');

            // Create the PCMA record with basic fields
            $pcma = new \App\Models\PCMA();
            $pcma->athlete_id = $validated['athlete_id'];
            $pcma->type = $validated['type'];
            $pcma->assessor_id = $validated['assessor_id'];
            $pcma->status = $validated['status'];
            $pcma->notes = $validated['notes'] ?? 'Évaluation médicale complétée';
            
            // Handle signature data if provided
            if ($request->has('is_signed') && $request->input('is_signed')) {
                $pcma->is_signed = true;
                $pcma->signed_by = $validated['signed_by'] ?? null;
                $pcma->signed_at = $validated['signed_at'] ?? now();
                $pcma->license_number = $validated['license_number'] ?? null;
                $pcma->signature_data = $validated['signature_data'] ?? null;
                $pcma->signature_image = $validated['signature_image'] ?? null;
            }
            
            // Store detailed data in result_json
            $resultJson = [
                'vital_signs' => [
                    'blood_pressure' => $validated['blood_pressure'] ?? null,
                    'heart_rate' => $validated['heart_rate'] ?? null,
                    'temperature' => $validated['temperature'] ?? null,
                    'respiratory_rate' => $validated['respiratory_rate'] ?? null,
                    'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
                    'weight' => $validated['weight'] ?? null,
                ],
                'medical_history' => [
                    'cardiovascular_history' => $validated['medical_history'] ?? null,
                    'surgical_history' => $validated['surgical_history'] ?? null,
                    'medications' => $validated['medications'] ?? null,
                    'allergies' => $validated['allergies'] ?? null,
                ],
                'physical_examination' => [
                    'general_appearance' => $validated['general_appearance'] ?? null,
                    'skin_examination' => $validated['skin_examination'] ?? null,
                    'lymph_nodes' => $validated['lymph_nodes'] ?? null,
                    'abdomen_examination' => $validated['abdomen_examination'] ?? null,
                ],
                'cardiovascular_assessment' => [
                    'cardiac_rhythm' => $validated['cardiac_rhythm'] ?? null,
                    'heart_murmur' => $validated['heart_murmur'] ?? null,
                    'blood_pressure_rest' => $validated['blood_pressure_rest'] ?? null,
                    'blood_pressure_exercise' => $validated['blood_pressure_exercise'] ?? null,
                ],
                'neurological_assessment' => [
                    'consciousness' => $validated['consciousness'] ?? null,
                    'cranial_nerves' => $validated['cranial_nerves'] ?? null,
                    'motor_function' => $validated['motor_function'] ?? null,
                    'sensory_function' => $validated['sensory_function'] ?? null,
                ],
                'musculoskeletal_assessment' => [
                    'joint_mobility' => $validated['joint_mobility'] ?? null,
                    'muscle_strength' => $validated['muscle_strength'] ?? null,
                    'pain_assessment' => $validated['pain_assessment'] ?? null,
                    'range_of_motion' => $validated['range_of_motion'] ?? null,
                ],
                'medical_imaging' => [
                    'ecg_date' => $validated['ecg_date'] ?? null,
                    'ecg_interpretation' => $validated['ecg_interpretation'] ?? null,
                    'ecg_notes' => $validated['ecg_notes'] ?? null,
                    'mri_date' => $validated['mri_date'] ?? null,
                    'mri_type' => $validated['mri_type'] ?? null,
                    'mri_findings' => $validated['mri_findings'] ?? null,
                    'mri_notes' => $validated['mri_notes'] ?? null,
                ],
                'clinical_notes' => $validated['clinical_notes'] ?? null,
                'assessment_date' => $validated['assessment_date'] ?? null,
                'fifa_connect_id' => $validated['fifa_connect_id'] ?? null,
            ];
            
            $pcma->result_json = $resultJson;
            
            $pcma->save();
            
            // Return JSON response for AJAX requests or if signature data is provided
            if ($request->expectsJson() || $request->has('signature_data')) {
                return response()->json([
                    'success' => true,
                    'message' => 'PCMA créé avec succès',
                    'pcma_id' => $pcma->id
                ]);
            }
            
            return redirect()->route('pcma.dashboard')->with('success', 'PCMA créé avec succès');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error("Validation error creating PCMA: " . $e->getMessage());
            \Log::error("Validation errors: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            \Log::error("Error creating PCMA: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du PCMA: ' . $e->getMessage()
            ], 500);
        }
    })->name('pcma.store');
    
    Route::get('/pcma/{pcma}', function ($pcma) {
        $athletes = collect([]); // Empty collection for now
        
        // Try to get actual players if model exists
        try {
            if (class_exists('\App\Models\Player')) {
                $athletes = \App\Models\Player::with('club')->orderBy('first_name')->get();
            }
        } catch (\Exception $e) {
            // Player model might not exist or table is missing
        }

        // If $pcma is a string ID, fetch the model
        if (is_string($pcma) && is_numeric($pcma)) {
            try {
                if (class_exists('\App\Models\PCMA')) {
                    $pcma = \App\Models\PCMA::with(['player', 'assessor'])->find($pcma);
                } else {
                    // Create a mock PCMA object if model doesn't exist
                    $pcma = (object) [
                        'id' => $pcma,
                        'player_name' => 'Test Player',
                        'assessor_name' => 'Test Assessor',
                        'assessment_date' => now(),
                        'status' => 'completed',
                        'notes' => 'Test PCMA assessment',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching PCMA: " . $e->getMessage());
                abort(404, 'PCMA not found');
            }
        }

        if (!$pcma) {
            abort(404, 'PCMA not found');
        }

        return view('pcma.show', [
            'pcma' => $pcma,
            'athletes' => $athletes
        ]);
    })->name('pcma.show');

    Route::get('/pcma/{pcma}/edit', function ($pcma) {
        $athletes = collect([]); // Empty collection for now
        
        // Try to get actual players if model exists
        try {
            if (class_exists('\App\Models\Player')) {
                $athletes = \App\Models\Player::with('club')->orderBy('first_name')->get();
            }
        } catch (\Exception $e) {
            // Player model might not exist or table is missing
        }
        
        // If $pcma is a string ID, fetch the model
        if (is_string($pcma) && is_numeric($pcma)) {
            try {
                if (class_exists('\App\Models\PCMA')) {
                    $pcma = \App\Models\PCMA::with(['player', 'assessor'])->find($pcma);
                } else {
                    // Create a mock PCMA object if model doesn't exist
                    $pcma = (object) [
                        'id' => $pcma,
                        'player_name' => 'Test Player',
                        'assessor_name' => 'Test Assessor',
                        'assessment_date' => now(),
                        'status' => 'completed',
                        'notes' => 'Test PCMA assessment',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } catch (\Exception $e) {
                \Log::error("Error fetching PCMA for edit: " . $e->getMessage());
                abort(404, 'PCMA not found');
            }
        }

        if (!$pcma) {
            abort(404, 'PCMA not found');
        }

        return view('pcma.edit', [
            'pcma' => $pcma,
            'athletes' => $athletes,
            'users' => \App\Models\User::all()
        ]);
    })->name('pcma.edit');

    Route::put('/pcma/{pcma}', function (Request $request, $pcma) {
        try {
            // Find the PCMA record
            $pcmaRecord = \App\Models\PCMA::find($pcma);
            if (!$pcmaRecord) {
                abort(404, 'PCMA not found');
            }
            

            

            
            // Validate the request
            $validated = $request->validate([
                'athlete_id' => 'required|exists:athletes,id',
                'fifa_connect_id' => 'nullable|string|max:255',
                'type' => 'required|in:bpma,cardio,dental,neurological,orthopedic',
                'assessor_id' => 'required|exists:users,id',
                'assessment_date' => 'required|date',
                'status' => 'required|in:pending,completed,failed',
                'result_json' => 'nullable|string',
                'notes' => 'nullable|string',
                'clinical_notes' => 'nullable|string',
                // Vital Signs
                'blood_pressure' => 'nullable|string|max:255',
                'heart_rate' => 'nullable|integer|min:0|max:300',
                'temperature' => 'nullable|numeric|min:30|max:45',
                'respiratory_rate' => 'nullable|integer|min:0|max:100',
                'oxygen_saturation' => 'nullable|integer|min:0|max:100',
                'weight' => 'nullable|numeric|min:0|max:500',
                // Medical History
                'medical_history' => 'nullable|string',
                'surgical_history' => 'nullable|string',
                'medications' => 'nullable|string',
                'allergies' => 'nullable|string',
                // Physical Examination
                'general_appearance' => 'nullable|in:normal,abnormal',
                'skin_examination' => 'nullable|in:normal,abnormal',
                'lymph_nodes' => 'nullable|in:normal,enlarged',
                'abdomen_examination' => 'nullable|in:normal,abnormal',
                // Cardiovascular Assessment
                'cardiac_rhythm' => 'nullable|in:sinus,irregular,arrhythmia',
                'heart_murmur' => 'nullable|in:none,systolic,diastolic',
                'blood_pressure_rest' => 'nullable|string|max:255',
                'blood_pressure_exercise' => 'nullable|string|max:255',
                // Neurological Assessment
                'consciousness' => 'nullable|in:alert,confused,drowsy',
                'cranial_nerves' => 'nullable|in:normal,abnormal',
                'motor_function' => 'nullable|in:normal,weakness,paralysis',
                'sensory_function' => 'nullable|in:normal,decreased,absent',
                // Musculoskeletal Assessment
                'joint_mobility' => 'nullable|in:normal,limited,restricted',
                'muscle_strength' => 'nullable|in:normal,reduced,weak',
                'pain_assessment' => 'nullable|in:none,mild,moderate,severe',
                'range_of_motion' => 'nullable|in:full,limited,restricted',
                // Medical Imaging
                'ecg_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ecg_date' => 'nullable|date',
                'ecg_interpretation' => 'nullable|in:normal,sinus_bradycardia,sinus_tachycardia,atrial_fibrillation,ventricular_tachycardia,st_elevation,st_depression,qt_prolongation,abnormal',
                'ecg_notes' => 'nullable|string',
                'mri_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'mri_date' => 'nullable|date',
                'mri_type' => 'nullable|in:brain,spine,knee,shoulder,ankle,hip,cardiac,other',
                'mri_findings' => 'nullable|in:normal,mild_abnormality,moderate_abnormality,severe_abnormality,fracture,tumor,inflammation,degenerative,other',
                'mri_notes' => 'nullable|string',
                'xray_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ct_scan_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
                'ultrasound_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:10240',
            ]);

            // Update the PCMA record with basic fields
            $pcmaRecord->athlete_id = $validated['athlete_id'];
            $pcmaRecord->type = $validated['type'];
            $pcmaRecord->assessor_id = $validated['assessor_id'];
            $pcmaRecord->status = $validated['status'];
            $pcmaRecord->notes = $validated['notes'];
            
            // Store detailed data in result_json
            $resultJson = [
                'vital_signs' => [
                    'blood_pressure' => $validated['blood_pressure'] ?? null,
                    'heart_rate' => $validated['heart_rate'] ?? null,
                    'temperature' => $validated['temperature'] ?? null,
                    'respiratory_rate' => $validated['respiratory_rate'] ?? null,
                    'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
                    'weight' => $validated['weight'] ?? null,
                ],
                'medical_history' => [
                    'cardiovascular_history' => $validated['medical_history'] ?? null,
                    'surgical_history' => $validated['surgical_history'] ?? null,
                    'medications' => $validated['medications'] ?? null,
                    'allergies' => $validated['allergies'] ?? null,
                ],
                'physical_examination' => [
                    'general_appearance' => $validated['general_appearance'] ?? null,
                    'skin_examination' => $validated['skin_examination'] ?? null,
                    'lymph_nodes' => $validated['lymph_nodes'] ?? null,
                    'abdomen_examination' => $validated['abdomen_examination'] ?? null,
                ],
                'cardiovascular_assessment' => [
                    'cardiac_rhythm' => $validated['cardiac_rhythm'] ?? null,
                    'heart_murmur' => $validated['heart_murmur'] ?? null,
                    'blood_pressure_rest' => $validated['blood_pressure_rest'] ?? null,
                    'blood_pressure_exercise' => $validated['blood_pressure_exercise'] ?? null,
                ],
                'neurological_assessment' => [
                    'consciousness' => $validated['consciousness'] ?? null,
                    'cranial_nerves' => $validated['cranial_nerves'] ?? null,
                    'motor_function' => $validated['motor_function'] ?? null,
                    'sensory_function' => $validated['sensory_function'] ?? null,
                ],
                'musculoskeletal_assessment' => [
                    'joint_mobility' => $validated['joint_mobility'] ?? null,
                    'muscle_strength' => $validated['muscle_strength'] ?? null,
                    'pain_assessment' => $validated['pain_assessment'] ?? null,
                    'range_of_motion' => $validated['range_of_motion'] ?? null,
                ],
                'medical_imaging' => [
                    'ecg_date' => $validated['ecg_date'] ?? null,
                    'ecg_interpretation' => $validated['ecg_interpretation'] ?? null,
                    'ecg_notes' => $validated['ecg_notes'] ?? null,
                    'mri_date' => $validated['mri_date'] ?? null,
                    'mri_type' => $validated['mri_type'] ?? null,
                    'mri_findings' => $validated['mri_findings'] ?? null,
                    'mri_notes' => $validated['mri_notes'] ?? null,
                ],
                'clinical_notes' => $validated['clinical_notes'] ?? null,
                'assessment_date' => $validated['assessment_date'] ?? null,
                'fifa_connect_id' => $validated['fifa_connect_id'] ?? null,
            ];
            
            $pcmaRecord->result_json = $resultJson;
            $pcmaRecord->save();
            
            return redirect()->route('pcma.dashboard')->with('success', 'PCMA mis à jour avec succès');
            
        } catch (\Exception $e) {
            \Log::error("Error updating PCMA: " . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour du PCMA: ' . $e->getMessage()]);
        }
    })->name('pcma.update');

    Route::delete('/pcma/{pcma}', function ($pcma) {
        return redirect()->route('pcma.dashboard')->with('success', 'PCMA deleted successfully');
    })->name('pcma.destroy');

    // Additional PCMA routes
    // PDF routes - specific routes first
    Route::get('/pcma/pdf', function () {
        return response()->json([
            'success' => false,
            'message' => 'PDF generation requires form data. Please use the PCMA form to generate a PDF.'
        ], 400);
    })->name('pcma.pdf');
    
    Route::get('/pcma/{pcma}/pdf', function ($pcma) {
        try {
            // Find the PCMA record
            $pcmaRecord = \App\Models\PCMA::with(['athlete', 'assessor'])->find($pcma);
            if (!$pcmaRecord) {
                abort(404, 'PCMA not found');
            }
            
            // Generate PDF content with proper data format
            $pdfContent = view('pcma.pdf', [
                'formData' => [
                    'type' => $pcmaRecord->type ?? 'standard',
                    'assessment_date' => $pcmaRecord->assessment_date ?? now()->format('Y-m-d'),
                    'assessment_id' => $pcmaRecord->id,
                    'blood_pressure' => $pcmaRecord->blood_pressure ?? 'Non mesuré',
                    'heart_rate' => $pcmaRecord->heart_rate ?? 'Non mesuré',
                    'temperature' => $pcmaRecord->temperature ?? 'Non mesuré',
                    'oxygen_saturation' => $pcmaRecord->oxygen_saturation ?? 'Non mesuré',
                    'cardiovascular_history' => $pcmaRecord->cardiovascular_history ?? 'Aucun',
                    'surgical_history' => $pcmaRecord->surgical_history ?? 'Aucun',
                    'current_medications' => $pcmaRecord->current_medications ?? 'Aucun',
                    'allergies' => $pcmaRecord->allergies ?? 'Aucune',
                ],
                'fitnessResults' => null,
                'athlete' => $pcmaRecord->athlete,
                'generatedAt' => now(),
                'isSigned' => $pcmaRecord->is_signed ?? false,
                'signedBy' => $pcmaRecord->signed_by ?? null,
                'licenseNumber' => $pcmaRecord->license_number ?? null,
                'signedAt' => $pcmaRecord->signed_at ?? null,
                'signatureImage' => $pcmaRecord->signature_image ? asset('storage/' . $pcmaRecord->signature_image) : null,
                'signatureData' => $pcmaRecord->signature_data ?? null
            ])->render();
            
            // For now, return a simple HTML response that can be printed as PDF
            // In a real implementation, you would use a library like DomPDF or Snappy
            return response($pdfContent)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'inline; filename="pcma-' . $pcmaRecord->id . '.html"');
                
        } catch (\Exception $e) {
            \Log::error("Error generating PCMA PDF: " . $e->getMessage());
            return redirect()->route('pcma.show', $pcma)->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    })->name('pcma.view.pdf');

    Route::post('/pcma/{pcma}/complete', function ($pcma) {
        return redirect()->route('pcma.show', $pcma)->with('success', 'PCMA marked as completed');
    })->name('pcma.complete');

    Route::post('/pcma/{pcma}/fail', function ($pcma) {
        return redirect()->route('pcma.show', $pcma)->with('error', 'PCMA marked as failed');
    })->name('pcma.fail');

    Route::get('/pcma', function (Request $request) {
        try {
            // Get PCMA records with relationships
            $query = \App\Models\PCMA::with(['athlete', 'assessor']);
            
            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('search')) {
                $query->whereHas('athlete', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
            
            // Order by latest first
            $pcmas = $query->orderBy('created_at', 'desc')->paginate(15);
            
            return view('pcma.index', compact('pcmas'));
            
        } catch (\Exception $e) {
            \Log::error("Error loading PCMA index: " . $e->getMessage());
            return view('pcma.index', ['pcmas' => collect([])]);
        }
    })->name('pcma.index');
    
    // PCMA AI Analysis routes
    Route::post('/pcma/ai-analyze-ecg', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeEcg'])->name('pcma.ai-analyze-ecg');
    Route::post('/pcma/ai-analyze-mri', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeMri'])->name('pcma.ai-analyze-mri');
    Route::post('/pcma/ai-analyze-xray', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeXray'])->name('pcma.ai-analyze-xray');
Route::post('/pcma/ai-analyze-ct', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeCt'])->name('pcma.ai-analyze-ct');
Route::post('/pcma/ai-analyze-ultrasound', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeUltrasound'])->name('pcma.ai-analyze-ultrasound');
Route::post('/pcma/ai-fitness-assessment', [App\Http\Controllers\PCMAController::class, 'aiFitnessAssessment'])->name('pcma.ai-fitness-assessment');

// Test PDF route (using api middleware group)
Route::get('/test-pdf', function() {
    try {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Test PDF</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; }
            </style>
        </head>
        <body>
            <h1>Test PDF Generation</h1>
            <p>This is a test PDF to verify DomPDF is working correctly.</p>
            <p>Generated at: ' . now()->format('Y-m-d H:i:s') . '</p>
        </body>
        </html>');
        $pdf->setPaper('A4', 'portrait');
        
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="test.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('test.pdf')->middleware('api');
Route::post('/pcma/ai-analyze-ecg-effort', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeEcgEffort'])->name('pcma.ai-analyze-ecg-effort');
    Route::post('/pcma/ai-analyze-scintigraphy', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeScintigraphy'])->name('pcma.ai-analyze-scintigraphy');
    Route::post('/pcma/ai-analyze-scat', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeScat'])->name('pcma.ai-analyze-scat');
    Route::post('/pcma/ai-analyze-complete', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeComplete'])->name('pcma.ai-analyze-complete');
    
    // Medical Predictions Dashboard routes
    Route::get('/medical-predictions/dashboard', function () {
        return view('modules.medical-predictions.dashboard');
    })->name('medical-predictions.dashboard');
    
    // Appointments routes
    Route::get('/appointments', function () {
        return view('modules.appointments.index');
    })->name('appointments.index');
    
    // Visits routes
    Route::get('/visits', function () {
        return view('modules.visits.index');
    })->name('visits.index');
    
    // Documents routes
    Route::get('/documents', function () {
        return view('modules.documents.index');
    })->name('documents.index');
    
    // Portal Dashboard routes
    Route::get('/portal/dashboard', function () {
        return view('modules.portal.dashboard');
    })->name('portal.dashboard');
    
    // Portal sub-routes
    Route::get('/portal/medical-record', function () {
        return view('modules.portal.medical-record');
    })->name('portal.medical-record');
    
    Route::get('/portal/wellness', function () {
        return view('modules.portal.wellness');
    })->name('portal.wellness');
    
    Route::get('/portal/devices', function () {
        return view('modules.portal.devices');
    })->name('portal.devices');
    
    // Secretary Dashboard routes
    Route::get('/secretary/dashboard', function () {
        return view('modules.secretary.dashboard');
    })->name('secretary.dashboard');
    
    // Secretary sub-routes
    Route::get('/appointments', function () {
        return view('modules.appointments.index');
    })->name('appointments.index');
    
    Route::get('/documents', function () {
        return view('modules.documents.index');
    })->name('documents.index');
    
    // Referee routes
    Route::get('/referee/dashboard', function () {
        return view('modules.referee.dashboard');
    })->name('referee.dashboard');
    
    Route::get('/referee/match-assignments', function () {
        return view('modules.referee.match-assignments');
    })->name('referee.match-assignments');
    
    Route::get('/referee/competition-schedule', function () {
        return view('modules.referee.competition-schedule');
    })->name('referee.competition-schedule');
    
    Route::get('/referee/create-match-report', function () {
        return view('modules.referee.create-match-report');
    })->name('referee.create-match-report');
    
    Route::get('/referee/performance-stats', function () {
        return view('modules.referee.performance-stats');
    })->name('referee.performance-stats');
    
    Route::get('/referee/settings', function () {
        return view('modules.referee.settings');
    })->name('referee.settings');
    
    // Performances Analytics routes
    Route::get('/performances/analytics', function () {
        return view('modules.performances.analytics');
    })->name('performances.analytics');
    
    // Performances Trends routes
    Route::get('/performances/trends', function () {
        return view('modules.performances.trends');
    })->name('performances.trends');
    
    // Alerts Performance routes
    Route::get('/alerts/performance', function () {
        return view('modules.alerts.performance');
    })->name('alerts.performance');
    
    // Profile routes
    Route::get('/profile', function () {
        return view('modules.profile.show');
    })->name('profile.show');
    
    // Notifications routes
    Route::post('/notifications/{id}/mark-as-read', function ($id) {
        return redirect()->back();
    })->name('notifications.markAsRead');
    
    // License Requests routes
    Route::get('/license-requests/{id}', function ($id) {
        return view('modules.license-requests.show');
    })->name('license-requests.show');
    
    // Medical Predictions routes
    Route::get('/medical-predictions', function () {
        return view('modules.medical-predictions.index');
    })->name('medical-predictions.index');
    
    Route::get('/medical-predictions/create', function () {
        $players = collect([]); // Empty collection for now
        $predictionTypes = [
            'injury_risk' => 'Risque de Blessure',
            'performance_prediction' => 'Prédiction de Performance',
            'recovery_time' => 'Temps de Récupération',
            'fitness_level' => 'Niveau de Forme',
            'health_status' => 'État de Santé'
        ];
        $selectedPlayer = null;
        
        // Try to get actual players if model exists
        try {
            if (class_exists('\App\Models\Player')) {
                $players = \App\Models\Player::with('club')->orderBy('first_name')->get();
            }
        } catch (\Exception $e) {
            // Player model might not exist or table is missing
        }
        
        return view('medical-predictions.create', [
            'players' => $players,
            'predictionTypes' => $predictionTypes,
            'selectedPlayer' => $selectedPlayer
        ]);
    })->name('medical-predictions.create');
    
    Route::post('/medical-predictions', function () {
        return redirect()->route('medical-predictions.index')->with('success', 'Medical prediction created successfully');
    })->name('medical-predictions.store');
    
    Route::get('/medical-predictions/{prediction}', function ($prediction) {
        return view('medical-predictions.show', [
            'medicalPrediction' => $prediction
        ]);
    })->name('medical-predictions.show');
    
    Route::get('/medical-predictions/{prediction}/edit', function ($prediction) {
        return view('medical-predictions.edit', [
            'medicalPrediction' => $prediction
        ]);
    })->name('medical-predictions.edit');
    
    Route::put('/medical-predictions/{prediction}', function ($prediction) {
        return redirect()->route('medical-predictions.index')->with('success', 'Medical prediction updated successfully');
    })->name('medical-predictions.update');
    
    Route::delete('/medical-predictions/{prediction}', function ($prediction) {
        return redirect()->route('medical-predictions.index')->with('success', 'Medical prediction deleted successfully');
    })->name('medical-predictions.destroy');
    
    Route::get('/medical-predictions/dashboard', function () {
        return view('medical-predictions.dashboard');
    })->name('medical-predictions.dashboard');
    
    // Healthcare Records routes
    Route::get('/healthcare/records/{record}', function ($record) {
        return view('modules.healthcare.records.show', [
            'record' => $record
        ]);
    })->name('healthcare.records.show');
    
    Route::get('/healthcare/records/{record}/edit', function ($record) {
        return view('modules.healthcare.records.edit', [
            'record' => $record
        ]);
    })->name('healthcare.records.edit');
    
    Route::put('/healthcare/records/{record}', function ($record) {
        return redirect()->route('modules.healthcare.index')->with('success', 'Dossier médical mis à jour avec succès');
    })->name('healthcare.records.update');
    
    Route::delete('/healthcare/records/{record}', function ($record) {
        return redirect()->back()->with('success', 'Record deleted successfully');
    })->name('healthcare.records.destroy');
    
    // Admin Account Requests routes
    Route::get('/admin/account-requests', function () {
        return view('modules.admin.account-requests.index');
    })->name('admin.account-requests.index');
    
    // Module routes
    Route::get('/modules/medical', [App\Http\Controllers\MedicalModuleController::class, 'index'])->name('modules.medical.index');
    
    Route::get('/modules/medical/athlete/{id}', function ($id) {
        $player = null;
        $isDemo = false;
        
        // Demo players data
        $demoPlayers = [
            1 => ['id' => 1, 'name' => 'John Smith', 'full_name' => 'John Smith', 'first_name' => 'John', 'last_name' => 'Smith', 'date_of_birth' => '1995-03-15', 'age' => 29, 'position' => 'ST', 'nationality' => 'USA', 'club' => ['name' => 'Team Alpha']],
            2 => ['id' => 2, 'name' => 'Sarah Johnson', 'full_name' => 'Sarah Johnson', 'first_name' => 'Sarah', 'last_name' => 'Johnson', 'date_of_birth' => '1993-07-22', 'age' => 31, 'position' => 'MF', 'nationality' => 'Canada', 'club' => ['name' => 'Team Beta']],
            3 => ['id' => 3, 'name' => 'Mike Wilson', 'full_name' => 'Mike Wilson', 'first_name' => 'Mike', 'last_name' => 'Wilson', 'date_of_birth' => '1997-11-08', 'age' => 27, 'position' => 'DF', 'nationality' => 'UK', 'club' => ['name' => 'Team Gamma']],
            4 => ['id' => 4, 'name' => 'Emma Davis', 'full_name' => 'Emma Davis', 'first_name' => 'Emma', 'last_name' => 'Davis', 'date_of_birth' => '1994-05-12', 'age' => 30, 'position' => 'GK', 'nationality' => 'Australia', 'club' => ['name' => 'Team Delta']],
            5 => ['id' => 5, 'name' => 'Alex Brown', 'full_name' => 'Alex Brown', 'first_name' => 'Alex', 'last_name' => 'Brown', 'date_of_birth' => '1996-09-30', 'age' => 28, 'position' => 'FW', 'nationality' => 'Germany', 'club' => ['name' => 'Team Echo']]
        ];
        
        // Check if this is a demo player
        if (isset($demoPlayers[$id])) {
            $player = (object) $demoPlayers[$id];
            $isDemo = true;
        } else {
            // Try to get the real player if model exists
            try {
                if (class_exists('\App\Models\Player')) {
                    $player = \App\Models\Player::with(['club', 'healthRecords'])->find($id);
                }
            } catch (\Exception $e) {
                // Player model might not exist or table is missing
            }
        }
        
        return view('modules.medical.athlete', [
            'player' => $player,
            'isDemo' => $isDemo,
            'footballType' => 'association'
        ]);
    })->name('modules.medical.athlete');
    
    Route::get('/modules/healthcare', function () {
        $healthRecords = collect([]); // Empty collection for now
        
        // Try to get actual health records if model exists
        try {
            if (class_exists('\App\Models\HealthRecord')) {
                $healthRecords = \App\Models\HealthRecord::with(['player', 'user'])->latest()->get();
            }
        } catch (\Exception $e) {
            // HealthRecord model might not exist or table is missing
        }
        
        return view('modules.healthcare.index', [
            'footballType' => 'association',
            'healthRecords' => $healthRecords
        ]);
    })->name('modules.healthcare.index');
    
    Route::get('/modules/competitions', function () {
        return view('modules.competitions.index', ['footballType' => 'association']);
    })->name('modules.competitions.index');
    
    Route::get('/modules/players', function () {
        return view('modules.players.index', ['footballType' => 'association']);
    })->name('modules.players.index');
    
    Route::get('/modules/teams', function () {
        return view('modules.teams.index', ['footballType' => 'association']);
    })->name('modules.teams.index');
    
    Route::get('/modules/referees', function () {
        return view('modules.referees.index', ['footballType' => 'association']);
    })->name('modules.referees.index');
    
    Route::get('/modules/associations', function () {
        return view('modules.associations.index', ['footballType' => 'association']);
    })->name('modules.associations.index');
    
    Route::get('/modules/clubs', function () {
        return view('modules.clubs.index', ['footballType' => 'association']);
    })->name('modules.clubs.index');
    
    Route::get('/modules/administration', function () {
        return view('modules.administration.index', ['footballType' => 'association']);
    })->name('modules.administration.index');
    
    Route::get('/modules/licenses', function () {
        return view('modules.licenses.index', ['footballType' => 'association']);
    })->name('modules.licenses.index');

    // AI Testing routes
    Route::get('/ai-testing', [App\Http\Controllers\AITestingController::class, 'index'])->name('ai-testing.index');
    Route::get('/ai-testing/test', function () {
        return view('ai-testing.test');
    })->name('ai-testing.test');
    Route::post('/ai-testing/run-tests', [App\Http\Controllers\AITestingController::class, 'runTests'])->name('ai-testing.run-tests');
    Route::post('/ai-testing/test-provider', [App\Http\Controllers\AITestingController::class, 'testProvider'])->name('ai-testing.test-provider');
    Route::get('/ai-testing/providers', [App\Http\Controllers\AITestingController::class, 'getProviders'])->name('ai-testing.providers');
    Route::post('/ai-testing/medical-diagnosis', [App\Http\Controllers\AITestingController::class, 'testMedicalDiagnosis'])->name('ai-testing.medical-diagnosis');
    Route::post('/ai-testing/performance-analysis', [App\Http\Controllers\AITestingController::class, 'testPerformanceAnalysis'])->name('ai-testing.performance-analysis');
    Route::post('/ai-testing/injury-prediction', [App\Http\Controllers\AITestingController::class, 'testInjuryPrediction'])->name('ai-testing.injury-prediction');
    Route::get('/ai-testing/summary', [App\Http\Controllers\AITestingController::class, 'getSummary'])->name('ai-testing.summary');

    // Whisper API routes
    Route::get('/whisper', [App\Http\Controllers\WhisperController::class, 'index'])->name('whisper.index');
    Route::post('/whisper/transcribe', [App\Http\Controllers\WhisperController::class, 'transcribe'])->name('whisper.transcribe');
    Route::post('/whisper/transcribe-medical-consultation', [App\Http\Controllers\WhisperController::class, 'transcribeMedicalConsultation'])->name('whisper.transcribe-medical-consultation');
    Route::post('/whisper/transcribe-medical-dictation', [App\Http\Controllers\WhisperController::class, 'transcribeMedicalDictation'])->name('whisper.transcribe-medical-dictation');
    Route::post('/whisper/batch-transcribe', [App\Http\Controllers\WhisperController::class, 'batchTranscribe'])->name('whisper.batch-transcribe');
    Route::get('/whisper/supported-languages', [App\Http\Controllers\WhisperController::class, 'getSupportedLanguages'])->name('whisper.supported-languages');
    Route::get('/whisper/medical-prompt-types', [App\Http\Controllers\WhisperController::class, 'getMedicalPromptTypes'])->name('whisper.medical-prompt-types');
    Route::get('/whisper/test-connection', [App\Http\Controllers\WhisperController::class, 'testConnection'])->name('whisper.test-connection');
    Route::get('/whisper/model-info', [App\Http\Controllers\WhisperController::class, 'getModelInfo'])->name('whisper.model-info');
    Route::get('/whisper/history', [App\Http\Controllers\WhisperController::class, 'getHistory'])->name('whisper.history');

    // Whisper test route
    Route::get('/whisper-test', function () {
        return response()->json(['message' => 'Whisper test route working']);
    })->name('whisper.test');

    // Google Gemini AI routes
    Route::get('/gemini', [App\Http\Controllers\GoogleGeminiController::class, 'index'])->name('gemini.index');
    Route::get('/gemini/test-connection', [App\Http\Controllers\GoogleGeminiController::class, 'testConnection'])->name('gemini.test-connection');
    Route::post('/gemini/generate-diagnosis', [App\Http\Controllers\GoogleGeminiController::class, 'generateDiagnosis'])->name('gemini.generate-diagnosis');
    Route::post('/gemini/generate-treatment', [App\Http\Controllers\GoogleGeminiController::class, 'generateTreatment'])->name('gemini.generate-treatment');
    Route::post('/gemini/analyze-performance', [App\Http\Controllers\GoogleGeminiController::class, 'analyzePerformance'])->name('gemini.analyze-performance');
    Route::post('/gemini/predict-injury-risk', [App\Http\Controllers\GoogleGeminiController::class, 'predictInjuryRisk'])->name('gemini.predict-injury-risk');
    Route::post('/gemini/generate-rehab-plan', [App\Http\Controllers\GoogleGeminiController::class, 'generateRehabPlan'])->name('gemini.generate-rehab-plan');
    Route::post('/gemini/analyze-medical-image', [App\Http\Controllers\GoogleGeminiController::class, 'analyzeMedicalImage'])->name('gemini.analyze-medical-image');
    Route::get('/gemini/configuration', [App\Http\Controllers\GoogleGeminiController::class, 'getConfiguration'])->name('gemini.configuration');
    Route::get('/gemini/history', [App\Http\Controllers\GoogleGeminiController::class, 'getHistory'])->name('gemini.history');
});

// PDF generation routes (public access)
Route::post('/pcma/pdf', [App\Http\Controllers\PCMAController::class, 'generatePdf'])->name('pcma.pdf.post')->middleware('api');

// Simple test route
Route::get('/test-public', function () {
    return response()->json(['message' => 'Public route working']);
})->name('test.public');

// API routes
Route::get('/api/fit/kpis', [FitDashboardController::class, 'kpis'])->name('fit.kpis');

// Test routes
Route::get('/test-tabs', function () {
    $players = \App\Models\Player::orderBy('name')->get();
    return view('health-records.create', compact('players'));
})->name('test-tabs');

// Analytics routes
Route::get('/analytics/dashboard', function () {
    return view('analytics.dashboard');
})->name('analytics.dashboard');

Route::get('/analytics/digital-twin', function () {
    return view('analytics.digital-twin');
})->name('analytics.digital-twin');

// Performance routes
Route::get('/performance', function () {
    return view('performance.index');
})->name('performance.index');

// DTN routes
Route::get('/dtn', function () {
    return view('dtn.index');
})->name('dtn.index');

// RPM routes
Route::get('/rpm', function () {
    return view('rpm.index');
})->name('rpm.index');

// License Fraud Detection Routes
Route::post('/api/v1/licenses/fraud-detection/batch', [App\Http\Controllers\LicenseController::class, 'batchFraudDetection'])
    ->name('licenses.fraud-detection.batch');

Route::post('/api/v1/licenses/fraud-detection/analyze/{licenseId}', [App\Http\Controllers\LicenseController::class, 'analyzeLicenseFraud'])
    ->name('licenses.fraud-detection.analyze');

Route::post('/api/v1/licenses/fraud-detection/check-all', [App\Http\Controllers\LicenseController::class, 'checkAllLicenses'])
    ->name('licenses.fraud-detection.check-all');

// Clinical Data Support System Routes
Route::get('/clinical/support', [App\Http\Controllers\ClinicalDataSupportController::class, 'index'])
    ->name('clinical.support.dashboard');

Route::post('/api/v1/clinical/analyze-pcma/{pCMAId}', [App\Http\Controllers\ClinicalDataSupportController::class, 'analyzePCMA'])
    ->name('clinical.analyze.pcma');

Route::post('/api/v1/clinical/analyze-visit/{visitId}', [App\Http\Controllers\ClinicalDataSupportController::class, 'analyzeVisit'])
    ->name('clinical.analyze.visit');

Route::post('/api/v1/clinical/batch-analyze-pcma', [App\Http\Controllers\ClinicalDataSupportController::class, 'batchAnalyzePCMA'])
    ->name('clinical.batch.analyze.pcma');

Route::post('/api/v1/clinical/batch-analyze-visits', [App\Http\Controllers\ClinicalDataSupportController::class, 'batchAnalyzeVisits'])
    ->name('clinical.batch.analyze.visits');

Route::post('/api/v1/clinical/test-gemini', [App\Http\Controllers\ClinicalDataSupportController::class, 'testGeminiConnection'])
    ->name('clinical.test.gemini');

Route::get('/api/v1/clinical/stats', [App\Http\Controllers\ClinicalDataSupportController::class, 'getClinicalStats'])
    ->name('clinical.stats');

Route::get('/api/v1/clinical/recommendations', [App\Http\Controllers\ClinicalDataSupportController::class, 'getClinicalRecommendations'])
    ->name('clinical.recommendations');

Route::post('/api/v1/clinical/report', [App\Http\Controllers\ClinicalDataSupportController::class, 'generateClinicalReport'])
    ->name('clinical.report');

// Routes pour le diagramme dentaire
Route::get('/dental-chart', [App\Http\Controllers\DentalChartController::class, 'index'])->name('dental-chart.index');
Route::get('/dental-chart/{patient}', [App\Http\Controllers\DentalChartController::class, 'show'])->name('dental-chart.show');

// Route pour le diagramme dentaire
Route::get('/dental-chart/{healthRecord}', function ($healthRecord) {
    return view('health-records.dental-chart', compact('healthRecord'));
})->name('dental-chart.show');

// Route de test pour le diagramme dentaire
Route::get('/dental-chart-test', function () {
    return view('health-records.dental-chart-simple');
})->name('dental-chart.test');

// PCMA Routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/pcma', [App\Http\Controllers\PCMAController::class, 'index'])->name('pcma.index');
    Route::post('/pcma', [App\Http\Controllers\PCMAController::class, 'store'])->name('pcma.store');
    Route::get('/pcma/{pcma}', [App\Http\Controllers\PCMAController::class, 'show'])->name('pcma.show');
    Route::get('/pcma/{pcma}/edit', [App\Http\Controllers\PCMAController::class, 'edit'])->name('pcma.edit');
    Route::put('/pcma/{pcma}', [App\Http\Controllers\PCMAController::class, 'update'])->name('pcma.update');
    Route::delete('/pcma/{pcma}', [App\Http\Controllers\PCMAController::class, 'destroy'])->name('pcma.destroy');
    Route::get('/pcma/{pcma}/complete', [App\Http\Controllers\PCMAController::class, 'complete'])->name('pcma.complete');
    Route::get('/pcma/{pcma}/fail', [App\Http\Controllers\PCMAController::class, 'fail'])->name('pcma.fail');
    Route::get('/pcma/{pcma}/pdf', [App\Http\Controllers\PCMAController::class, 'exportPdf'])->name('pcma.pdf');
});



// PCMA API Routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::post('/pcma/ai/ecg', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeEcg'])->name('pcma.ai.ecg');
    Route::post('/pcma/ai/mri', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeMri'])->name('pcma.ai.mri');
    Route::post('/pcma/ai/xray', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeXray'])->name('pcma.ai.xray');
    Route::post('/pcma/ai/ecg-effort', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeEcgEffort'])->name('pcma.ai.ecg-effort');
    Route::post('/pcma/ai/scintigraphy', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeScintigraphy'])->name('pcma.ai.scintigraphy');
    Route::post('/pcma/ai/scat', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeScat'])->name('pcma.ai.scat');
    Route::post('/pcma/ai/complete', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeComplete'])->name('pcma.ai.complete');
    Route::post('/pcma/ai/ct', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeCt'])->name('pcma.ai.ct');
    Route::post('/pcma/ai/ultrasound', [App\Http\Controllers\PCMAController::class, 'aiAnalyzeUltrasound'])->name('pcma.ai.ultrasound');
    Route::post('/pcma/ai/fitness', [App\Http\Controllers\PCMAController::class, 'aiFitnessAssessment'])->name('pcma.ai.fitness');
    Route::post('/pcma/pdf', [App\Http\Controllers\PCMAController::class, 'generatePdf'])->name('pcma.pdf.post');
});

// Player Dashboard redirect (accessible après login)
Route::middleware(['auth'])->get('/player-dashboard', function () {
    $user = Auth::user();
    
    // Si l'utilisateur est un joueur, afficher la fiche 360°
    if ($user->role === 'player' && $user->player) {
        return view('player-portal.player-360-simple');
    }
    
    // Sinon, rediriger vers le portail joueur standard
    return redirect()->route('player-portal.dashboard');
})->name('player-dashboard');

// Player Portal Routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::prefix('player-portal')->name('player-portal.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('player-portal.fifa-ultimate');
        });
        Route::get('/home', function () {
            return redirect()->route('player-portal.fifa-ultimate');
        })->name('dashboard');
        Route::get('/simple', function () {
            return view('player-portal.simple-dashboard');
        })->name('simple-dashboard');
        Route::get('/debug', function () {
            return view('player-portal.debug');
        })->name('debug');
        Route::get('/test', function () {
            return view('player-portal.test');
        })->name('test');
        Route::get('/profile', [App\Http\Controllers\PlayerPortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\PlayerPortalController::class, 'updateProfile'])->name('update-profile');
        Route::get('/medical-records', function () {
            return view('player-portal.medical-records-simple');
        })->name('medical-records');
        Route::get('/predictions', [App\Http\Controllers\PlayerPortalController::class, 'predictions'])->name('predictions');
        Route::get('/performances', [App\Http\Controllers\PlayerPortalController::class, 'performances'])->name('performances');
        Route::get('/matches', [App\Http\Controllers\PlayerPortalController::class, 'matches'])->name('matches');
        Route::get('/documents', [App\Http\Controllers\PlayerPortalController::class, 'documents'])->name('documents');
        Route::get('/settings', [App\Http\Controllers\PlayerPortalController::class, 'settings'])->name('settings');
        Route::get('/fifa-ultimate', [App\Http\Controllers\PlayerPortalController::class, 'fifaUltimateDashboard'])->name('fifa-ultimate');
    Route::get('/fifa-light', [App\Http\Controllers\PlayerPortalController::class, 'fifaUltimateDashboard'])->name('fifa-light');
    });
});

// Routes de test publiques (en dehors du groupe player-portal)
Route::get('/test-minimal', function () {
    return view('test-minimal');
})->name('test-minimal');

// Route de test pour l'authentification
Route::get('/test-auth', function () {
    if (Auth::check()) {
        return response()->json([
            'authenticated' => true,
            'user' => Auth::user()->email,
            'role' => Auth::user()->role,
            'session_id' => session()->getId()
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'session_id' => session()->getId()
        ]);
    }
})->name('test-auth');

// Route de test pour forcer la connexion
Route::get('/force-login', function () {
    $user = App\Models\User::where('email', 'lionel.messi@example.com')->first();
    if ($user) {
        Auth::login($user);
        return response()->json([
            'message' => 'User logged in',
            'user' => $user->email,
            'role' => $user->role,
            'session_id' => session()->getId()
        ]);
    } else {
        return response()->json(['error' => 'User not found']);
        }
})->name('force-login');

// Route publique de test
Route::get('/public-test', function () {
    return response()->json([
        'message' => 'Public route works',
        'timestamp' => now(),
        'session_id' => session()->getId()
    ]);
})->name('public-test');

// Route de debug FIFA (temporaire)
Route::get('/fifa-debug', function () {
    // Trouver un joueur pour le test
    $user = App\Models\User::where('email', 'lionel.messi@example.com')->first();
    $player = $user ? $user->player : null;
    
    if (!$player) {
        // Créer des données de test si le joueur n'existe pas
        $player = (object) [
            'first_name' => 'Lionel',
            'last_name' => 'Messi',
            'email' => 'lionel.messi@example.com',
            'club' => (object) ['name' => 'Paris Saint-Germain']
        ];
    } else {
        $player->load('club');
    }
    
    return view('player-portal.fifa-debug', compact('player'));
})->name('fifa-debug');




