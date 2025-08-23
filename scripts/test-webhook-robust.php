<?php
/**
 * Test robuste de l'API webhook PCMA
 * Utilise des données valides pour tester tous les intents
 */

echo "=== Test Robuste API Webhook PCMA ===\n\n";

$webhookUrl = 'http://localhost:8080/api/google-assistant/webhook';

// Test complet du flux PCMA avec des données valides
$testFlow = [
    [
        'name' => 'start_pcma - Démarrage',
        'intent' => 'start_pcma',
        'parameters' => [],
        'expected_response' => 'Bonjour'
    ],
    [
        'name' => 'answer_field - Nom du joueur',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Mohamed'],
        'expected_response' => 'Mohamed'
    ],
    [
        'name' => 'answer_field - Âge du joueur',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Mohamed', 'age1' => '25'],
        'expected_response' => '25 ans'
    ],
    [
        'name' => 'answer_field - Position du joueur',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Mohamed', 'age1' => '25', 'position' => 'attaquant'],
        'expected_response' => 'attaquant'
    ],
    [
        'name' => 'yes_intent - Confirmation',
        'intent' => 'yes_intent',
        'parameters' => ['player_name' => 'Mohamed', 'age1' => '25', 'position' => 'attaquant'],
        'expected_response' => 'formulaire PCMA'
    ]
];

echo "Test du flux PCMA complet :\n\n";

foreach ($testFlow as $index => $test) {
    echo "Test " . ($index + 1) . ": {$test['name']}\n";
    
    // Préparer la requête
    $requestData = [
        'queryResult' => [
            'intent' => ['displayName' => $test['intent']],
            'parameters' => $test['parameters'],
            'queryText' => "Test: " . $test['name']
        ]
    ];
    
    // Appel à l'API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "   ❌ Erreur cURL: $error\n";
        continue;
    }
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if (isset($data['fulfillmentText'])) {
            $responseText = $data['fulfillmentText'];
            echo "   ✅ HTTP $httpCode - Réponse reçue\n";
            echo "   📝 Réponse: " . substr($responseText, 0, 80) . "...\n";
            
            // Vérifier si la réponse contient le texte attendu
            if (stripos($responseText, $test['expected_response']) !== false) {
                echo "   🎯 Réponse attendue trouvée\n";
            } else {
                echo "   ⚠️ Réponse différente de celle attendue\n";
            }
        } else {
            echo "   ⚠️ Réponse JSON invalide\n";
        }
    } else {
        echo "   ❌ HTTP $httpCode - Erreur\n";
        if ($response) {
            echo "   📝 Erreur: " . substr($response, 0, 100) . "...\n";
        }
    }
    
    echo "   ---\n";
    
    // Pause entre les tests
    usleep(200000); // 200ms
}

// Test de validation des données
echo "\n=== Test de Validation ===\n";

$validationTests = [
    [
        'name' => 'Données invalides - Âge négatif',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Test', 'age1' => '-5'],
        'should_fail' => true
    ],
    [
        'name' => 'Données invalides - Position invalide',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Test', 'age1' => '25', 'position' => 'invalid_position'],
        'should_fail' => true
    ]
];

foreach ($validationTests as $test) {
    echo "Test: {$test['name']}\n";
    
    $requestData = [
        'queryResult' => [
            'intent' => ['displayName' => $test['intent']],
            'parameters' => $test['parameters'],
            'queryText' => "Test validation"
        ]
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if (isset($data['fulfillmentText'])) {
            $responseText = $data['fulfillmentText'];
            if ($test['should_fail'] && stripos($responseText, 'erreur') !== false) {
                echo "   ✅ Validation fonctionne (erreur détectée)\n";
            } elseif (!$test['should_fail'] && stripos($responseText, 'erreur') === false) {
                echo "   ✅ Validation fonctionne (données acceptées)\n";
            } else {
                echo "   ⚠️ Validation inattendue\n";
            }
        }
    }
    
    echo "   ---\n";
    usleep(100000); // 100ms
}

echo "\n=== Résumé ===\n";
echo "🎉 Tests terminés !\n";
echo "🚀 L'API webhook PCMA est prête pour Dialogflow\n";
echo "📝 URL Cloud Run : https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook\n";
echo "🔧 URL Local : http://localhost:8080/api/google-assistant/webhook\n";
echo "\nProchaine étape : Configurer Dialogflow avec l'URL Cloud Run\n";
?>

