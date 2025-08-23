<?php

/**
 * Script de test pour Google Assistant FIT
 * 
 * Ce script simule les requêtes Google Assistant pour tester
 * l'intégration PCMA vocale
 */

echo "🧪 Test de Google Assistant FIT\n";
echo "===============================\n\n";

$baseUrl = 'http://localhost:8000/google-assistant';
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'User-Agent: Google-Assistant/1.0',
    'X-User-ID: 1' // ID utilisateur de test
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
        
        return true;
    } else {
        echo "   ❌ Échec\n";
        if (isset($result['data']['message'])) {
            echo "   📝 Message: " . $result['data']['message'] . "\n";
        }
        return false;
    }
}

echo "🚀 Tests des Endpoints Google Assistant\n";
echo "======================================\n\n";

// Test 1: Endpoint de santé
testEndpoint('Santé Google Assistant', 'GET', "{$baseUrl}/health");

echo "\n";

// Test 2: Démarrage d'une session PCMA
$startPcmaData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'start_pcma'
        ],
        'parameters' => [
            'player_name' => 'Mohamed Salah'
        ],
        'queryText' => 'Commence un PCMA pour Mohamed Salah'
    ],
    'session' => 'test-session-123'
];

testEndpoint('Démarrage PCMA', 'POST', "{$baseUrl}/webhook", $startPcmaData);

echo "\n";

// Test 3: Réponse au champ poste
$posteAnswerData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'answer_field'
        ],
        'queryText' => 'défenseur',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'poste',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Réponse poste', 'POST', "{$baseUrl}/webhook", $posteAnswerData);

echo "\n";

// Test 4: Réponse au champ âge
$ageAnswerData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'answer_field'
        ],
        'queryText' => '25 ans',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'age',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Réponse âge', 'POST', "{$baseUrl}/webhook", $ageAnswerData);

echo "\n";

// Test 5: Réponse au champ antécédents
$antecedentsAnswerData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'answer_field'
        ],
        'queryText' => 'non',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'antecedents',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Réponse antécédents', 'POST', "{$baseUrl}/webhook", $antecedentsAnswerData);

echo "\n";

// Test 6: Réponse au champ dernière blessure
$blessureAnswerData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'answer_field'
        ],
        'queryText' => 'hier',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'derniere_blessure',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Réponse dernière blessure', 'POST', "{$baseUrl}/webhook", $blessureAnswerData);

echo "\n";

// Test 7: Réponse au champ statut
$statutAnswerData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'answer_field'
        ],
        'queryText' => 'apte',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'statut',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Réponse statut', 'POST', "{$baseUrl}/webhook", $statutAnswerData);

echo "\n";

// Test 8: Finalisation du PCMA
$completePcmaData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'complete_pcma'
        ],
        'queryText' => 'oui',
        'outputContexts' => [
            [
                'name' => 'test-session-123/contexts/pcma_session',
                'parameters' => [
                    'session_id' => 'test-session-123',
                    'current_field' => 'complete',
                    'player_name' => 'Mohamed Salah'
                ]
            ]
        ]
    ],
    'session' => 'test-session-123'
];

testEndpoint('Finalisation PCMA', 'POST', "{$baseUrl}/webhook", $completePcmaData);

echo "\n";

// Test 9: Intent inconnu
$unknownIntentData = [
    'queryResult' => [
        'intent' => [
            'displayName' => 'unknown_intent'
        ],
        'queryText' => 'bonjour comment allez-vous'
    ],
    'session' => 'test-session-456'
];

testEndpoint('Intent inconnu', 'POST', "{$baseUrl}/webhook", $unknownIntentData);

echo "\n";

echo "📊 Résumé des Tests Google Assistant\n";
echo "===================================\n";
echo "Tests terminés. Vérifiez les logs pour plus de détails.\n";
echo "\n";
echo "🎯 Prochaines étapes:\n";
echo "1. Configurer Google Actions avec le webhook: {$baseUrl}/webhook\n";
echo "2. Tester avec un vrai Google Home\n";
echo "3. Vérifier les logs dans storage/logs/laravel.log\n";
echo "4. Consulter la table voice_sessions en base de données\n";
echo "\n";
echo "Test terminé.\n";
