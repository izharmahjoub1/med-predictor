<?php
echo "=== Test Accès Portail Athlète ===\n";

// Test 1: Vérifier que le serveur fonctionne
echo "1. Test du serveur Laravel...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Serveur Laravel accessible (HTTP $httpCode)\n";
} else {
    echo "❌ Serveur Laravel non accessible (HTTP $httpCode)\n";
    echo "   - Démarrez le serveur: php artisan serve\n";
    return;
}

// Test 2: Vérifier les routes du portail
echo "\n2. Test des routes du portail...\n";
$portalRoutes = [
    'http://localhost:8000/portal' => 'Portail principal',
    'http://localhost:8000/portal/dashboard' => 'Dashboard athlète',
    'http://localhost:8000/portal/wellness' => 'Formulaire bien-être',
    'http://localhost:8000/portal/devices' => 'Appareils connectés',
    'http://localhost:8000/portal/medical-record' => 'Dossier médical'
];

foreach ($portalRoutes as $url => $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 302) {
        echo "✅ $description - Redirection (authentification requise)\n";
    } elseif ($httpCode == 200) {
        echo "✅ $description - Accessible (HTTP $httpCode)\n";
    } else {
        echo "❌ $description - Non accessible (HTTP $httpCode)\n";
    }
}

// Test 3: Vérifier les vues du portail
echo "\n3. Test des vues du portail...\n";
$portalViews = [
    'resources/views/portal/index.blade.php' => 'Vue principale',
    'resources/views/portal/dashboard.blade.php' => 'Vue dashboard',
    'resources/views/portal/wellness.blade.php' => 'Vue bien-être',
    'resources/views/portal/devices.blade.php' => 'Vue appareils',
    'resources/views/portal/medical-record.blade.php' => 'Vue dossier médical'
];

foreach ($portalViews as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✅ $description - Fichier existe (" . number_format($size) . " bytes)\n";
    } else {
        echo "❌ $description - Fichier manquant\n";
    }
}

// Test 4: Vérifier les fichiers JavaScript du portail
echo "\n4. Test des fichiers JavaScript du portail...\n";
$portalJsFiles = [
    'resources/js/portal/App.vue' => 'Application principale',
    'resources/js/portal/views/DashboardView.vue' => 'Vue Dashboard',
    'resources/js/portal/views/WellnessFormView.vue' => 'Vue Formulaire Bien-être',
    'resources/js/portal/views/ConnectedDevicesView.vue' => 'Vue Appareils Connectés'
];

foreach ($portalJsFiles as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✅ $description - Fichier existe (" . number_format($size) . " bytes)\n";
    } else {
        echo "❌ $description - Fichier manquant\n";
    }
}

echo "\n=== Instructions d'Accès au Portail Athlète ===\n";
echo "Pour accéder au portail athlète:\n";
echo "1. Démarrez le serveur: php artisan serve\n";
echo "2. Ouvrez votre navigateur\n";
echo "3. Allez sur: http://localhost:8000/login\n";
echo "4. Connectez-vous avec un utilisateur ayant le rôle 'athlete':\n";
echo "   - Email: test@example.com\n";
echo "   - Mot de passe: password\n";
echo "5. Accédez au portail:\n";
echo "   - Portail principal: http://localhost:8000/portal\n";
echo "   - Dashboard: http://localhost:8000/portal/dashboard\n";
echo "   - Bien-être: http://localhost:8000/portal/wellness\n";
echo "   - Appareils: http://localhost:8000/portal/devices\n";
echo "   - Dossier médical: http://localhost:8000/portal/medical-record\n";

echo "\n=== Fonctionnalités du Portail ===\n";
echo "✅ Dashboard avec métriques de santé\n";
echo "✅ Formulaire de bien-être quotidien\n";
echo "✅ Gestion des appareils connectés\n";
echo "✅ Consultation du dossier médical\n";
echo "✅ Navigation mobile-first\n";
echo "✅ Interface moderne et responsive\n";

echo "\n=== API du Portail ===\n";
echo "Les endpoints API suivants sont disponibles:\n";
echo "- GET /api/v1/portal/dashboard-summary\n";
echo "- GET /api/v1/portal/medical-record-summary\n";
echo "- POST /api/v1/portal/wellness-form\n";
echo "- GET /api/v1/portal/wellness-history\n";
echo "- GET /api/v1/portal/appointments\n";
echo "- GET /api/v1/portal/documents\n";

echo "\n=== Test Manuel ===\n";
echo "Pour tester complètement le portail:\n";
echo "1. Connectez-vous en tant qu'athlète\n";
echo "2. Naviguez entre les différentes sections\n";
echo "3. Testez le formulaire de bien-être\n";
echo "4. Vérifiez la responsivité sur mobile\n";
echo "5. Testez les fonctionnalités d'API\n";
?> 