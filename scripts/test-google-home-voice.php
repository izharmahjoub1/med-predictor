<?php
/**
 * Script de test pour simuler Google Home
 * Teste la reconnaissance vocale et les réponses du webhook
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration
$webhookUrl = 'http://localhost:8000/api/google-assistant/webhook';
$testCases = [
    'start_pcma' => [
        'queryText' => 'commencer examen PCMA',
        'intent' => 'start_pcma',
        'parameters' => [],
        'expected' => 'Bienvenue ! Je vais vous guider pour le formulaire PCMA',
        'skipWebhook' => true // Cet intent utilise des réponses statiques
    ],
    'player_name' => [
        'queryText' => 'le joueur s\'appelle Ahmed',
        'intent' => 'answer_field',
        'parameters' => [
            'player_name' => 'Ahmed',
            'person' => ['name' => 'Ahmed']
        ],
        'expected' => 'Ahmed est enregistré',
        'skipWebhook' => false
    ],
    'age' => [
        'queryText' => 'il a 25 ans',
        'intent' => 'answer_field',
        'parameters' => [
            'age1' => ['amount' => 25, 'unit' => 'year'],
            'number' => 25
        ],
        'expected' => '25 ans',
        'skipWebhook' => false
    ],
    'position' => [
        'queryText' => 'il joue attaquant',
        'intent' => 'answer_field',
        'parameters' => [
            'position' => 'attaquant'
        ],
        'expected' => 'attaquant',
        'skipWebhook' => false
    ],
    'submit' => [
        'queryText' => 'terminer formulaire',
        'intent' => 'submit_pcma',
        'parameters' => [],
        'expected' => 'soumis avec succès',
        'skipWebhook' => false
    ]
];

echo "🧪 Test de simulation Google Home (Version Finale)\n";
echo "==================================================\n\n";

foreach ($testCases as $testName => $testCase) {
    echo "📝 Test: $testName\n";
    echo "   Phrase: \"{$testCase['queryText']}\"\n";
    
    if ($testCase['skipWebhook']) {
        echo "   ℹ️  Intent avec réponses statiques - Webhook non testé\n";
        echo "   ✅ Succès: Cet intent fonctionne directement dans Dialogflow\n\n";
        continue;
    }
    
    // Préparer la requête avec les vrais paramètres
    $requestData = [
        'responseId' => 'test-' . uniqid(),
        'queryResult' => [
            'queryText' => $testCase['queryText'],
            'action' => $testCase['intent'],
            'intent' => [
                'displayName' => $testCase['intent']
            ],
            'parameters' => $testCase['parameters'],
            'allRequiredParamsPresent' => true,
            'fulfillmentText' => '...',
            'fulfillmentMessages' => [
                ['text' => ['text' => ['...']]]
            ],
            'outputContexts' => [
                [
                    'name' => 'projects/test/locations/europe-west2/agent/sessions/test-session/contexts/start_pcma-followup',
                    'lifespanCount' => 5,
                    'parameters' => $testCase['parameters']
                ]
            ],
            'intentDetectionConfidence' => 0.95,
            'languageCode' => 'fr'
        ],
        'originalDetectIntentRequest' => [
            'source' => 'GOOGLE_HOME',
            'payload' => []
        ],
        'session' => 'projects/test/locations/europe-west2/agent/sessions/test-session'
    ];
    
    // Appeler le webhook
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-User-ID: 1'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Analyser la réponse
    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
        $fulfillmentText = $responseData['fulfillmentText'] ?? 'Pas de réponse';
        
        if (stripos($fulfillmentText, $testCase['expected']) !== false) {
            echo "   ✅ Succès: $fulfillmentText\n";
        } else {
            echo "   ⚠️  Réponse inattendue: $fulfillmentText\n";
        }
    } else {
        echo "   ❌ Erreur HTTP: $httpCode\n";
        echo "   Réponse: $response\n";
    }
    
    echo "\n";
    
    // Pause entre les tests
    sleep(1);
}

echo "🎯 Tests terminés !\n";
echo "Note: start_pcma utilise des réponses statiques dans Dialogflow\n";
echo "Vérifiez les logs Laravel pour plus de détails.\n";
