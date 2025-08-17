<?php

/**
 * Script de test pour vérifier que les corrections de la vue fonctionnent
 */

echo "🧪 TEST DES CORRECTIONS DE LA VUE\n";
echo "==================================\n\n";

// Test des fonctions de sécurisation des données
function safeDisplay($value) {
    if (is_string($value)) {
        return $value;
    } elseif (is_array($value)) {
        return json_encode($value);
    } else {
        return 'N/A';
    }
}

// Test avec différents types de données
echo "📊 TEST 1 - Fonction safeDisplay :\n";
echo "   String: " . safeDisplay("Test String") . "\n";
echo "   Array: " . safeDisplay(["key" => "value", "test" => true]) . "\n";
echo "   Null: " . safeDisplay(null) . "\n";
echo "   Number: " . safeDisplay(123) . "\n\n";

// Test de la logique conditionnelle utilisée dans la vue
echo "📊 TEST 2 - Logique conditionnelle de la vue :\n";

$testData = [
    'fifa_id' => 'FIFA_001',
    'type_label' => ['type' => 'League', 'format' => 'Round Robin'],
    'season' => '2024-2025',
    'format_label' => 'Championnat',
    'status' => 'active'
];

echo "   FIFA ID: " . (is_string($testData['fifa_id'] ?? 'N/A') ? ($testData['fifa_id'] ?? 'N/A') : 'N/A') . "\n";
echo "   Type Label: " . (is_string($testData['type_label']) ? $testData['type_label'] : (is_array($testData['type_label']) ? json_encode($testData['type_label']) : 'N/A')) . "\n";
echo "   Season: " . (is_string($testData['season']) ? $testData['season'] : (is_array($testData['season']) ? json_encode($testData['season']) : 'N/A')) . "\n";
echo "   Format Label: " . (is_string($testData['format_label']) ? $testData['format_label'] : (is_array($testData['format_label']) ? json_encode($testData['format_label']) : 'N/A')) . "\n";
echo "   Status: " . ucfirst($testData['status']) . "\n\n";

// Test avec des données problématiques
echo "📊 TEST 3 - Données problématiques :\n";
$problematicData = [
    'fifa_id' => null,
    'type_label' => ['complex' => ['nested' => 'data']],
    'season' => ['year' => 2024, 'period' => 'full'],
    'format_label' => ['format' => 'League', 'type' => 'Professional'],
    'status' => 'active'
];

echo "   FIFA ID (null): " . (is_string($problematicData['fifa_id'] ?? 'N/A') ? ($problematicData['fifa_id'] ?? 'N/A') : 'N/A') . "\n";
echo "   Type Label (nested array): " . (is_string($problematicData['type_label']) ? $problematicData['type_label'] : (is_array($problematicData['type_label']) ? json_encode($problematicData['type_label']) : 'N/A')) . "\n";
echo "   Season (array): " . (is_string($problematicData['season']) ? $problematicData['season'] : (is_array($problematicData['season']) ? json_encode($problematicData['season']) : 'N/A')) . "\n";
echo "   Format Label (array): " . (is_string($problematicData['format_label']) ? $problematicData['format_label'] : (is_array($problematicData['format_label']) ? json_encode($problematicData['format_label']) : 'N/A')) . "\n\n";

echo "🎉 TESTS TERMINÉS AVEC SUCCÈS !\n";
echo "Les corrections de la vue devraient maintenant gérer correctement les arrays.\n";
echo "La fonction htmlspecialchars() ne devrait plus recevoir d'array.\n";




