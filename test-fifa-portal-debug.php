<?php
/**
 * Script de Diagnostic FIFA Portal
 * Teste toutes les fonctionnalités et identifie les problèmes
 */

echo "🔍 DIAGNOSTIC COMPLET FIFA PORTAL\n";
echo "==================================\n\n";

$baseUrl = "http://localhost:8001";

// 1. Test de l'API des joueurs
echo "1️⃣ TEST API LISTE DES JOUEURS\n";
echo "-----------------------------\n";
$playersUrl = "$baseUrl/api/players";
$playersResponse = file_get_contents($playersUrl);

if ($playersResponse === false) {
    echo "❌ Erreur HTTP lors de l'appel à $playersUrl\n";
} else {
    $playersData = json_decode($playersResponse, true);
    if (isset($playersData['success']) && $playersData['success']) {
        echo "✅ API des joueurs fonctionnelle\n";
        echo "📊 Nombre de joueurs: " . count($playersData['data']) . "\n";
        
        // Prendre le premier joueur pour les tests suivants
        $firstPlayer = $playersData['data'][0];
        $testPlayerId = $firstPlayer['id'];
        echo "🎯 Joueur de test: ID $testPlayerId - {$firstPlayer['first_name']} {$firstPlayer['last_name']}\n\n";
    } else {
        echo "❌ API des joueurs retourne une erreur\n";
        echo "📄 Réponse: " . substr($playersResponse, 0, 200) . "...\n\n";
    }
}

// 2. Test de l'API de performance des joueurs
echo "2️⃣ TEST API PERFORMANCE DES JOUEURS\n";
echo "-----------------------------------\n";
if (isset($testPlayerId)) {
    $performanceUrl = "$baseUrl/api/player-performance/$testPlayerId";
    $performanceResponse = file_get_contents($performanceUrl);
    
    if ($performanceResponse === false) {
        echo "❌ Erreur HTTP lors de l'appel à $performanceUrl\n";
    } else {
        $performanceData = json_decode($performanceResponse, true);
        if (isset($performanceData['message'])) {
            echo "✅ API de performance fonctionnelle\n";
            echo "📊 Données récupérées: " . count($performanceData['data']) . " champs\n";
            echo "🎯 Joueur: {$performanceData['data']['first_name']} {$performanceData['data']['last_name']}\n";
            echo "⭐ Rating: {$performanceData['data']['overall_rating']}\n";
            echo "💪 Fitness: {$performanceData['data']['fitness_score']}%\n";
            echo "🔥 Forme: {$performanceData['data']['form_percentage']}%\n\n";
        } else {
            echo "❌ API de performance retourne une erreur\n";
            echo "📄 Réponse: " . substr($performanceResponse, 0, 200) . "...\n\n";
        }
    }
} else {
    echo "⚠️ Impossible de tester l'API de performance (pas de joueur de test)\n\n";
}

// 3. Test de l'API de recherche
echo "3️⃣ TEST API RECHERCHE DE JOUEURS\n";
echo "--------------------------------\n";
$searchUrl = "$baseUrl/search-players?q=samir";
$searchResponse = file_get_contents($searchUrl);

if ($searchResponse === false) {
    echo "❌ Erreur HTTP lors de l'appel à $searchUrl\n";
} else {
    $searchData = json_decode($searchResponse, true);
    if (isset($searchData['data'])) {
        echo "✅ API de recherche fonctionnelle\n";
        echo "🔍 Terme recherché: 'samir'\n";
        echo "📊 Résultats trouvés: " . count($searchData['data']) . "\n";
        
        if (count($searchData['data']) > 0) {
            $firstResult = $searchData['data'][0];
            echo "🎯 Premier résultat: {$firstResult['first_name']} {$firstResult['last_name']}\n";
        }
        echo "\n";
    } else {
        echo "❌ API de recherche retourne une erreur\n";
        echo "📄 Réponse: " . substr($searchResponse, 0, 200) . "...\n\n";
    }
}

// 4. Test de la page FIFA Portal
echo "4️⃣ TEST PAGE FIFA PORTAL\n";
echo "-------------------------\n";
$fifaPortalUrl = "$baseUrl/fifa-portal";
$fifaPortalResponse = file_get_contents($fifaPortalUrl);

