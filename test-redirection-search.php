<?php
/**
 * Test de la Redirection de la Barre de Recherche FIFA Portal
 */

echo "🔍 TEST REDIRECTION BARRE DE RECHERCHE FIFA PORTAL\n";
echo "==================================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Vérifier que la fonction selectPlayer redirige vers le bon URL
echo "1️⃣ TEST REDIRECTION SELECTPLAYER\n";
echo "--------------------------------\n";

$testUrl = "$baseUrl/fifa-portal";
echo "🎯 URL testée: $testUrl\n";

$response = file_get_contents($testUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès\n";
} else {
    echo "✅ Page accessible\n";
    
    // Vérifier si la fonction selectPlayer redirige vers le bon URL
    if (strpos($response, '/fifa-portal?player_id=${playerId}') !== false) {
        echo "✅ Redirection FIFA Portal correcte\n";
    } elseif (strpos($response, '/portail-joueur/${playerId}') !== false) {
        echo "❌ Redirection encore vers l'ancien portail\n";
    } else {
        echo "❓ Redirection non trouvée dans le code\n";
    }
    
    // Vérifier si la fonction selectPlayer est présente
    if (strpos($response, 'function selectPlayer') !== false) {
        echo "✅ Fonction selectPlayer présente\n";
    } else {
        echo "❌ Fonction selectPlayer NON présente\n";
    }
}

echo "\n2️⃣ TEST FONCTIONNEMENT RECHERCHE\n";
echo "--------------------------------\n";

// Vérifier si les éléments de recherche sont présents
if (strpos($response, 'fifa-player-search') !== false) {
    echo "✅ Barre de recherche présente\n";
} else {
    echo "❌ Barre de recherche NON présente\n";
}

if (strpos($response, 'fifa-search-results') !== false) {
    echo "✅ Container résultats de recherche présent\n";
} else {
    echo "❌ Container résultats de recherche NON présent\n";
}

if (strpos($response, 'performFIFASearch') !== false) {
    echo "✅ Fonction de recherche présente\n";
} else {
    echo "❌ Fonction de recherche NON présente\n";
}

echo "\n3️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-----------------------------\n";

echo "📋 État de la Barre de Recherche:\n";
echo "  • Page accessible: ✅\n";
echo "  • Redirection FIFA Portal: " . (strpos($response, '/fifa-portal?player_id=${playerId}') !== false ? "✅" : "❌") . "\n";
echo "  • Fonction selectPlayer: " . (strpos($response, 'function selectPlayer') !== false ? "✅" : "❌") . "\n";
echo "  • Éléments de recherche: " . (strpos($response, 'fifa-player-search') !== false ? "✅" : "❌") . "\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Ouvrir $testUrl dans le navigateur\n";
echo "2. Taper un nom de joueur dans la barre de recherche\n";
echo "3. Cliquer sur un résultat\n";
echo "4. Vérifier que la redirection se fait vers /fifa-portal?player_id=X\n";

echo "\n✅ Test de redirection terminé !\n";

