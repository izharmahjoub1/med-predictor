<?php
/**
 * Debug Voice Conversation - Problème de conversation vocale
 */

echo "=== Debug Voice Conversation - Problème de Conversation ===\n\n";

echo "🎤 **PROBLÈME** : Le bouton s'active 2 secondes puis s'arrête sans conversation\n\n";

echo "🔍 **DIAGNOSTIC DU PROBLÈME** :\n\n";

// Test de la page
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
    
    // Vérifier les fonctions JavaScript critiques
    echo "\n2. Vérification des fonctions critiques :\n";
    
    $criticalFunctions = [
        'initSpeechRecognition' => 'Initialisation reconnaissance vocale',
        'startVoiceRecognition' => 'Démarrage reconnaissance vocale',
        'stopVoiceRecognition' => 'Arrêt reconnaissance vocale',
        'processVoiceInput' => 'Traitement entrée vocale',
        'speakResponse' => 'Réponse vocale',
        'startPCMA' => 'Démarrage PCMA'
    ];
    
    foreach ($criticalFunctions as $function => $description) {
        if (strpos($response, $function) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier les variables et états
    echo "\n3. Vérification des variables d'état :\n";
    
    $stateVariables = [
        'isListening' => 'État d\'écoute',
        'recognition' => 'Objet reconnaissance',
        'voiceSessionData' => 'Données session vocale',
        'voiceStatus' => 'Statut vocal'
    ];
    
    foreach ($stateVariables as $variable => $description) {
        if (strpos($response, $variable) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier les gestionnaires d'événements
    echo "\n4. Vérification des gestionnaires d'événements :\n";
    
    $eventHandlers = [
        'addEventListener.*start-voice-btn' => 'Gestionnaire bouton démarrage',
        'addEventListener.*stop-voice-btn' => 'Gestionnaire bouton arrêt',
        'onclick.*start-voice-btn' => 'Clic bouton démarrage',
        'onclick.*stop-voice-btn' => 'Clic bouton arrêt'
    ];
    
    foreach ($eventHandlers as $handler => $description) {
        if (preg_match('/' . $handler . '/', $response)) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier la configuration de reconnaissance vocale
    echo "\n5. Configuration reconnaissance vocale :\n";
    
    $recognitionConfig = [
        'continuous.*true' => 'Mode continu activé',
        'interimResults.*false' => 'Résultats intermédiaires désactivés',
        'lang.*fr' => 'Langue française',
        'lang.*fr-FR' => 'Langue française (France)'
    ];
    
    foreach ($recognitionConfig as $config => $description) {
        if (preg_match('/' . $config . '/', $response)) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier les messages d'erreur potentiels
    echo "\n6. Vérification des erreurs potentielles :\n";
    
    $errorPatterns = [
        'error.*recognition' => 'Erreur reconnaissance vocale',
        'error.*speech' => 'Erreur synthèse vocale',
        'error.*microphone' => 'Erreur microphone',
        'console\\.log.*error' => 'Logs d\'erreur'
    ];
    
    foreach ($errorPatterns as $pattern => $description) {
        if (preg_match('/' . $pattern . '/', $response)) {
            echo "   ⚠️  $description : TROUVÉ\n";
        } else {
            echo "   ✅ $description : NON TROUVÉ\n";
        }
    }
    
    // Afficher un extrait du JavaScript pour analyse
    echo "\n7. Extrait du JavaScript vocal :\n";
    
    $jsStart = strpos($response, 'function startVoiceRecognition');
    if ($jsStart !== false) {
        $jsEnd = strpos($response, 'function', $jsStart + 1);
        if ($jsEnd === false) {
            $jsEnd = strpos($response, '</script>', $jsStart);
        }
        
        if ($jsEnd !== false) {
            $jsCode = substr($response, $jsStart, $jsEnd - $jsStart);
            echo "   📝 Code startVoiceRecognition :\n";
            echo "      " . substr($jsCode, 0, 300) . "...\n";
        }
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
}

echo "\n=== DIAGNOSTIC ===\n";
echo "🔍 **Problèmes potentiels identifiés** :\n";
echo "1. La reconnaissance vocale peut ne pas s'initialiser correctement\n";
echo "2. Le microphone peut ne pas être autorisé\n";
echo "3. Il peut y avoir une erreur JavaScript qui arrête le processus\n";
echo "4. La configuration de reconnaissance vocale peut être incorrecte\n";
echo "5. Il peut y avoir un conflit avec d'autres composants\n\n";

echo "📋 **Solutions à essayer** :\n";
echo "1. Vérifier la console du navigateur (F12) pour les erreurs JavaScript\n";
echo "2. Vérifier que le microphone est autorisé dans le navigateur\n";
echo "3. Tester avec un autre navigateur (Chrome recommandé)\n";
echo "4. Vérifier que le volume est activé\n";
echo "5. Vider le cache du navigateur (Ctrl+F5)\n\n";

echo "🎯 **Test immédiat** :\n";
echo "1. Aller sur http://localhost:8080/test-pcma-simple\n";
echo "2. Ouvrir la console (F12)\n";
echo "3. Cliquer sur 'Commencer l\'examen PCMA'\n";
echo "4. Regarder les erreurs dans la console\n";
echo "5. Vérifier que le microphone est autorisé\n\n";

echo "🚨 **Erreurs courantes** :\n";
echo "- 'Permission denied' → Microphone non autorisé\n";
echo "- 'SpeechRecognition not supported' → Navigateur non compatible\n";
echo "- 'recognition is not defined' → Erreur JavaScript\n";
echo "- 'speakResponse is not defined' → Fonction manquante\n\n";

echo "🎉 **Objectif** : Rendre la conversation vocale fluide et continue !\n";
?>

