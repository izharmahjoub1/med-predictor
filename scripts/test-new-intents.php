<?php
/**
 * Script de test pour les nouveaux intents PCMA
 * Teste les intents de confirmation, correction et redémarrage
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration
$webhookUrl = 'http://localhost:8000/api/google-assistant/webhook';
$sessionId = 'test-new-intents-' . uniqid();

echo "🧪 Test des nouveaux intents PCMA\n";
echo "================================\n\n";

function sendWebhookRequest($queryText, $intentName, $sessionId, $parameters = [], $confidence = 1.0) {
    global $webhookUrl;
    
    $requestData = [
        'responseId' => 'test-' . uniqid(),
        'queryResult' => [
            'queryText' => $queryText,
            'action' => $intentName,
            'intent' => [
                'displayName' => $intentName
            ],
            'parameters' => $parameters,
            'allRequiredParamsPresent' => true,
            'fulfillmentText' => '...',
            'fulfillmentMessages' => [
                ['text' => ['text' => ['...']]]
            ],
            'outputContexts' => [
                [
                    'name' => "projects/test/locations/europe-west2/agent/sessions/{$sessionId}/contexts/start_pcma-followup",
                    'lifespanCount' => 5,
                    'parameters' => []
                ]
            ],
            'intentDetectionConfidence' => $confidence,
            'languageCode' => 'fr'
        ],
        'originalDetectIntentRequest' => [
            'source' => 'GOOGLE_HOME',
            'payload' => []
        ],
        'session' => "projects/test/locations/europe-west2/agent/sessions/{$sessionId}"
    ];
    
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
    
    if ($httpCode !== 200) {
        echo "   ❌ Erreur HTTP: $httpCode\n";
        return null;
    }
    
    $responseData = json_decode($response, true);
    $fulfillmentText = $responseData['fulfillmentText'] ?? 'Pas de réponse';
    
    echo "   Réponse: \"$fulfillmentText\"\n";
    return $fulfillmentText;
}

echo "📝 Test 1: Intent 'yes_intent' (Confirmation positive)\n";
echo "   Phrase: 'oui'\n";
$response = sendWebhookRequest("oui", "yes_intent", $sessionId);
if (strpos($response, "soumettre") !== false || strpos($response, "confirmer") !== false) {
    echo "   ✅ Succès: Réponse de confirmation détectée.\n";
} else {
    echo "   ❌ Échec: Réponse de confirmation non détectée.\n";
}
echo "\n";

echo "📝 Test 2: Intent 'no_intent' (Confirmation négative)\n";
echo "   Phrase: 'non'\n";
$response = sendWebhookRequest("non", "no_intent", $sessionId);
if (strpos($response, "corriger") !== false || strpos($response, "modifier") !== false) {
    echo "   ✅ Succès: Réponse de correction détectée.\n";
} else {
    echo "   ❌ Échec: Réponse de correction non détectée.\n";
}
echo "\n";

echo "📝 Test 3: Intent 'confirm_submit' (Confirmation de soumission)\n";
echo "   Phrase: 'soumettre le formulaire'\n";
$response = sendWebhookRequest("soumettre le formulaire", "confirm_submit", $sessionId);
if (strpos($response, "soumettre") !== false || strpos($response, "envoyer") !== false) {
    echo "   ✅ Succès: Réponse de soumission détectée.\n";
} else {
    echo "   ❌ Échec: Réponse de soumission non détectée.\n";
}
echo "\n";

echo "📝 Test 4: Intent 'cancel_pcma' (Annulation)\n";
echo "   Phrase: 'annuler le formulaire'\n";
$response = sendWebhookRequest("annuler le formulaire", "cancel_pcma", $sessionId);
if (strpos($response, "annuler") !== false || strpos($response, "arrêter") !== false) {
    echo "   ✅ Succès: Réponse d'annulation détectée.\n";
} else {
    echo "   ❌ Échec: Réponse d'annulation non détectée.\n";
}
echo "\n";

echo "📝 Test 5: Intent 'restart_pcma' (Redémarrage)\n";
echo "   Phrase: 'recommencer'\n";
$response = sendWebhookRequest("recommencer", "restart_pcma", $sessionId);
if (strpos($response, "recommencer") !== false || strpos($response, "bonjour") !== false) {
    echo "   ✅ Succès: Réponse de redémarrage détectée.\n";
} else {
    echo "   ❌ Échec: Réponse de redémarrage non détectée.\n";
}
echo "\n";

echo "📝 Test 6: Intent 'correct_field' (Correction de champ)\n";
echo "   Phrase: 'corriger le nom'\n";
$response = sendWebhookRequest("corriger le nom", "correct_field", $sessionId, ['field' => 'nom']);
if (strpos($response, "nom") !== false && (strpos($response, "corriger") !== false || strpos($response, "nouveau") !== false)) {
    echo "   ✅ Succès: Réponse de correction de champ détectée.\n";
} else {
    echo "   ❌ Échec: Réponse de correction de champ non détectée.\n";
}
echo "\n";

echo "📝 Test 7: Gestion d'erreur (faible confiance)\n";
echo "   Phrase: 'blablabla' (confiance: 0.1)\n";
$response = sendWebhookRequest("blablabla", "unknown", $sessionId, [], 0.1);
if (strpos($response, "aide") !== false || strpos($response, "comprendre") !== false) {
    echo "   ✅ Succès: Réponse d'aide détectée pour faible confiance.\n";
} else {
    echo "   ❌ Échec: Réponse d'aide non détectée.\n";
}
echo "\n";

echo "📝 Test 8: Fallback web après 3 erreurs\n";
echo "   Simuler 3 erreurs consécutives...\n";
sendWebhookRequest("erreur1", "Default Fallback Intent", $sessionId);
sendWebhookRequest("erreur2", "Default Fallback Intent", $sessionId);
$response = sendWebhookRequest("erreur3", "Default Fallback Intent", $sessionId);
if (strpos($response, "interface web") !== false || strpos($response, "web") !== false) {
    echo "   ✅ Succès: Fallback web déclenché après 3 erreurs.\n";
} else {
    echo "   ❌ Échec: Fallback web non déclenché.\n";
}
echo "\n";

echo "🎯 Tests des nouveaux intents terminés !\n";
echo "Vérifiez les logs Laravel pour plus de détails.\n";
echo "URL de test de l'interface web: http://localhost:8000/pcma/voice-fallback?session=$sessionId\n";
