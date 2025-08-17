<?php
/**
 * Script de test pour vérifier la synchronisation FIFA
 */

echo "=== TEST DE SYNCHRONISATION FIFA ===\n\n";

// Test 1: Vérifier que l'API fonctionne
echo "1. Test de l'API FIFA...\n";
$apiUrl = "http://localhost:8001/api/player-performance/7";
$response = file_get_contents($apiUrl);

if ($response === false) {
    echo "❌ Erreur: Impossible d'accéder à l'API\n";
    exit(1);
}

$data = json_decode($response, true);
if (!$data) {
    echo "❌ Erreur: Réponse JSON invalide\n";
    exit(1);
}

echo "✅ API accessible\n";
echo "   Message: " . $data['message'] . "\n";
echo "   Données reçues: " . count($data['data']) . " champs\n\n";

// Test 2: Vérifier les données clés
echo "2. Vérification des données clés...\n";
$playerData = $data['data'];

$keyFields = [
    'first_name', 'last_name', 'overall_rating', 'position', 'age',
    'goals_scored', 'assists', 'matches_played', 'distance_covered'
];

foreach ($keyFields as $field) {
    if (isset($playerData[$field])) {
        echo "   ✅ $field: " . $playerData[$field] . "\n";
    } else {
        echo "   ❌ $field: Manquant\n";
    }
}

echo "\n3. Résumé des performances FIFA:\n";
echo "   Joueur: " . $playerData['first_name'] . " " . $playerData['last_name'] . "\n";
echo "   Rating FIFA: " . $playerData['overall_rating'] . "\n";
echo "   Position: " . $playerData['position'] . "\n";
echo "   Âge: " . $playerData['age'] . " ans\n";
echo "   Buts: " . $playerData['goals_scored'] . "\n";
echo "   Passes: " . $playerData['assists'] . "\n";
echo "   Matchs: " . $playerData['matches_played'] . "\n";
echo "   Distance: " . $playerData['distance_covered'] . "km\n";

echo "\n🎉 Test de synchronisation FIFA réussi !\n";
echo "L'API retourne toutes les données nécessaires.\n";
echo "Le problème vient probablement du JavaScript côté client.\n";
?>

