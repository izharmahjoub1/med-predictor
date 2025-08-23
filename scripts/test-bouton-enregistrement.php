<?php
/**
 * Test rapide du bouton d'enregistrement vocal
 */

echo "🧪 TEST DU BOUTON D'ENREGISTREMENT VOCAL\n";
echo "========================================\n\n";

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
    
    // Test 2: Vérifier la présence des éléments vocaux
    echo "🔍 Test 2: Éléments vocaux\n";
    echo "---------------------------\n";
    
    $elements = [
        'start-recording-btn' => strpos($response, 'start-recording-btn') !== false,
        'stop-recording-btn' => strpos($response, 'stop-recording-btn') !== false,
        'console-vocale' => strpos($response, 'console-vocale') !== false,
        'SpeechRecognitionService' => strpos($response, 'SpeechRecognitionService') !== false,
        'window.speechService' => strpos($response, 'window.speechService') !== false,
        'startVoiceRecording' => strpos($response, 'startVoiceRecording') !== false
    ];
    
    foreach ($elements as $element => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $element\n";
    }
    
    $foundCount = count(array_filter($elements));
    $totalCount = count($elements);
    
    echo "\n📊 Résumé: $foundCount/$totalCount éléments trouvés\n";
    
    if ($foundCount === $totalCount) {
        echo "✅ Tous les éléments vocaux sont présents\n\n";
        
        // Test 3: Vérifier le JavaScript
        echo "🔧 Test 3: Code JavaScript\n";
        echo "----------------------------\n";
        
        $jsChecks = [
            'Service initialisé' => strpos($response, 'SpeechRecognitionService initialisé') !== false,
            'Service global' => strpos($response, 'window.speechService = speechService') !== false,
            'Fonction startVoiceRecording' => strpos($response, 'function startVoiceRecording') !== false,
            'Event listener' => strpos($response, 'addEventListener(\'click\', startVoiceRecording)') !== false
        ];
        
        foreach ($jsChecks as $check => $found) {
            echo "   " . ($found ? '✅' : '❌') . " $check\n";
        }
        
        $jsFoundCount = count(array_filter($jsChecks));
        $jsTotalCount = count($jsChecks);
        
        echo "\n📊 JavaScript: $jsFoundCount/$jsTotalCount vérifications OK\n";
        
        if ($jsFoundCount === $jsTotalCount) {
            echo "✅ Code JavaScript correctement configuré\n\n";
            
            echo "🎯 DIAGNOSTIC:\n";
            echo "Le bouton devrait fonctionner. Si ce n'est pas le cas:\n";
            echo "1. Ouvrez la console développeur (F12)\n";
            echo "2. Rechargez la page\n";
            echo "3. Vérifiez les messages d'erreur\n";
            echo "4. Testez en mode vocal\n\n";
            
        } else {
            echo "❌ Problèmes dans le code JavaScript\n\n";
            
            echo "🔧 ACTIONS NÉCESSAIRES:\n";
            echo "1. Vérifier l'initialisation du service\n";
            echo "2. Vérifier la définition de window.speechService\n";
            echo "3. Vérifier les event listeners\n\n";
        }
        
    } else {
        echo "❌ Éléments vocaux manquants\n\n";
        
        echo "🔧 ACTIONS NÉCESSAIRES:\n";
        echo "1. Vérifier l'intégration de l'interface vocale\n";
        echo "2. Vérifier le chargement des scripts\n";
        echo "3. Vérifier les IDs des éléments HTML\n\n";
    }
    
} elseif (strpos($finalUrl, 'login') !== false) {
    echo "🔐 AUTHENTIFICATION REQUISE\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "⚠️ Status: Redirection vers login détectée\n\n";
    
    echo "🎯 INSTRUCTIONS:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $baseUrl/pcma/create\n";
    echo "4. Testez le bouton d'enregistrement\n\n";
    
} else {
    echo "❌ Page PCMA inaccessible\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier l'URL: $baseUrl/pcma/create\n\n";
}

echo "✨ Test terminé !\n";
?>

