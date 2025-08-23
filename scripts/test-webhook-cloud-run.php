<?php
/**
 * Script de test pour l'API webhook PCMA
 * Teste tous les intents avec des données simulées
 */

$webhookUrl = 'http://localhost:8080/api/google-assistant/webhook';

// Tests des différents intents
$tests = [
    [
        'name' => 'start_pcma',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'start_pcma'],
                'parameters' => [],
                'queryText' => 'commencer l\'examen PCMA'
            ]
        ]
    ],
    [
        'name' => 'answer_field - nom',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'answer_field'],
                'parameters' => ['player_name' => 'Ahmed'],
                'queryText' => 'Il s\'appelle Ahmed'
            ]
        ]
    ],
    [
        'name' => 'answer_field - âge',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'answer_field'],
                'parameters' => ['player_name' => 'Ahmed', 'age1' => '24'],
                'queryText' => 'Il a 24 ans'
            ]
        ]
    ],
    [
        'name' => 'answer_field - position',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'answer_field'],
                'parameters' => ['player_name' => 'Ahmed', 'age1' => '24', 'position' => 'défenseur'],
                'queryText' => 'Il joue défenseur'
            ]
        ]
    ],
    [
        'name' => 'yes_intent',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'yes_intent'],
                'parameters' => ['player_name' => 'Ahmed', 'age1' => '24', 'position' => 'défenseur'],
                'queryText' => 'Oui'
            ]
        ]
    ],
    [
        'name' => 'no_intent',
        'data' => [
            'queryResult' => [
                'intent' => ['displayName' => 'no_intent'],
                'parameters' => ['player_name' => 'Ahmed', 'age1' => '24', 'position' => 'défenseur'],
                'queryText' => 'Non'
            ]
        ]
    ]
];

echo "=== Test de l'API Webhook PCMA ===\n\n";

foreach ($tests as $test) {
    echo "Test: {$test['name']}\n";
    echo "Données: " . json_encode($test['data'], JSON_UNESCAPED_UNICODE) . "\n";
    
    // Appel à l'API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test['data']));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Code HTTP: $httpCode\n";
    echo "Réponse: " . $response . "\n";
    echo "---\n\n";
    
    // Pause entre les tests
    sleep(1);
}

echo "Tests terminés !\n";
?>

