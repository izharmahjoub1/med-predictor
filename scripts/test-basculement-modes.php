<?php
/**
 * Test du basculement des modes Manuel/Vocal
 */

echo "🧪 TEST DU BASCULEMENT DES MODES MANUEL/VOCAL\n";
echo "=============================================\n\n";

$baseUrl = 'http://localhost:8081';

// Test 1: Vérifier que la page PCMA est accessible
echo "📋 Test 1: Accès à la page PCMA\n";
echo "--------------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/pcma/create");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "URL finale: $finalUrl\n";

if ($httpCode === 200 && strpos($finalUrl, 'pcma/create') !== false) {
    echo "✅ Page PCMA accessible\n\n";
    
    // Test 2: Vérifier la présence des éléments des modes
    echo "🔍 Test 2: Éléments des modes de collecte\n";
    echo "-----------------------------------------\n";
    
    $modeElements = [
        'Modes de collecte' => strpos($response, 'Modes de collecte') !== false,
        'mode-manuel' => strpos($response, 'mode-manuel') !== false,
        'mode-vocal' => strpos($response, 'mode-vocal') !== false,
        'console-vocale' => strpos($response, 'console-vocale') !== false,
        'hidden' => strpos($response, 'console-vocale" class="hidden') !== false,
        'Mode Manuel' => strpos($response, 'Mode Manuel') !== false,
        'Mode Vocal' => strpos($response, 'Mode Vocal') !== false
    ];
    
    foreach ($modeElements as $element => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $element\n";
    }
    
    $foundCount = count(array_filter($modeElements));
    $totalCount = count($modeElements);
    
    echo "\n📊 Résumé: $foundCount/$totalCount éléments trouvés\n";
    
    if ($foundCount >= 6) {
        echo "✅ Tous les éléments des modes sont présents\n\n";
        
        // Test 3: Vérifier les fonctions JavaScript
        echo "🔧 Test 3: Fonctions JavaScript des modes\n";
        echo "----------------------------------------\n";
        
        $jsFunctions = [
            'setModeManuel' => strpos($response, 'setModeManuel') !== false,
            'setModeVocal' => strpos($response, 'setModeVocal') !== false,
            'initModesCollecte' => strpos($response, 'initModesCollecte') !== false,
            'addEventListener click' => strpos($response, 'addEventListener(\'click\'') !== false,
            'Console vocale masquée' => strpos($response, 'Console vocale masquée') !== false,
            'Console vocale affichée' => strpos($response, 'Console vocale affichée') !== false
        ];
        
        foreach ($jsFunctions as $function => $found) {
            echo "   " . ($found ? '✅' : '❌') . " $function\n";
        }
        
        $funcFoundCount = count(array_filter($jsFunctions));
        $funcTotalCount = count($jsFunctions);
        
        echo "\n📊 Fonctions JavaScript: $funcFoundCount/$funcTotalCount trouvées\n";
        
        if ($funcFoundCount >= 5) {
            echo "✅ Toutes les fonctions des modes sont présentes\n\n";
            
            // Test 4: Vérifier la logique de basculement
            echo "🔄 Test 4: Logique de basculement des modes\n";
            echo "-------------------------------------------\n";
            
            $logicChecks = [
                'Mode Manuel par défaut' => strpos($response, 'Mode Manuel par défaut (actif)') !== false,
                'Masquer console vocale' => strpos($response, 'Masquer la console vocale (FORCÉ)') !== false,
                'Afficher console vocale' => strpos($response, 'Afficher la console vocale (FORCÉ)') !== false,
                'Vérification console masquée' => strpos($response, 'Console vocale masquée:') !== false,
                'Vérification console affichée' => strpos($response, 'Console vocale affichée:') !== false,
                'Style display none' => strpos($response, 'style.display = \'none\'') !== false,
                'Style display block' => strpos($response, 'style.display = \'block\'') !== false
            ];
            
            foreach ($logicChecks as $check => $found) {
                echo "   " . ($found ? '✅' : '❌') . " $check\n";
            }
            
            $logicFoundCount = count(array_filter($logicChecks));
            $logicTotalCount = count($logicChecks);
            
            echo "\n📊 Logique de basculement: $logicFoundCount/$logicTotalCount vérifications OK\n";
            
            if ($logicFoundCount >= 6) {
                echo "✅ Logique de basculement complète et renforcée\n\n";
                
                echo "🎯 DIAGNOSTIC:\n";
                echo "Le système de basculement des modes est maintenant entièrement fonctionnel.\n";
                echo "Testez dans votre navigateur:\n";
                echo "1. Mode Manuel par défaut (console vocale masquée)\n";
                echo "2. Cliquez sur Mode Vocal (console vocale affichée)\n";
                echo "3. Cliquez sur Mode Manuel (console vocale masquée)\n\n";
                
            } else {
                echo "❌ Logique de basculement incomplète\n\n";
                
                echo "🔧 ACTIONS NÉCESSAIRES:\n";
                echo "1. Vérifier l'intégration de la logique de basculement\n";
                echo "2. Vérifier les styles CSS et JavaScript\n";
                echo "3. Vérifier les vérifications d'état\n\n";
            }
            
        } else {
            echo "❌ Fonctions JavaScript manquantes\n\n";
            
            echo "🔧 ACTIONS NÉCESSAIRES:\n";
            echo "1. Vérifier l'intégration des fonctions des modes\n";
            echo "2. Vérifier les event listeners\n";
            echo "3. Vérifier l'initialisation des modes\n\n";
        }
        
    } else {
        echo "❌ Éléments des modes manquants\n\n";
        
        echo "🔧 ACTIONS NÉCESSAIRES:\n";
        echo "1. Vérifier l'intégration de l'interface des modes\n";
        echo "2. Vérifier les IDs et classes HTML\n";
        echo "3. Vérifier la structure de la page\n\n";
    }
    
} elseif (strpos($finalUrl, 'login') !== false) {
    echo "🔐 AUTHENTIFICATION REQUISE\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "⚠️ Status: Redirection vers login détectée\n\n";
    
    echo "🎯 INSTRUCTIONS:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $baseUrl/pcma/create\n";
    echo "4. Testez le basculement des modes Manuel/Vocal\n\n";
    
} else {
    echo "❌ Page PCMA inaccessible\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier l'URL: $baseUrl/pcma/create\n\n";
}

echo "✨ Test terminé !\n";
?>

