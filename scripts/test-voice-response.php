<?php
/**
 * Test Voice Response - Test de la réponse vocale de l'assistant
 */

echo "=== Test Voice Response - Réponse Vocale PCMA ===\n\n";

echo "🎤 **Test de la réponse vocale de l'assistant**\n\n";

// Test de la route de test
$testUrl = 'http://localhost:8080/test-pcma-simple';
echo "1. Test de la page : $testUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    
    // Vérifier la présence de la fonction speakResponse
    echo "\n2. Vérification de la réponse vocale :\n";
    
    if (strpos($response, 'speakResponse') !== false) {
        echo "   ✅ Fonction speakResponse trouvée\n";
    } else {
        echo "   ❌ Fonction speakResponse NON trouvée\n";
    }
    
    // Vérifier la présence de SpeechSynthesis
    if (strpos($response, 'SpeechSynthesisUtterance') !== false) {
        echo "   ✅ SpeechSynthesis trouvé\n";
    } else {
        echo "   ❌ SpeechSynthesis NON trouvé\n";
    }
    
    // Vérifier la présence de la fonction processVoiceInput
    if (strpos($response, 'processVoiceInput') !== false) {
        echo "   ✅ Fonction processVoiceInput trouvée\n";
    } else {
        echo "   ❌ Fonction processVoiceInput NON trouvée\n";
    }
    
    // Vérifier la présence de la commande "commencer l'examen PCMA"
    if (strpos($response, 'commencer l\'examen PCMA') !== false) {
        echo "   ✅ Commande 'commencer l\'examen PCMA' trouvée\n";
    } else {
        echo "   ❌ Commande 'commencer l\'examen PCMA' NON trouvée\n";
    }
    
    // Vérifier la présence de la fonction startPCMA
    if (strpos($response, 'startPCMA') !== false) {
        echo "   ✅ Fonction startPCMA trouvée\n";
    } else {
        echo "   ❌ Fonction startPCMA NON trouvée\n";
    }
    
    // Vérifier la présence des instructions vocales
    echo "\n3. Instructions vocales disponibles :\n";
    
    $instructions = [
        'commencer l\'examen PCMA' => 'Démarrer la collecte',
        'Il s\'appelle [nom]' => 'Nom du joueur',
        'Il a [âge] ans' => 'Âge du joueur',
        'Il joue [position]' => 'Position du joueur',
        'oui' => 'Confirmer et soumettre',
        'non' => 'Corriger ou recommencer'
    ];
    
    foreach ($instructions as $instruction => $description) {
        if (strpos($response, $instruction) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier la présence du JavaScript de reconnaissance vocale
    echo "\n4. Reconnaissance vocale :\n";
    
    if (strpos($response, 'webkitSpeechRecognition') !== false) {
        echo "   ✅ WebkitSpeechRecognition trouvé\n";
    } else {
        echo "   ❌ WebkitSpeechRecognition NON trouvé\n";
    }
    
    if (strpos($response, 'initSpeechRecognition') !== false) {
        echo "   ✅ Fonction initSpeechRecognition trouvée\n";
    } else {
        echo "   ❌ Fonction initSpeechRecognition NON trouvée\n";
    }
    
    // Afficher un extrait du JavaScript pour vérifier la logique
    echo "\n5. Extrait du JavaScript vocal :\n";
    
    $jsStart = strpos($response, 'function processVoiceInput');
    if ($jsStart !== false) {
        $jsEnd = strpos($response, 'function', $jsStart + 1);
        if ($jsEnd === false) {
            $jsEnd = strpos($response, '</script>', $jsStart);
        }
        
        if ($jsEnd !== false) {
            $jsCode = substr($response, $jsStart, $jsEnd - $jsStart);
            echo "   📝 Code JavaScript vocal :\n";
            echo "      " . substr($jsCode, 0, 200) . "...\n";
        }
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé ===\n";
echo "🎯 **Objectif** : Vérifier que l'assistant répond vocalement\n";
echo "🔍 **Résultat** : Si toutes les fonctions sont trouvées, l'assistant devrait répondre\n";
echo "\n📋 **Comment tester la réponse vocale** :\n";
echo "1. Aller sur http://localhost:8080/test-pcma-simple\n";
echo "2. Cliquer sur l'onglet '🎤 Assistant Vocal'\n";
echo "3. Cliquer sur 'Commencer l\'examen PCMA'\n";
echo "4. Dire clairement : 'commencer l\'examen PCMA'\n";
echo "5. Vérifier que l'assistant répond vocalement\n";
echo "\n🎤 **Réponse attendue** :\n";
echo "- L'assistant devrait dire : 'Parfait ! Commençons l\'examen PCMA. Dites-moi le nom du joueur.'\n";
echo "- Puis il devrait écouter vos réponses et les traiter\n";
echo "\n🚨 **Si pas de réponse vocale** :\n";
echo "- Vérifier que le microphone est autorisé\n";
echo "- Vérifier la console du navigateur (F12)\n";
echo "- Vérifier que le volume est activé\n";
echo "\n🎉 **Votre assistant vocal PCMA devrait maintenant répondre !**\n";
?>

