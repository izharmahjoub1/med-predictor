<?php
/**
 * Script de test pour l'interface des modes de collecte PCMA
 * Teste les éléments HTML et JavaScript
 */

echo "🧪 TEST DE L'INTERFACE DES MODES DE COLLECTE PCMA\n";
echo "================================================\n\n";

// URL de test
$url = 'http://localhost:8081/pcma/create';

echo "🔍 Test de la page PCMA...\n";
echo "URL: $url\n\n";

// Test 1: Vérifier que la page est accessible
echo "📋 Test 1: Accessibilité de la page\n";
echo "-----------------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "URL finale: $finalUrl\n";

if ($httpCode === 200) {
    echo "✅ Page accessible\n\n";
} else {
    echo "❌ Page non accessible (code: $httpCode)\n";
    if ($httpCode === 302 || $httpCode === 301) {
        echo "⚠️  Redirection détectée - probablement vers la page de connexion\n";
        echo "   C'est normal pour une page protégée par authentification\n\n";
    }
}

// Test 2: Vérifier la présence des éléments des modes de collecte
echo "🎯 Test 2: Éléments des modes de collecte\n";
echo "----------------------------------------\n";

if ($response) {
    // Vérifier les modes de collecte
    $modesCollecte = strpos($response, 'Modes de collecte') !== false;
    $modeManuel = strpos($response, 'mode-manuel') !== false;
    $modeVocal = strpos($response, 'mode-vocal') !== false;
    $modeSelector = strpos($response, 'mode-selector') !== false;
    
    echo "Section 'Modes de collecte': " . ($modesCollecte ? '✅' : '❌') . "\n";
    echo "Bouton Mode Manuel (ID): " . ($modeManuel ? '✅' : '❌') . "\n";
    echo "Bouton Mode Vocal (ID): " . ($modeVocal ? '✅' : '❌') . "\n";
    echo "Classe CSS mode-selector: " . ($modeSelector ? '✅' : '❌') . "\n";
    
    if ($modesCollecte && $modeManuel && $modeVocal && $modeSelector) {
        echo "✅ Tous les éléments des modes de collecte sont présents\n";
    } else {
        echo "❌ Certains éléments des modes de collecte sont manquants\n";
    }
    echo "\n";
    
    // Test 3: Vérifier la console vocale
    echo "🎤 Test 3: Console vocale\n";
    echo "-------------------------\n";
    
    $consoleVocale = strpos($response, 'console-vocale') !== false;
    $startRecording = strpos($response, 'start-recording-btn') !== false;
    $stopRecording = strpos($response, 'stop-recording-btn') !== false;
    $voiceStatus = strpos($response, 'voice-status') !== false;
    $voiceResults = strpos($response, 'voice-results') !== false;
    
    echo "Console vocale (ID): " . ($consoleVocale ? '✅' : '❌') . "\n";
    echo "Bouton démarrer (ID): " . ($startRecording ? '✅' : '❌') . "\n";
    echo "Bouton arrêter (ID): " . ($stopRecording ? '✅' : '❌') . "\n";
    echo "Statut vocal (ID): " . ($voiceStatus ? '✅' : '❌') . "\n";
    echo "Résultats vocaux (ID): " . ($voiceResults ? '✅' : '❌') . "\n";
    
    if ($consoleVocale && $startRecording && $stopRecording && $voiceStatus && $voiceResults) {
        echo "✅ Tous les éléments de la console vocale sont présents\n";
    } else {
        echo "❌ Certains éléments de la console vocale sont manquants\n";
    }
    echo "\n";
    
    // Test 4: Vérifier le formulaire principal
    echo "📝 Test 4: Formulaire principal\n";
    echo "--------------------------------\n";
    
    $formulairePrincipal = strpos($response, 'formulaire-principal') !== false;
    $formSection = strpos($response, 'form-section') !== false;
    
    echo "Formulaire principal (ID): " . ($formulairePrincipal ? '✅' : '❌') . "\n";
    echo "Classe CSS form-section: " . ($formSection ? '✅' : '❌') . "\n";
    
    if ($formulairePrincipal && $formSection) {
        echo "✅ Le formulaire principal est présent\n";
    } else {
        echo "❌ Le formulaire principal est manquant\n";
    }
    echo "\n";
    
    // Test 5: Vérifier les instructions vocales
    echo "📋 Test 5: Instructions vocales\n";
    echo "-------------------------------\n";
    
    $commandesVocales = strpos($response, 'Commandes vocales disponibles') !== false;
    $idFifaConnect = strpos($response, 'ID FIFA CONNECT') !== false;
    $exemples = strpos($response, 'Exemples') !== false;
    
    echo "Section commandes vocales: " . ($commandesVocales ? '✅' : '❌') . "\n";
    echo "Commande 'ID FIFA CONNECT': " . ($idFifaConnect ? '✅' : '❌') . "\n";
    echo "Section exemples: " . ($exemples ? '✅' : '❌') . "\n";
    
    if ($commandesVocales && $idFifaConnect && $exemples) {
        echo "✅ Toutes les instructions vocales sont présentes\n";
    } else {
        echo "❌ Certaines instructions vocales sont manquantes\n";
    }
    echo "\n";
    
} else {
    echo "❌ Impossible de récupérer le contenu de la page\n\n";
}

