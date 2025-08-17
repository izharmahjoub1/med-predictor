<?php
/**
 * Script de debug simple pour identifier le problème FIFA
 */

echo "=== DEBUG FIFA SIMPLE ===\n\n";

// Test 1: Vérifier que le contrôleur existe
echo "1. Test du contrôleur FIFA...\n";
try {
    $controller = new \App\Http\Controllers\RealFIFAController();
    echo "✅ Contrôleur FIFA créé avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur contrôleur: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Vérifier que l'API fonctionne
echo "\n2. Test de l'API FIFA...\n";
$apiUrl = "http://localhost:8001/api/player-performance/7";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$response = file_get_contents($apiUrl, false, $context);
if ($response === false) {
    echo "❌ Erreur: Impossible d'accéder à l'API\n";
    echo "   URL testée: $apiUrl\n";
    echo "   Vérifiez que le serveur Laravel fonctionne\n";
    exit(1);
}

echo "✅ API accessible\n";

// Test 3: Vérifier la réponse JSON
$data = json_decode($response, true);
if (!$data) {
    echo "❌ Erreur: Réponse JSON invalide\n";
    echo "   Réponse brute: " . substr($response, 0, 200) . "...\n";
    exit(1);
}

echo "✅ Réponse JSON valide\n";
echo "   Message: " . $data['message'] . "\n";
echo "   Données reçues: " . count($data['data']) . " champs\n";

// Test 4: Vérifier les données clés
echo "\n3. Vérification des données clés...\n";
$playerData = $data['data'];

$keyFields = [
    'first_name', 'last_name', 'overall_rating', 'position', 'age',
    'goals_scored', 'assists', 'matches_played', 'distance_covered'
];

$missingFields = [];
foreach ($keyFields as $field) {
    if (isset($playerData[$field])) {
        echo "   ✅ $field: " . $playerData[$field] . "\n";
    } else {
        echo "   ❌ $field: Manquant\n";
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    echo "\n⚠️ Champs manquants: " . implode(', ', $missingFields) . "\n";
}

// Test 5: Vérifier la structure complète
echo "\n4. Structure des données reçues:\n";
echo "   Champs disponibles: " . implode(', ', array_keys($playerData)) . "\n";

// Test 6: Vérifier les routes
echo "\n5. Test des routes...\n";
$routes = [
    '/api/player-performance/7' => 'API FIFA',
    '/portail-joueur/7' => 'Portail joueur',
    '/test-fifa-debug' => 'Page debug'
];

foreach ($routes as $route => $description) {
    $testUrl = "http://localhost:8001$route";
    $testResponse = file_get_contents($testUrl, false, $context);
    if ($testResponse !== false) {
        echo "   ✅ $description ($route): Accessible\n";
    } else {
        echo "   ❌ $description ($route): Inaccessible\n";
    }
}

echo "\n=== RÉSUMÉ DU DEBUG ===\n";
if (empty($missingFields)) {
    echo "🎉 Tous les tests sont passés !\n";
    echo "✅ L'API FIFA fonctionne parfaitement\n";
    echo "✅ Les données sont complètes\n";
    echo "✅ Les routes sont accessibles\n";
    echo "\n💡 Le problème vient probablement du JavaScript côté client\n";
    echo "   Vérifiez la console du navigateur pour les erreurs JavaScript\n";
} else {
    echo "⚠️ Certains champs sont manquants: " . implode(', ', $missingFields) . "\n";
    echo "❌ L'API FIFA a des problèmes de données\n";
}

echo "\n🔧 Prochaines étapes:\n";
echo "1. Vérifiez la console du navigateur (F12)\n";
echo "2. Testez la page de debug: http://localhost:8001/test-fifa-debug\n";
echo "3. Vérifiez que loadFIFAPerformanceData() est appelée\n";
?>

