<?php
/**
 * Script de test pour la gestion d'erreur
 * Teste les scénarios d'erreur et de fallback
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration
$webhookUrl = 'http://localhost:8000/api/google-assistant/webhook';

$errorTestCases = [
    'low_confidence' => [
        'queryText' => 'blabla incompréhensible',
        'intent' => 'Default Fallback Intent',
        'intentDetectionConfidence' => 0.2,
        'description' => 'Test de reconnaissance faible'
    ],
    'fallback_intent' => [
        'queryText' => 'je ne sais pas quoi dire',
        'intent' => 'Default Fallback Intent',
        'intentDetectionConfidence' => 0.8,
        'description' => 'Test intent fallback'
    ],
    'multiple_errors' => [
        'queryText' => 'erreur répétée',
        'intent' => 'Default Fallback Intent',
        'intentDetectionConfidence' => 0.1,
        'description' => 'Test erreurs multiples pour déclencher fallback web'
    ]
];

echo "🧪 Test de gestion d'erreur Google Assistant\n";
echo "==========================================\n\n";

$sessionId = 'projects/test/locations/europe-west2/agent/sessions/error-test-session';

foreach ($errorTestCases as $testName => $testCase) {
    echo "📝 Test: {$testCase['description']} ($testName)\n";
    echo "   Phrase: \"{$testCase['queryText']}\"\n";
    echo "   Confiance: {$testCase['intentDetectionConfidence']}\n";
    
    // Répéter le test 4 fois pour déclencher le fallback web
    $iterations = ($testName === 'multiple_errors') ? 4 : 1;
    
    for ($i = 1; $i <= $iterations; $i++) {
        echo "   Tentative $i/$iterations\n";
        
        // Préparer la requête d'erreur
        $requestData = [
            'responseId' => 'error-test-' . uniqid(),
            'queryResult' => [
                'queryText' => $testCase['queryText'],
                'intent' => [
                    'displayName' => $testCase['intent']
                ],
                'parameters' => [],
                'allRequiredParamsPresent' => true,
                'fulfillmentText' => '...',
                'fulfillmentMessages' => [
                    ['text' => ['text' => ['...']]]
                ],
                'outputContexts' => [],
                'intentDetectionConfidence' => $testCase['intentDetectionConfidence'],
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
            
            if (stripos($fulfillmentText, 'interface web') !== false) {
                echo "   🔄 Fallback web activé: $fulfillmentText\n";
            } elseif (stripos($fulfillmentText, 'aide') !== false) {
                echo "   💡 Aide proposée: $fulfillmentText\n";
            } else {
                echo "   ⚠️  Réponse: $fulfillmentText\n";
            }
        } else {
            echo "   ❌ Erreur HTTP: $httpCode\n";
        }
        
        sleep(1); // Pause entre les tentatives
    }
    
    echo "\n";
}

echo "🎯 Tests d'erreur terminés !\n";
echo "Vérifiez les logs Laravel pour le détail des compteurs d'erreur.\n";
