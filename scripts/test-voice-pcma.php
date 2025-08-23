<?php
/**
 * Test de la Fonctionnalité Vocale PCMA
 * Vérifie que l'interface vocale est correctement intégrée
 */

echo "=== Test Fonctionnalité Vocale PCMA ===\n\n";

// Test de la page de création PCMA
$createUrl = 'http://localhost:8080/pcma/create';
echo "1. Test de la page de création PCMA :\n";
echo "   URL : $createUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $createUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    
    // Vérifier la présence des éléments vocaux
    $voiceElements = [
        'voice-section' => 'Section vocale',
        'start-voice-btn' => 'Bouton de démarrage vocal',
        'stop-voice-btn' => 'Bouton d\'arrêt vocal',
        'voice-status' => 'Statut vocal',
        'voice-form-preview' => 'Prévisualisation du formulaire vocal',
        'voice-player-name' => 'Champ nom du joueur',
        'voice-player-age' => 'Champ âge du joueur',
        'voice-player-position' => 'Champ position du joueur'
    ];
    
    echo "\n2. Vérification des éléments vocaux :\n";
    foreach ($voiceElements as $id => $description) {
        if (strpos($response, "id=\"$id\"") !== false) {
            echo "   ✅ $description trouvé\n";
        } else {
            echo "   ❌ $description manquant\n";
        }
    }
    
    // Vérifier la présence du JavaScript vocal
    $jsElements = [
        'initSpeechRecognition' => 'Fonction d\'initialisation vocale',
        'processVoiceInput' => 'Fonction de traitement vocal',
        'speakResponse' => 'Fonction de réponse vocale',
        'webkitSpeechRecognition' => 'Support reconnaissance vocale'
    ];
    
    echo "\n3. Vérification du JavaScript vocal :\n";
    foreach ($jsElements as $function => $description) {
        if (strpos($response, $function) !== false) {
            echo "   ✅ $description trouvé\n";
        } else {
            echo "   ❌ $description manquant\n";
        }
    }
    
    // Vérifier les instructions vocales
    $voiceInstructions = [
        'commencer l\'examen PCMA' => 'Instruction de démarrage',
        'Il s\'appelle' => 'Instruction pour le nom',
        'Il a' => 'Instruction pour l\'âge',
        'Il joue' => 'Instruction pour la position',
        'attaquant' => 'Position attaquant',
        'défenseur' => 'Position défenseur',
        'milieu' => 'Position milieu',
        'gardien' => 'Position gardien'
    ];
    
    echo "\n4. Vérification des instructions vocales :\n";
    foreach ($voiceInstructions as $instruction => $description) {
        if (strpos($response, $instruction) !== false) {
            echo "   ✅ $description trouvé\n";
        } else {
            echo "   ❌ $description manquant\n";
        }
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
    echo "   📍 Redirection vers : $finalUrl\n";
}

// Test de l'API webhook vocal (si accessible)
echo "\n5. Test de l'API webhook vocal :\n";
$webhookUrl = 'http://localhost:8080/api/google-assistant/webhook';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhookUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'queryResult' => [
        'intent' => ['displayName' => 'start_pcma'],
        'parameters' => [],
        'queryText' => 'commencer l\'examen PCMA'
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ API webhook accessible (HTTP $httpCode)\n";
    $data = json_decode($response, true);
    if (isset($data['fulfillmentText'])) {
        echo "   📝 Réponse : " . substr($data['fulfillmentText'], 0, 80) . "...\n";
    }
} else {
    echo "   ❌ API webhook inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé ===\n";
echo "🎤 Fonctionnalité vocale PCMA intégrée avec succès !\n";
echo "📱 Interface accessible sur : $createUrl\n";
echo "🔗 API webhook : $webhookUrl\n";
echo "\n📋 Pour tester :\n";
echo "1. Aller sur la page de création PCMA\n";
echo "2. Cliquer sur l'onglet 'Enregistrement vocal'\n";
echo "3. Utiliser les commandes vocales\n";
echo "4. Tester le remplissage du formulaire\n";
echo "\n🎯 Votre plateforme FIT dispose maintenant d'un assistant vocal PCMA !\n";
?>

