<?php
/**
 * Script de test pour les réponses vocales optimisées
 * Teste les variantes de réponses et la personnalisation
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration
$webhookUrl = 'http://localhost:8000/api/google-assistant/webhook';

$voiceTestCases = [
    'test_1' => [
        'sequence' => [
            [
                'queryText' => 'le joueur s\'appelle Sarah',
                'intent' => 'answer_field',
                'parameters' => [
                    'player_name' => 'Sarah',
                    'person' => ['name' => 'Sarah']
                ],
                'expected' => 'name_confirmed'
            ],
            [
                'queryText' => 'elle a 22 ans',
                'intent' => 'answer_field',
                'parameters' => [
                    'age1' => ['amount' => 22, 'unit' => 'year'],
                    'number' => 22
                ],
                'expected' => 'age_confirmed'
            ],
            [
                'queryText' => 'elle joue gardienne',
                'intent' => 'answer_field',
                'parameters' => [
                    'position' => 'gardienne'
                ],
                'expected' => 'position_confirmed'
            ]
        ],
        'description' => 'Test des réponses variées pour une joueuse'
    ],
    'test_2' => [
        'sequence' => [
            [
                'queryText' => 'le joueur s\'appelle Karim',
                'intent' => 'answer_field',
                'parameters' => [
                    'player_name' => 'Karim',
                    'person' => ['name' => 'Karim']
                ],
                'expected' => 'name_confirmed'
            ],
            [
                'queryText' => 'il a 28 ans',
                'intent' => 'answer_field',
                'parameters' => [
                    'age1' => ['amount' => 28, 'unit' => 'year'],
                    'number' => 28
                ],
                'expected' => 'age_confirmed'
            ],
            [
                'queryText' => 'il joue milieu',
                'intent' => 'answer_field',
                'parameters' => [
                    'position' => 'milieu'
                ],
                'expected' => 'position_confirmed'
            ]
        ],
        'description' => 'Test des réponses variées pour un autre joueur'
    ]
];

echo "🎙️ Test d'optimisation des réponses vocales\n";
echo "==========================================\n\n";

foreach ($voiceTestCases as $testName => $testData) {
    echo "📝 {$testData['description']} ($testName)\n";
    echo "----------------------------------------\n";
    
    $sessionId = "projects/test/locations/europe-west2/agent/sessions/voice-test-$testName";
    
    foreach ($testData['sequence'] as $index => $step) {
        $stepNumber = $index + 1;
        echo "   Étape $stepNumber: \"{$step['queryText']}\"\n";
        
        // Préparer la requête
        $requestData = [
            'responseId' => 'voice-test-' . uniqid(),
            'queryResult' => [
                'queryText' => $step['queryText'],
                'intent' => [
                    'displayName' => $step['intent']
                ],
                'parameters' => $step['parameters'],
                'allRequiredParamsPresent' => true,
                'fulfillmentText' => '...',
                'fulfillmentMessages' => [
                    ['text' => ['text' => ['...']]]
                ],
                'outputContexts' => [
                    [
                        'name' => 'projects/test/locations/europe-west2/agent/sessions/voice-test-session/contexts/start_pcma-followup',
                        'lifespanCount' => 5,
                        'parameters' => $step['parameters']
                    ]
                ],
                'intentDetectionConfidence' => 0.95,
                'languageCode' => 'fr'
            ],
            'originalDetectIntentRequest' => [
                'source' => 'GOOGLE_HOME',
                'payload' => []
            ],
            'session' => $sessionId
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
            
            echo "   🎙️ Réponse: $fulfillmentText\n";
            
            // Vérifier la variabilité des réponses
            if (stripos($fulfillmentText, 'Parfait') !== false || 
                stripos($fulfillmentText, 'Très bien') !== false || 
                stripos($fulfillmentText, 'Excellent') !== false) {
                echo "   ✅ Variante de réponse détectée\n";
            }
        } else {
            echo "   ❌ Erreur HTTP: $httpCode\n";
        }
        
        sleep(1); // Pause entre les étapes
    }
    
    echo "\n";
}

echo "🎯 Tests d'optimisation vocale terminés !\n";
echo "Les réponses devraient être variées et naturelles.\n";
