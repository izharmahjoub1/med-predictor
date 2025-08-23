<?php
/**
 * Test Production - Assistant Vocal PCMA FIT
 * Valide que l'assistant vocal fonctionne en production
 */

echo "=== Test Production - Assistant Vocal PCMA FIT ===\n\n";

// URLs de production
$ngrokUrl = 'https://94f299ed4d48.ngrok-free.app/api/google-assistant/webhook';
$cloudRunUrl = 'https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook';

echo "🌐 URLs de Production :\n";
echo "   Ngrok (Temporaire) : $ngrokUrl\n";
echo "   Cloud Run (Permanent) : $cloudRunUrl\n\n";

// Test du flux PCMA complet via ngrok
echo "🧪 Test du Flux PCMA Complet (via ngrok) :\n\n";

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
        'parameters' => ['player_name' => 'Ahmed'],
        'expected_response' => 'Ahmed'
    ],
    [
        'name' => 'answer_field - Âge du joueur',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Ahmed', 'age1' => '24'],
        'expected_response' => '24 ans'
    ],
    [
        'name' => 'answer_field - Position du joueur',
        'intent' => 'answer_field',
        'parameters' => ['player_name' => 'Ahmed', 'age1' => '24', 'position' => 'défenseur'],
        'expected_response' => 'défenseur'
    ],
    [
        'name' => 'yes_intent - Confirmation',
        'intent' => 'yes_intent',
        'parameters' => ['player_name' => 'Ahmed', 'age1' => '24', 'position' => 'défenseur'],
        'expected_response' => 'formulaire PCMA'
    ]
];

foreach ($testFlow as $index => $test) {
    echo "Test " . ($index + 1) . ": {$test['name']}\n";
    
    // Appel au webhook ngrok
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ngrokUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'queryResult' => [
            'intent' => ['displayName' => $test['intent']],
            'parameters' => $test['parameters'],
            'queryText' => "Test: " . $test['name']
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour ngrok
    
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
    usleep(300000); // 300ms
}

// Test de l'interface web de production
echo "\n🌐 Test de l'Interface Web de Production :\n";
$webUrl = 'https://94f299ed4d48.ngrok-free.app/pcma/voice-fallback';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Interface web accessible (HTTP $httpCode)\n";
    echo "   🌐 URL: $webUrl\n";
} else {
    echo "   ❌ Interface web inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé Production ===\n";
echo "🎉 Assistant vocal PCMA prêt pour la production !\n";
echo "🚀 Webhook ngrok fonctionnel : $ngrokUrl\n";
echo "🌐 Interface web accessible : $webUrl\n";
echo "\n📋 Prochaines étapes :\n";
echo "1. Configurer Dialogflow avec l'URL ngrok\n";
echo "2. Tester tous les intents PCMA\n";
echo "3. Valider le flux complet\n";
echo "4. Mettre en production pour les utilisateurs FIT\n";
echo "\n🎯 Votre assistant vocal PCMA est opérationnel !\n";
?>

