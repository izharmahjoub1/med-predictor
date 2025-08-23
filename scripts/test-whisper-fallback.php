<?php

/**
 * Script de test pour le fallback Whisper
 * 
 * Ce script teste le mécanisme de fallback de Google Assistant vers Whisper
 * en cas d'échec de l'assistant
 */

echo "🧪 Test du Fallback Whisper - Google Assistant FIT\n";
echo "==================================================\n\n";

$baseUrl = 'http://localhost:8000/api/google-assistant';
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'User-Agent: Google-Assistant/1.0',
    'X-User-ID: 1'
];

function makeRequest($method, $url, $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error, 'http_code' => 0];
    }
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'data' => json_decode($response, true)
    ];
}

function testEndpoint($name, $method, $url, $data = null) {
    global $headers;
    
    echo "🔍 Test: {$name}\n";
    echo "   URL: {$url}\n";
    echo "   Méthode: {$method}\n";
    
    if ($data) {
        echo "   Données: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    }
    
    $result = makeRequest($method, $url, $data, $headers);
    
    if (isset($result['error'])) {
        echo "   ❌ Erreur cURL: {$result['error']}\n";
        return false;
    }
    
    echo "   📡 Code HTTP: {$result['http_code']}\n";
    
    if ($result['http_code'] >= 200 && $result['http_code'] < 300) {
        echo "   ✅ Succès\n";
        
        if (isset($result['data']['fulfillmentText'])) {
            echo "   💬 Réponse: " . $result['data']['fulfillmentText'] . "\n";
        }
        
        // Vérifier les informations de fallback
        if (isset($result['data']['fallback_info'])) {
            $fallback = $result['data']['fallback_info'];
            echo "   🔄 Fallback détecté:\n";
            echo "      Source: " . ($fallback['source'] ?? 'N/A') . "\n";
            echo "      Raison: " . ($fallback['reason'] ?? 'N/A') . "\n";
            
            if (isset($fallback['url'])) {
                echo "      URL Web: " . $fallback['url'] . "\n";
            }
            
            if (isset($fallback['transcription'])) {
                echo "      Transcription Whisper: " . $fallback['transcription'] . "\n";
                echo "      Confiance: " . ($fallback['confidence'] ?? 'N/A') . "\n";
            }
        }
        
        return true;
    } else {
        echo "   ❌ Échec\n";
        if (isset($result['data']['message'])) {
            echo "   📝 Message: " . $result['data']['message'] . "\n";
        }
        return false;
    }
}

echo "🚀 Tests du Système de Fallback\n";
echo "================================\n\n";

// Test 1: Démarrage PCMA normal (devrait fonctionner)
echo "📋 Test 1: Démarrage PCMA normal\n";
$startPcmaData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'start_pcma'
        ],
        'parameters' => [
            'player_name' => 'Test Player'
        ],
        'queryText' => 'Commence un PCMA pour Test Player'
    ],
    'session' => 'test-session-123'
];

testEndpoint('Démarrage PCMA normal', 'POST', "{$baseUrl}/webhook", $startPcmaData);

echo "\n";

// Test 2: Simulation d'échec Google Assistant avec audio
echo "📋 Test 2: Fallback Whisper avec audio\n";
$fallbackAudioData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'start_pcma'
        ],
        'parameters' => [
            'player_name' => 'Test Player Fallback'
        ],
        'queryText' => 'Commence un PCMA pour Test Player Fallback'
    ],
    'session' => 'test-session-fallback-123',
    'audio_data' => 'base64_encoded_audio_data_here', // Simuler des données audio
    'google_assistant_error' => 'timeout_error'
];

testEndpoint('Fallback Whisper avec audio', 'POST', "{$baseUrl}/webhook", $fallbackAudioData);

echo "\n";

// Test 3: Simulation d'échec complet (fallback web)
echo "📋 Test 3: Fallback vers interface web\n";
$completeFailureData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'start_pcma'
        ],
        'parameters' => [
            'player_name' => 'Test Player Web Fallback'
        ],
        'queryText' => 'Commence un PCMA pour Test Player Web Fallback'
    ],
    'session' => 'test-session-web-fallback-123',
    'google_assistant_error' => 'complete_failure'
];

testEndpoint('Fallback vers interface web', 'POST', "{$baseUrl}/webhook", $completeFailureData);

echo "\n";

// Test 4: Test de santé du système
echo "📋 Test 4: Santé du système\n";
testEndpoint('Santé du système', 'GET', "{$baseUrl}/health");

echo "\n";

echo "📊 Résumé des Tests de Fallback\n";
echo "================================\n";
echo "Tests terminés. Vérifiez les logs pour plus de détails.\n";
echo "\n";
echo "🎯 Scénarios de Fallback testés:\n";
echo "1. ✅ PCMA normal via Google Assistant\n";
echo "2. 🔄 Fallback vers Whisper (avec audio)\n";
echo "3. 🌐 Fallback vers interface web\n";
echo "4. 💚 Vérification de santé du système\n";
echo "\n";
echo "🔄 Logique de Fallback:\n";
echo "   Google Assistant → Whisper → Interface Web\n";
echo "\n";
echo "📝 Variables d'environnement à configurer:\n";
echo "   WHISPER_ENABLED=true\n";
echo "   WHISPER_USE_OPENAI=false (ou true pour OpenAI)\n";
echo "   WHISPER_OPENAI_API_KEY=your_key (si OpenAI)\n";
echo "\n";
echo "Test terminé.\n";
