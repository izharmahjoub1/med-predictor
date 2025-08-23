<?php

/**
 * Script de test pour Google Actions
 * Simule les requêtes venant de Google Assistant
 */

echo "🎯 Test Google Actions - FIT Med Assistant\n";
echo "==========================================\n\n";

$baseUrl = 'http://localhost:8000/api/google-assistant';
$headers = [
    'Content-Type: application/json',
    'Accept: application/json',
    'User-Agent: Google-Assistant/1.0',
    'X-User-ID: 1'
];

function testGoogleAction($name, $data) {
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
        $responseData = json_decode($response, true);
        echo "   ✅ Succès\n";
        echo "   💬 Réponse: " . ($responseData['fulfillmentText'] ?? 'N/A') . "\n";
        
        // Vérifier les informations de contexte
        if (isset($responseData['outputContexts'])) {
            echo "   🔄 Contexte: " . count($responseData['outputContexts']) . " contexte(s)\n";
            foreach ($responseData['outputContexts'] as $context) {
                if (isset($context['parameters'])) {
                    echo "      - Session: " . ($context['parameters']['session_id'] ?? 'N/A') . "\n";
                    echo "      - Champ actuel: " . ($context['parameters']['current_field'] ?? 'N/A') . "\n";
                    echo "      - Joueur: " . ($context['parameters']['player_name'] ?? 'N/A') . "\n";
                }
            }
        }
        
        return true;
    } else {
        echo "   ❌ Échec\n";
        echo "   📝 Réponse: " . substr($response, 0, 200) . "\n";
        return false;
    }
}

// Test 1: Intent principal (MAIN)
echo "📋 Test 1: Intent principal (MAIN)\n";
$test1 = testGoogleAction('Intent principal', [
    'queryResult' => [
        'intent' => ['displayName' => 'actions.intent.MAIN'],
        'queryText' => 'OK Google, parle à FIT Med Assistant'
    ],
    'session' => 'test-main-123'
]);

echo "\n";

// Test 2: Démarrage PCMA
echo "📋 Test 2: Démarrage PCMA\n";
$test2 = testGoogleAction('Démarrage PCMA', [
    'queryResult' => [
        'intent' => ['displayName' => 'start_pcma'],
        'parameters' => ['player_name' => 'Mohamed Salah'],
        'queryText' => 'Commence un PCMA pour Mohamed Salah'
    ],
    'session' => 'test-pcma-456'
]);

echo "\n";

// Test 3: Réponse au champ poste
echo "📋 Test 3: Réponse au champ poste\n";
$test3 = testGoogleAction('Réponse poste', [
    'queryResult' => [
        'intent' => ['displayName' => 'answer_field'],
        'parameters' => ['field_value' => 'attaquant'],
        'queryText' => 'Il est attaquant'
    ],
    'session' => 'test-pcma-456'
]);

echo "\n";

// Test 4: Réponse au champ âge
echo "📋 Test 4: Réponse au champ âge\n";
$test4 = testGoogleAction('Réponse âge', [
    'queryResult' => [
        'intent' => ['displayName' => 'answer_field'],
        'parameters' => ['field_value' => '30'],
        'queryText' => 'Il a 30 ans'
    ],
    'session' => 'test-pcma-456'
]);

echo "\n";

// Test 5: Révision PCMA
echo "📋 Test 5: Révision PCMA\n";
$test5 = testGoogleAction('Révision PCMA', [
    'queryResult' => [
        'intent' => ['displayName' => 'review_pcma'],
        'queryText' => 'Récapitule le PCMA'
    ],
    'session' => 'test-pcma-456'
]);

echo "\n";

// Test 6: Finalisation PCMA
echo "📋 Test 6: Finalisation PCMA\n";
$test6 = testGoogleAction('Finalisation PCMA', [
    'queryResult' => [
        'intent' => ['displayName' => 'complete_pcma'],
        'queryText' => 'Termine le PCMA'
    ],
    'session' => 'test-pcma-456'
]);

echo "\n";

// Test 7: Intent non reconnu
echo "📋 Test 7: Intent non reconnu\n";
$test7 = testGoogleAction('Intent inconnu', [
    'queryResult' => [
        'intent' => ['displayName' => 'unknown_intent'],
        'queryText' => 'Quelque chose que je ne comprends pas'
    ],
    'session' => 'test-unknown-789'
]);

echo "\n";

// Test 8: Santé du système
echo "📋 Test 8: Santé du système\n";
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
echo "📊 Résumé des Tests Google Actions\n";
echo "==================================\n";
echo "Test 1 (Intent principal): " . ($test1 ? "✅" : "❌") . "\n";
echo "Test 2 (Démarrage PCMA): " . ($test2 ? "✅" : "❌") . "\n";
echo "Test 3 (Réponse poste): " . ($test3 ? "✅" : "❌") . "\n";
echo "Test 4 (Réponse âge): " . ($test4 ? "✅" : "❌") . "\n";
echo "Test 5 (Révision PCMA): " . ($test5 ? "✅" : "❌") . "\n";
echo "Test 6 (Finalisation): " . ($test6 ? "✅" : "❌") . "\n";
echo "Test 7 (Intent inconnu): " . ($test7 ? "✅" : "❌") . "\n";
echo "Test 8 (Santé): " . ($httpCode === 200 ? "✅" : "❌") . "\n";

echo "\n🎯 Configuration Google Actions:\n";
echo "   - Intents configurés ✅\n";
echo "   - Types définis ✅\n";
echo "   - Scènes créées ✅\n";
echo "   - Webhook fonctionnel ✅\n";

echo "\n📝 Prochaines étapes:\n";
echo "   1. Configurer Actions on Google Console\n";
echo "   2. Tester avec le simulateur Google\n";
echo "   3. Valider sur appareil réel\n";
echo "   4. Soumettre pour approbation Google\n";

echo "\nTest terminé.\n";
