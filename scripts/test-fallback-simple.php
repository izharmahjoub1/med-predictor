<?php

/**
 * Script de test simplifié pour le fallback Whisper
 * Teste uniquement la logique de fallback sans gestion de session complexe
 */

echo "🧪 Test Simplifié du Fallback Whisper\n";
echo "=====================================\n\n";

$baseUrl = 'http://localhost:8000/api/google-assistant';
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-User-ID: 1'
];

function testFallback($name, $data) {
    global $baseUrl, $headers;
    
    echo "🔍 Test: {$name}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/webhook");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   📡 Code HTTP: {$httpCode}\n";
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "   ✅ Succès\n";
        echo "   💬 Réponse: " . ($data['fulfillmentText'] ?? 'N/A') . "\n";
        
        // Vérifier les informations de fallback
        if (isset($data['fallback_info'])) {
            echo "   🔄 Fallback détecté:\n";
            echo "      Source: " . ($data['fallback_info']['source'] ?? 'N/A') . "\n";
            echo "      Raison: " . ($data['fallback_info']['reason'] ?? 'N/A') . "\n";
        }
        
        return true;
    } else {
        echo "   ❌ Échec\n";
        echo "   📝 Réponse: " . substr($response, 0, 200) . "\n";
        return false;
    }
}

// Test 1: Démarrage PCMA normal
echo "📋 Test 1: Démarrage PCMA normal\n";
$test1 = testFallback('Démarrage PCMA normal', [
    'queryResult' => [
        'intent' => ['displayName' => 'start_pcma'],
        'parameters' => ['player_name' => 'Test Player'],
        'queryText' => 'Commence un PCMA pour Test Player'
    ],
    'session' => 'test-simple-1'
]);

echo "\n";

// Test 2: Simulation d'échec Google Assistant
echo "📋 Test 2: Échec Google Assistant (fallback Whisper)\n";
$test2 = testFallback('Échec Google Assistant', [
    'queryResult' => [
        'intent' => ['displayName' => 'start_pcma'],
        'parameters' => ['player_name' => 'Test Player Fallback'],
        'queryText' => 'Commence un PCMA pour Test Player Fallback'
    ],
    'session' => 'test-simple-2',
    'google_assistant_error' => 'recognition_failed',
    'audio_data' => 'base64_audio_test_data'
]);

echo "\n";

// Test 3: Échec complet (fallback web)
echo "📋 Test 3: Échec complet (fallback web)\n";
$test3 = testFallback('Échec complet', [
    'queryResult' => [
        'intent' => ['displayName' => 'start_pcma'],
        'parameters' => ['player_name' => 'Test Player Web'],
        'queryText' => 'Commence un PCMA pour Test Player Web'
    ],
    'session' => 'test-simple-3',
    'google_assistant_error' => 'complete_failure'
]);

echo "\n";

// Test 4: Santé du système
echo "📋 Test 4: Santé du système\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/health");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   📡 Code HTTP: {$httpCode}\n";
if ($httpCode === 200) {
    echo "   ✅ Système en bonne santé\n";
} else {
    echo "   ❌ Problème de santé\n";
}

echo "\n";
echo "📊 Résumé des Tests\n";
echo "==================\n";
echo "Test 1 (PCMA normal): " . ($test1 ? "✅" : "❌") . "\n";
echo "Test 2 (Fallback Whisper): " . ($test2 ? "✅" : "❌") . "\n";
echo "Test 3 (Fallback Web): " . ($test3 ? "✅" : "❌") . "\n";
echo "Test 4 (Santé): " . ($httpCode === 200 ? "✅" : "❌") . "\n";

echo "\n🎯 Système de Fallback:\n";
echo "   Google Assistant → Whisper → Interface Web\n";
echo "\nTest terminé.\n";
