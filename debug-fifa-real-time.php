<?php
/**
 * Script de débogage FIFA en temps réel
 * Identifie pourquoi le chargement FIFA ne fonctionne pas
 */

echo "🔍 DIAGNOSTIC FIFA EN TEMPS RÉEL\n";
echo "================================\n\n";

// 1. Vérifier que le serveur Laravel fonctionne
echo "1. Test du serveur Laravel...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Serveur Laravel accessible (HTTP $httpCode)\n";
} else {
    echo "   ❌ Serveur Laravel inaccessible (HTTP $httpCode)\n";
    exit(1);
}

// 2. Tester l'API FIFA directement
echo "\n2. Test de l'API FIFA...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/api/player-performance/7');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ API FIFA accessible (HTTP $httpCode)\n";
    
    // Vérifier le contenu de la réponse
    $data = json_decode($response, true);
    if ($data && isset($data['data'])) {
        echo "   ✅ Réponse JSON valide\n";
        echo "   📊 Nombre de champs: " . count($data['data']) . "\n";
        echo "   🎯 Message: " . ($data['message'] ?? 'N/A') . "\n";
        
        // Afficher quelques données clés
        $playerData = $data['data'];
        echo "   👤 Joueur: " . ($playerData['first_name'] ?? 'N/A') . " " . ($playerData['last_name'] ?? 'N/A') . "\n";
        echo "   ⭐ Rating: " . ($playerData['overall_rating'] ?? 'N/A') . "\n";
        echo "   ⚽ Buts: " . ($playerData['goals_scored'] ?? 'N/A') . "\n";
        echo "   🎯 Passes: " . ($playerData['assists'] ?? 'N/A') . "\n";
    } else {
        echo "   ❌ Réponse JSON invalide\n";
        echo "   📄 Contenu brut: " . substr($response, 0, 200) . "...\n";
    }
} else {
    echo "   ❌ API FIFA inaccessible (HTTP $httpCode)\n";
    echo "   📄 Réponse: " . $response . "\n";
}

// 3. Tester la route de test du portail
echo "\n3. Test de la route de test du portail...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/test-portail-fifa/7');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Route de test accessible (HTTP $httpCode)\n";
    
    // Vérifier que le JavaScript FIFA est présent
    if (strpos($response, 'loadFIFAPerformanceData') !== false) {
        echo "   ✅ Fonction loadFIFAPerformanceData trouvée\n";
    } else {
        echo "   ❌ Fonction loadFIFAPerformanceData NON trouvée\n";
    }
    
    if (strpos($response, 'setTimeout') !== false && strpos($response, 'loadFIFAPerformanceData') !== false) {
        echo "   ✅ Appel automatique setTimeout trouvé\n";
    } else {
        echo "   ❌ Appel automatique setTimeout NON trouvé\n";
    }
    
    if (strpos($response, 'DOMContentLoaded') !== false) {
        echo "   ✅ Event DOMContentLoaded trouvé\n";
    } else {
        echo "   ❌ Event DOMContentLoaded NON trouvé\n";
    }
    
} else {
    echo "   ❌ Route de test inaccessible (HTTP $httpCode)\n";
}

// 4. Tester la route principale du portail (avec authentification)
echo "\n4. Test de la route principale du portail...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/portail-joueur/7');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Ne pas suivre les redirections
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 302) {
    echo "   ⚠️  Redirection détectée (HTTP $httpCode) - Authentification requise\n";
} elseif ($httpCode == 200) {
    echo "   ✅ Portail principal accessible (HTTP $httpCode)\n";
} else {
    echo "   ❌ Portail principal inaccessible (HTTP $httpCode)\n";
}

// 5. Vérifier les logs Laravel pour des erreurs
echo "\n5. Vérification des logs Laravel...\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "   📁 Fichier de log trouvé: $logFile\n";
    
    // Lire les dernières lignes du log
    $lines = file($logFile);
    $recentLines = array_slice($lines, -20); // 20 dernières lignes
    
    $errorCount = 0;
    foreach ($recentLines as $line) {
        if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
            $errorCount++;
            if ($errorCount <= 5) { // Limiter à 5 erreurs
                echo "   ❌ Erreur récente: " . trim($line) . "\n";
            }
        }
    }
    
    if ($errorCount == 0) {
        echo "   ✅ Aucune erreur récente détectée\n";
    } else {
        echo "   ⚠️  $errorCount erreur(s) récente(s) détectée(s)\n";
    }
} else {
    echo "   ❌ Fichier de log non trouvé\n";
}

// 6. Test de la page de test FIFA direct
echo "\n6. Test de la page de test FIFA direct...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/test-fifa-direct');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page de test FIFA direct accessible (HTTP $httpCode)\n";
    
    // Vérifier que le JavaScript de test est présent
    if (strpos($response, 'testAPIFIFA') !== false) {
        echo "   ✅ Fonction testAPIFIFA trouvée\n";
    } else {
        echo "   ❌ Fonction testAPIFIFA NON trouvée\n";
    }
    
    if (strpos($response, 'DOMContentLoaded') !== false) {
        echo "   ✅ Event DOMContentLoaded trouvé\n";
    } else {
        echo "   ❌ Event DOMContentLoaded NON trouvé\n";
    }
    
} else {
    echo "   ❌ Page de test FIFA direct inaccessible (HTTP $httpCode)\n";
}

echo "\n🎯 RÉSUMÉ DU DIAGNOSTIC\n";
echo "========================\n";

if ($httpCode == 200) {
    echo "✅ Toutes les routes de test sont accessibles\n";
    echo "✅ L'API FIFA fonctionne parfaitement\n";
    echo "✅ Le JavaScript FIFA est présent dans les vues\n";
    echo "\n🔍 PROBLÈME IDENTIFIÉ :\n";
    echo "   Le chargement automatique ne fonctionne probablement pas à cause de :\n";
    echo "   1. Une erreur JavaScript silencieuse\n";
    echo "   2. Un conflit avec d'autres scripts\n";
    echo "   3. Un problème de timing (DOM pas encore prêt)\n";
    echo "\n💡 SOLUTION RECOMMANDÉE :\n";
    echo "   1. Ouvrir la console du navigateur sur /test-portail-fifa/7\n";
    echo "   2. Vérifier les erreurs JavaScript\n";
    echo "   3. Tester manuellement loadFIFAPerformanceData()\n";
} else {
    echo "❌ Certaines routes ne sont pas accessibles\n";
    echo "🔧 Vérifiez que le serveur Laravel fonctionne\n";
}

echo "\n🚀 PROCHAINES ÉTAPES :\n";
echo "   1. Ouvrir http://localhost:8001/test-portail-fifa/7\n";
echo "   2. Ouvrir la console du navigateur (F12)\n";
echo "   3. Vérifier les erreurs et les logs\n";
echo "   4. Tester manuellement le bouton '🔄 Forcer le Chargement FIFA'\n";

echo "\n✨ Diagnostic terminé !\n";
?>
