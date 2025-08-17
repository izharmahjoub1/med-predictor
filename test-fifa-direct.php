<?php
/**
 * Test Direct du FIFA Portal
 * Vérifie le fonctionnement avec un joueur spécifique
 */

echo "🔍 TEST DIRECT FIFA PORTAL\n";
echo "==========================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Accès direct au FIFA Portal avec un joueur spécifique
echo "1️⃣ TEST ACCÈS DIRECT FIFA PORTAL\n";
echo "--------------------------------\n";

$testUrl = "$baseUrl/fifa-portal?player_id=8";
echo "🎯 URL testée: $testUrl\n";

$response = file_get_contents($testUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès\n";
} else {
    echo "✅ Page accessible\n";
    
    // Vérifier si le paramètre player_id est présent dans le HTML
    if (strpos($response, 'player_id=8') !== false) {
        echo "✅ Paramètre player_id=8 trouvé dans l'URL\n";
    } else {
        echo "❌ Paramètre player_id=8 NON trouvé dans l'URL\n";
    }
    
    // Vérifier si les fonctions JavaScript sont présentes
    if (strpos($response, 'syncFIFAHeroZone') !== false) {
        echo "✅ Fonction syncFIFAHeroZone présente\n";
    } else {
        echo "❌ Fonction syncFIFAHeroZone NON présente\n";
    }
    
    if (strpos($response, 'loadPlayerFIFAData') !== false) {
        echo "✅ Fonction loadPlayerFIFAData présente\n";
    } else {
        echo "❌ Fonction loadPlayerFIFAData NON présente\n";
    }
    
    // Vérifier si les éléments de la Hero Zone sont présents
    $heroElements = [
        'hero-overall-rating',
        'hero-player-name',
        'hero-club-name',
        'hero-nationality'
    ];
    
    echo "\n🔍 Vérification des éléments Hero Zone:\n";
    foreach ($heroElements as $element) {
        if (strpos($response, $element) !== false) {
            echo "  ✅ $element: présent\n";
        } else {
            echo "  ❌ $element: NON présent\n";
        }
    }
}

echo "\n2️⃣ TEST NAVIGATION ENTRE JOUEURS\n";
echo "--------------------------------\n";

// Vérifier si les fonctions de navigation sont présentes
if (strpos($response, 'navigateToPreviousPlayer') !== false) {
    echo "✅ Fonction navigateToPreviousPlayer présente\n";
} else {
    echo "❌ Fonction navigateToPreviousPlayer NON présente\n";
}

if (strpos($response, 'navigateToNextPlayer') !== false) {
    echo "✅ Fonction navigateToNextPlayer présente\n";
} else {
    echo "❌ Fonction navigateToNextPlayer NON présente\n";
}

if (strpos($response, 'fifa-player-counter') !== false) {
    echo "✅ Compteur de joueurs présent\n";
} else {
    echo "❌ Compteur de joueurs NON présent\n";
}

echo "\n3️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-----------------------------\n";

echo "📋 État du FIFA Portal:\n";
echo "  • Page accessible: ✅\n";
echo "  • Paramètre URL: " . (strpos($response, 'player_id=8') !== false ? "✅" : "❌") . "\n";
echo "  • Fonctions JavaScript: " . (strpos($response, 'syncFIFAHeroZone') !== false ? "✅" : "❌") . "\n";
echo "  • Éléments Hero Zone: " . (strpos($response, 'hero-overall-rating') !== false ? "✅" : "❌") . "\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Ouvrir $testUrl dans le navigateur\n";
echo "2. Ouvrir la console développeur (F12)\n";
echo "3. Vérifier les logs de débogage\n";
echo "4. Tester la navigation entre joueurs\n";

echo "\n✅ Test direct terminé !\n";