if ($fifaPortalResponse === false) {
    echo "❌ Erreur HTTP lors de l'accès à $fifaPortalUrl\n";
} else {
    echo "✅ Page FIFA Portal accessible\n";
    
    // Vérifier la présence d'éléments clés
    $keyElements = [
        'fifa-hero-zone' => 'Hero Zone FIFA',
        'fifa-navigation-controls' => 'Navigation Précédent/Suivant',
        'fifa-player-search' => 'Barre de recherche',
        'fifa-search-container' => 'Container de recherche',
        'fifa-player-counter' => 'Compteur de joueurs'
    ];
    
    echo "🔍 Vérification des éléments clés:\n";
    foreach ($keyElements as $element => $description) {
        if (strpos($fifaPortalResponse, $element) !== false) {
            echo "  ✅ $description: présent\n";
        } else {
            echo "  ❌ $description: manquant\n";
        }
    }
    
    // Vérifier la présence des fonctions JavaScript
    $jsFunctions = [
        'loadAllPlayers' => 'Chargement des joueurs',
        'navigateToPreviousPlayer' => 'Navigation précédent',
        'navigateToNextPlayer' => 'Navigation suivant',
        'syncFIFAHeroZone' => 'Synchronisation Hero Zone',
        'loadFIFAData' => 'Chargement données FIFA'
    ];
    
    echo "\n🔍 Vérification des fonctions JavaScript:\n";
    foreach ($jsFunctions as $function => $description) {
        if (strpos($fifaPortalResponse, $function) !== false) {
            echo "  ✅ $description: présente\n";
        } else {
            echo "  ❌ $description: manquante\n";
        }
    }
    
    echo "\n";
}

// 5. Test avec un joueur spécifique
echo "5️⃣ TEST FIFA PORTAL AVEC JOUEUR SPÉCIFIQUE\n";
echo "-------------------------------------------\n";
if (isset($testPlayerId)) {
    $fifaPortalWithPlayerUrl = "$baseUrl/fifa-portal?player_id=$testPlayerId";
    $fifaPortalWithPlayerResponse = file_get_contents($fifaPortalWithPlayerUrl);
    
    if ($fifaPortalWithPlayerResponse === false) {
        echo "❌ Erreur HTTP lors de l'accès à $fifaPortalWithPlayerUrl\n";
    } else {
        echo "✅ FIFA Portal avec joueur accessible\n";
        echo "🎯 URL testée: $fifaPortalWithPlayerUrl\n";
        
        // Vérifier que la page contient l'ID du joueur
        if (strpos($fifaPortalWithPlayerResponse, "player_id=$testPlayerId") !== false) {
            echo "✅ Paramètre player_id présent dans la page\n";
        } else {
            echo "❌ Paramètre player_id manquant dans la page\n";
        }
    }
    echo "\n";
}

// 6. Résumé et recommandations
echo "6️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-----------------------------\n";

echo "📋 État des APIs:\n";
echo "  • /api/players: " . (isset($playersData['success']) && $playersData['success'] ? "✅ OK" : "❌ KO") . "\n";
echo "  • /api/player-performance/{id}: " . (isset($performanceData['message']) ? "✅ OK" : "❌ KO") . "\n";
echo "  • /search-players: " . (isset($searchData['data']) ? "✅ OK" : "❌ KO") . "\n";

echo "\n📋 État des pages:\n";
echo "  • /fifa-portal: " . (isset($fifaPortalResponse) ? "✅ OK" : "❌ KO") . "\n";
echo "  • /fifa-portal?player_id=X: " . (isset($fifaPortalWithPlayerResponse) ? "✅ OK" : "❌ KO") . "\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Ouvrir http://localhost:8001/fifa-portal dans le navigateur\n";
echo "2. Ouvrir la console développeur (F12)\n";
echo "3. Vérifier les erreurs JavaScript\n";
echo "4. Tester la navigation entre joueurs\n";
echo "5. Vérifier que les données se chargent dans la Hero Zone\n";

echo "\n🔧 EN CAS DE PROBLÈME:\n";
echo "• Vérifier que Laravel est démarré (php artisan serve)\n";
echo "• Vérifier que la base de données est accessible\n";
echo "• Vérifier les logs Laravel (storage/logs/laravel.log)\n";
echo "• Vérifier la console du navigateur pour les erreurs JavaScript\n";

echo "\n✅ Diagnostic terminé !\n";
?>

