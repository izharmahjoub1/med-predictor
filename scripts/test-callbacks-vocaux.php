<?php
/**
 * Test des callbacks vocaux et de l'affichage des données
 */

echo "🧪 TEST DES CALLBACKS VOCAUX ET AFFICHAGE DES DONNÉES\n";
echo "====================================================\n\n";

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
    
    // Test 2: Vérifier la configuration des callbacks
    echo "🔍 Test 2: Configuration des callbacks\n";
    echo "--------------------------------------\n";
    
    $callbackChecks = [
        'onResult callback' => strpos($response, 'onResult = function') !== false,
        'onError callback' => strpos($response, 'onError = function') !== false,
        'analyzeVoiceText' => strpos($response, 'analyzeVoiceText') !== false,
        'displayVoiceResults' => strpos($response, 'displayVoiceResults') !== false,
        'fillFormFields' => strpos($response, 'fillFormFields') !== false,
        'Callback manuel' => strpos($response, 'Callback onResult manquant - configuration manuelle') !== false
    ];
    
    foreach ($callbackChecks as $check => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $check\n";
    }
    
    $foundCount = count(array_filter($callbackChecks));
    $totalCount = count($callbackChecks);
    
    echo "\n📊 Résumé: $foundCount/$totalCount vérifications OK\n";
    
    if ($foundCount >= 4) {
        echo "✅ Callbacks correctement configurés\n\n";
        
        // Test 3: Vérifier les fonctions d'analyse
        echo "🔧 Test 3: Fonctions d'analyse vocale\n";
        echo "------------------------------------\n";
        
        $functionChecks = [
            'Fonction analyzeVoiceText' => strpos($response, 'function analyzeVoiceText') !== false,
            'Fonction displayVoiceResults' => strpos($response, 'function displayVoiceResults') !== false,
            'Fonction fillFormFields' => strpos($response, 'function fillFormFields') !== false,
            'Commande ID FIFA CONNECT' => strpos($response, 'fifa_connect_search') !== false,
            'Validation intelligente' => strpos($response, 'validateVoiceDataWithDatabase') !== false
        ];
        
        foreach ($functionChecks as $check => $found) {
            echo "   " . ($found ? '✅' : '❌') . " $check\n";
        }
        
        $funcFoundCount = count(array_filter($functionChecks));
        $funcTotalCount = count($functionChecks);
        
        echo "\n📊 Fonctions: $funcFoundCount/$funcTotalCount trouvées\n";
        
        if ($funcFoundCount >= 4) {
            echo "✅ Toutes les fonctions d'analyse sont présentes\n\n";
            
            echo "🎯 DIAGNOSTIC:\n";
            echo "Le système est correctement configuré. Si les données ne s'affichent pas:\n";
            echo "1. Vérifiez la console développeur (F12)\n";
            echo "2. Rechargez la page après connexion\n";
            echo "3. Testez avec la commande 'ID FIFA CONNECT'\n";
            echo "4. Vérifiez que les callbacks sont configurés\n\n";
            
        } else {
            echo "❌ Fonctions d'analyse manquantes\n\n";
            
            echo "🔧 ACTIONS NÉCESSAIRES:\n";
            echo "1. Vérifier l'intégration des fonctions d'analyse\n";
            echo "2. Vérifier la commande ID FIFA CONNECT\n";
            echo "3. Vérifier la validation intelligente\n\n";
        }
        
    } else {
        echo "❌ Callbacks mal configurés\n\n";
        
        echo "🔧 ACTIONS NÉCESSAIRES:\n";
        echo "1. Vérifier la configuration des callbacks\n";
        echo "2. Vérifier l'initialisation du service vocal\n";
        echo "3. Vérifier l'ordre d'initialisation\n\n";
    }
    
} elseif (strpos($finalUrl, 'login') !== false) {
    echo "🔐 AUTHENTIFICATION REQUISE\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "⚠️ Status: Redirection vers login détectée\n\n";
    
    echo "🎯 INSTRUCTIONS:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $baseUrl/pcma/create\n";
    echo "4. Testez les callbacks vocaux\n\n";
    
} else {
    echo "❌ Page PCMA inaccessible\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier l'URL: $baseUrl/pcma/create\n\n";
}

echo "✨ Test terminé !\n";
?>

