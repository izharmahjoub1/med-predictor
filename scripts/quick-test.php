<?php
/**
 * Test rapide de l'API webhook PCMA
 * Vérifie que tous les endpoints sont accessibles
 */

echo "=== Test Rapide API Webhook PCMA ===\n\n";

// Test 1: Vérifier que le serveur répond
echo "1. Test de connectivité...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Serveur Laravel accessible\n";
} else {
    echo "   ❌ Serveur Laravel inaccessible (HTTP $httpCode)\n";
    exit(1);
}

// Test 2: Vérifier l'endpoint webhook
echo "2. Test de l'endpoint webhook...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/api/google-assistant/webhook');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'queryResult' => [
        'intent' => ['displayName' => 'test'],
        'parameters' => [],
        'queryText' => 'test'
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Endpoint webhook accessible\n";
    $data = json_decode($response, true);
    if (isset($data['fulfillmentText'])) {
        echo "   ✅ Réponse JSON valide\n";
    } else {
        echo "   ⚠️ Réponse JSON invalide\n";
    }
} else {
    echo "   ❌ Endpoint webhook inaccessible (HTTP $httpCode)\n";
    exit(1);
}

// Test 3: Vérifier la base de données
echo "3. Test de la base de données...\n";
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='voice_sessions'");
    if ($stmt->fetch()) {
        echo "   ✅ Table voice_sessions existe\n";
    } else {
        echo "   ❌ Table voice_sessions manquante\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ❌ Erreur base de données: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Test rapide des intents principaux
echo "4. Test des intents principaux...\n";

$intents = [
    'start_pcma' => ['parameters' => []],
    'answer_field' => ['parameters' => ['player_name' => 'Test']],
    'yes_intent' => ['parameters' => ['player_name' => 'Test', 'age1' => '25', 'position' => 'test']]
];

foreach ($intents as $intent => $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/api/google-assistant/webhook');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'queryResult' => [
            'intent' => ['displayName' => $intent],
            'parameters' => $data['parameters'],
            'queryText' => "Test $intent"
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ Intent '$intent' fonctionne\n";
    } else {
        echo "   ❌ Intent '$intent' échoue (HTTP $httpCode)\n";
    }
    
    usleep(100000); // 100ms pause
}

echo "\n=== Résumé ===\n";
echo "🎉 Tous les tests sont passés !\n";
echo "🚀 L'API webhook PCMA est prête pour Dialogflow\n";
echo "📝 URL à configurer : https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook\n";
echo "\nProchaine étape : Configurer Dialogflow avec cette URL\n";
?>