// Test 6: Vérifier la structure JavaScript
echo "🔧 Test 6: Structure JavaScript\n";
echo "--------------------------------\n";

if ($response) {
    $initModesCollecte = strpos($response, 'initModesCollecte') !== false;
    $setModeManuel = strpos($response, 'setModeManuel') !== false;
    $setModeVocal = strpos($response, 'setModeVocal') !== false;
    $initConsoleVocale = strpos($response, 'initConsoleVocale') !== false;
    $startVoiceRecording = strpos($response, 'startVoiceRecording') !== false;
    $stopVoiceRecording = strpos($response, 'stopVoiceRecording') !== false;
    
    echo "Fonction initModesCollecte: " . ($initModesCollecte ? '✅' : '❌') . "\n";
    echo "Fonction setModeManuel: " . ($setModeManuel ? '✅' : '❌') . "\n";
    echo "Fonction setModeVocal: " . ($setModeVocal ? '✅' : '❌') . "\n";
    echo "Fonction initConsoleVocale: " . ($initConsoleVocale ? '✅' : '❌') . "\n";
    echo "Fonction startVoiceRecording: " . ($startVoiceRecording ? '✅' : '❌') . "\n";
    echo "Fonction stopVoiceRecording: " . ($stopVoiceRecording ? '✅' : '❌') . "\n";
    
    if ($initModesCollecte && $setModeManuel && $setModeVocal && 
        $initConsoleVocale && $startVoiceRecording && $stopVoiceRecording) {
        echo "✅ Toutes les fonctions JavaScript sont présentes\n";
    } else {
        echo "❌ Certaines fonctions JavaScript sont manquantes\n";
    }
    echo "\n";
}

// Résumé final
echo "📊 RÉSUMÉ DES TESTS\n";
echo "===================\n";

if ($httpCode === 200 || $httpCode === 302) {
    echo "✅ Interface des modes de collecte PCMA : PRÊTE À TESTER\n";
    echo "\n🎯 Prochaines étapes :\n";
    echo "   1. Tester l'interface dans le navigateur\n";
    echo "   2. Vérifier le basculement entre modes Manuel/Vocal\n";
    echo "   3. Tester l'affichage/masquage de la console vocale\n";
    echo "   4. Vérifier les boutons d'enregistrement\n";
} else {
    echo "❌ Interface des modes de collecte PCMA : PROBLÈME DÉTECTÉ\n";
    echo "\n🔧 Actions à effectuer :\n";
    echo "   1. Vérifier que le serveur Laravel fonctionne\n";
    echo "   2. Vérifier les routes et l'authentification\n";
    echo "   3. Relancer le serveur si nécessaire\n";
}

echo "\n✨ Test terminé !\n";
?>

