<?php
/**
 * Debug Voice Timeout - Problème d'arrêt après 2 secondes
 */

echo "=== Debug Voice Timeout - Problème d'Arrêt après 2 Secondes ===\n\n";

echo "⏱️  **PROBLÈME** : Le bouton s'arrête toujours après 2 secondes\n\n";

echo "🔍 **DIAGNOSTIC APPROFONDI** :\n\n";

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
    
    // Vérifier la présence de setTimeout ou setInterval
    echo "\n2. Vérification des timers potentiels :\n";
    
    $timers = [
        'setTimeout.*2000' => 'Timeout de 2 secondes',
        'setTimeout.*2\\*1000' => 'Timeout de 2 secondes (calculé)',
        'setInterval.*2000' => 'Interval de 2 secondes',
        'setTimeout' => 'setTimeout présent',
        'setInterval' => 'setInterval présent'
    ];
    
    foreach ($timers as $timer => $description) {
        if (preg_match('/' . $timer . '/', $response)) {
            echo "   ⚠️  $description : TROUVÉ\n";
        } else {
            echo "   ✅ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier la présence de stopVoiceRecognition automatique
    echo "\n3. Vérification de l'arrêt automatique :\n";
    
    $autoStop = [
        'stopVoiceRecognition.*setTimeout' => 'Arrêt automatique avec setTimeout',
        'recognition\\.stop.*setTimeout' => 'Arrêt reconnaissance avec setTimeout',
        'setTimeout.*stopVoiceRecognition' => 'Timeout vers stopVoiceRecognition',
        'setTimeout.*recognition\\.stop' => 'Timeout vers recognition.stop'
    ];
    
    foreach ($autoStop as $pattern => $description) {
        if (preg_match('/' . $pattern . '/', $response)) {
            echo "   ⚠️  $description : TROUVÉ\n";
        } else {
            echo "   ✅ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier la configuration de reconnaissance vocale
    echo "\n4. Configuration reconnaissance vocale :\n";
    
    $recognitionConfig = [
        'continuous.*true' => 'Mode continu activé',
        'continuous.*false' => 'Mode continu désactivé',
        'interimResults.*false' => 'Résultats intermédiaires désactivés',
        'maxAlternatives.*1' => 'Alternatives limitées',
        'lang.*fr' => 'Langue française'
    ];
    
    foreach ($recognitionConfig as $config => $description) {
        if (preg_match('/' . $config . '/', $response)) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier les gestionnaires d'événements de reconnaissance
    echo "\n5. Gestionnaires d'événements de reconnaissance :\n";
    
    $recognitionEvents = [
        'recognition\\.onstart' => 'Événement onstart',
        'recognition\\.onend' => 'Événement onend',
        'recognition\\.onerror' => 'Événement onerror',
        'recognition\\.onresult' => 'Événement onresult',
        'recognition\\.onspeechend' => 'Événement onspeechend'
    ];
    
    foreach ($recognitionEvents as $event => $description) {
        if (preg_match('/' . $event . '/', $response)) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Chercher des patterns de problème spécifiques
    echo "\n6. Patterns de problème potentiels :\n";
    
    $problemPatterns = [
        'recognition\\.stop\\(\\)' => 'Appel à recognition.stop()',
        'stopVoiceRecognition\\(\\)' => 'Appel à stopVoiceRecognition()',
        'isListening.*false' => 'Mise à false de isListening',
        'recognition\\.abort' => 'Abort de la reconnaissance',
        'setTimeout.*function' => 'setTimeout avec fonction'
    ];
    
    foreach ($problemPatterns as $pattern => $description) {
        if (preg_match('/' . $pattern . '/', $response)) {
            echo "   ⚠️  $description : TROUVÉ\n";
        } else {
            echo "   ✅ $description : NON TROUVÉ\n";
        }
    }
    
    // Afficher un extrait du JavaScript pour analyse
    echo "\n7. Extrait du JavaScript de reconnaissance :\n";
    
    $jsStart = strpos($response, 'webkitSpeechRecognition');
    if ($jsStart !== false) {
        $jsEnd = strpos($response, '</script>', $jsStart);
        if ($jsEnd === false) {
            $jsEnd = strpos($response, 'function', $jsStart + 1);
        }
        
        if ($jsEnd !== false) {
            $jsCode = substr($response, $jsStart, $jsEnd - $jsStart);
            echo "   📝 Code reconnaissance vocale :\n";
            echo "      " . substr($jsCode, 0, 400) . "...\n";
        }
    }
    
    // Chercher des indices de timeout caché
    echo "\n8. Recherche de timeout cachés :\n";
    
    $hiddenTimeouts = [
        '2000' => 'Valeur 2000 (2 secondes)',
        '2\\*1000' => 'Calcul 2*1000',
        '2e3' => 'Notation scientifique 2e3',
        '2000ms' => '2000 millisecondes',
        '2s' => '2 secondes'
    ];
    
    foreach ($hiddenTimeouts as $timeout => $description) {
        if (preg_match('/' . $timeout . '/', $response)) {
            echo "   ⚠️  $description : TROUVÉ\n";
        } else {
            echo "   ✅ $description : NON TROUVÉ\n";
        }
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
}

echo "\n=== DIAGNOSTIC DU TIMEOUT ===\n";
echo "🔍 **Causes possibles du timeout de 2 secondes** :\n";
echo "1. ⏱️  setTimeout caché dans le code JavaScript\n";
echo "2. ⏱️  Configuration de reconnaissance vocale incorrecte\n";
echo "3. ⏱️  Événement onend qui arrête automatiquement\n";
echo "4. ⏱️  Événement onspeechend qui arrête la reconnaissance\n";
echo "5. ⏱️  Gestionnaire d'erreur qui arrête la reconnaissance\n";
echo "6. ⏱️  Conflit avec d'autres composants JavaScript\n\n";

echo "📋 **Solutions à essayer** :\n";
echo "1. 🔍 Vérifier la console du navigateur (F12) pour les erreurs\n";
echo "2. ⏱️  Chercher des setTimeout dans le code JavaScript\n";
echo "3. 🎤 Vérifier la configuration de reconnaissance vocale\n";
echo "4. 🚫 Désactiver les événements onend et onspeechend\n";
echo "5. 🔄 Forcer le mode continu de la reconnaissance\n";
echo "6. 🧹 Nettoyer le code JavaScript\n\n";

echo "🎯 **Test immédiat** :\n";
echo "1. Aller sur http://localhost:8080/test-pcma-simple\n";
echo "2. Ouvrir la console (F12)\n";
echo "3. Cliquer sur 'Commencer l\'examen PCMA'\n";
echo "4. Regarder les messages dans la console\n";
echo "5. Chercher des erreurs ou des timeouts\n\n";

echo "🚨 **Erreurs courantes** :\n";
echo "- 'onend' → La reconnaissance s'arrête automatiquement\n";
echo "- 'onspeechend' → Arrêt après fin de parole\n";
echo "- 'setTimeout' → Timeout programmé\n";
echo "- 'continuous: false' → Mode non continu\n\n";

echo "🎉 **Objectif** : Identifier et éliminer le timeout de 2 secondes !\n";
?>

