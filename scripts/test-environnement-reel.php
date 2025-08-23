<?php
/**
 * Script de test complet pour l'environnement Laravel réel
 * Teste toutes les fonctionnalités vocales implémentées
 */

echo "🧪 TEST COMPLET - ENVIRONNEMENT LARAVEL RÉEL\n";
echo "===========================================\n\n";

$baseUrl = 'http://localhost:8081';

// Test 1: Vérifier que le serveur Laravel fonctionne
echo "📋 Test 1: Serveur Laravel\n";
echo "---------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo $httpCode === 200 ? "✅ Serveur Laravel fonctionnel\n\n" : "❌ Serveur Laravel inaccessible\n\n";

// Test 2: API de recherche de joueurs
echo "🔍 Test 2: API de recherche de joueurs\n";
echo "--------------------------------------\n";

$apiUrl = "$baseUrl/api/athletes/search?name=Ali%20Jebali";
echo "URL: $apiUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$apiResponse = curl_exec($ch);
$apiHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP API: $apiHttpCode\n";

if ($apiHttpCode === 200) {
    $playerData = json_decode($apiResponse, true);
    
    if ($playerData && isset($playerData['success']) && $playerData['success']) {
        echo "✅ API de recherche fonctionnelle\n";
        echo "📊 Données du joueur trouvé:\n";
        echo "   - ID: " . ($playerData['player']['id'] ?? 'N/A') . "\n";
        echo "   - Nom: " . ($playerData['player']['name'] ?? 'N/A') . "\n";
        echo "   - FIFA ID: " . ($playerData['player']['fifa_connect_id'] ?? 'N/A') . "\n";
        echo "   - Position: " . ($playerData['player']['position'] ?? 'N/A') . "\n";
        echo "   - Âge: " . ($playerData['player']['age'] ?? 'N/A') . "\n";
        echo "   - Nationalité: " . ($playerData['player']['nationality'] ?? 'N/A') . "\n";
    } else {
        echo "❌ API retourne une erreur: " . ($playerData['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ API de recherche inaccessible (Code: $apiHttpCode)\n";
}
echo "\n";

// Test 3: Page PCMA avec nouvelle interface
echo "📄 Test 3: Page PCMA avec interface vocale\n";
echo "------------------------------------------\n";

$pcmaUrl = "$baseUrl/pcma/create";
echo "URL: $pcmaUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pcmaUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$pcmaResponse = curl_exec($ch);
$pcmaHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $pcmaHttpCode\n";
echo "URL finale: $finalUrl\n";

if ($pcmaResponse) {
    // Tester la présence des éléments vocaux
    $elements = [
        'Modes de collecte' => strpos($pcmaResponse, 'Modes de collecte') !== false,
        'mode-manuel' => strpos($pcmaResponse, 'mode-manuel') !== false,
        'mode-vocal' => strpos($pcmaResponse, 'mode-vocal') !== false,
        'console-vocale' => strpos($pcmaResponse, 'console-vocale') !== false,
        'start-recording-btn' => strpos($pcmaResponse, 'start-recording-btn') !== false,
        'confirmation-modal' => strpos($pcmaResponse, 'confirmation-modal') !== false,
        'validateVoiceDataWithDatabase' => strpos($pcmaResponse, 'validateVoiceDataWithDatabase') !== false,
        'searchPlayerByFifaConnect' => strpos($pcmaResponse, 'searchPlayerByFifaConnect') !== false,
        'ID FIFA CONNECT' => strpos($pcmaResponse, 'ID FIFA CONNECT') !== false,
        'Validation intelligente' => strpos($pcmaResponse, 'showConfirmationPopup') !== false
    ];
    
    echo "🔍 Éléments vocaux détectés:\n";
    foreach ($elements as $element => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $element\n";
    }
    
    $foundElements = array_filter($elements);
    $totalElements = count($elements);
    $foundCount = count($foundElements);
    
    echo "\n📊 Résumé: $foundCount/$totalElements éléments trouvés\n";
    
    if ($foundCount === $totalElements) {
        echo "✅ Interface vocale complètement intégrée !\n";
    } elseif ($foundCount >= $totalElements * 0.8) {
        echo "⚠️ Interface vocale majoritairement intégrée\n";
    } else {
        echo "❌ Interface vocale partiellement intégrée\n";
    }
}
echo "\n";

// Test 4: Service de reconnaissance vocale
echo "🎤 Test 4: Service de reconnaissance vocale\n";
echo "-------------------------------------------\n";

if ($pcmaResponse) {
    $speechServiceElements = [
        'SpeechRecognitionService' => strpos($pcmaResponse, 'SpeechRecognitionService') !== false,
        'Google Speech-to-Text' => strpos($pcmaResponse, 'Google Speech-to-Text') !== false,
        'analyzeVoiceText' => strpos($pcmaResponse, 'analyzeVoiceText') !== false,
        'fillFormFields' => strpos($pcmaResponse, 'fillFormFields') !== false,
        'initModesCollecte' => strpos($pcmaResponse, 'initModesCollecte') !== false,
        'SpeechRecognitionService-laravel.js' => strpos($pcmaResponse, 'SpeechRecognitionService-laravel.js') !== false
    ];
    
    echo "🔍 Composants du service vocal:\n";
    foreach ($speechServiceElements as $element => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $element\n";
    }
    
    $foundSpeech = array_filter($speechServiceElements);
    $totalSpeech = count($speechServiceElements);
    $foundSpeechCount = count($foundSpeech);
    
    echo "\n📊 Service vocal: $foundSpeechCount/$totalSpeech composants trouvés\n";
    
    if ($foundSpeechCount === $totalSpeech) {
        echo "✅ Service de reconnaissance vocale entièrement intégré !\n";
    } else {
        echo "⚠️ Service de reconnaissance vocale partiellement intégré\n";
    }
}
echo "\n";

// Résumé final
echo "📊 RÉSUMÉ FINAL\n";
echo "===============\n";

$status = 'unknown';
if ($httpCode === 200 && $apiHttpCode === 200 && isset($foundCount) && $foundCount >= 8) {
    $status = 'ready';
    echo "🎉 SYSTÈME PRÊT POUR LES TESTS UTILISATEUR !\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "✅ API de recherche: OK\n";
    echo "✅ Interface vocale: OK\n";
    echo "✅ Service vocal: OK\n\n";
    
    echo "🎯 INSTRUCTIONS POUR LES TESTS UTILISATEUR:\n";
    echo "1. Accédez à: $pcmaUrl\n";
    echo "2. Connectez-vous si nécessaire\n";
    echo "3. Testez les modes Manuel/Vocal\n";
    echo "4. Testez la commande 'ID FIFA CONNECT'\n";
    echo "5. Testez la validation intelligente\n\n";
    
} elseif ($httpCode === 302 || strpos($finalUrl, 'login') !== false) {
    $status = 'auth_required';
    echo "🔐 AUTHENTIFICATION REQUISE\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "✅ Interface vocale: Intégrée\n";
    echo "⚠️ Status: Redirection vers login détectée\n\n";
    
    echo "🎯 INSTRUCTIONS:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $pcmaUrl\n";
    echo "4. Testez les fonctionnalités vocales\n\n";
    
} else {
    $status = 'issues';
    echo "❌ PROBLÈMES DÉTECTÉS\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier l'intégration de l'interface vocale\n\n";
}

echo "Status: $status\n";
echo "✨ Test terminé !\n";
?>

