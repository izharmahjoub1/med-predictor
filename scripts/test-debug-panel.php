<?php
echo "🔍 **TEST DU PANNEAU DE DÉBOGAGE**\n";
echo "🎯 Vérification que le panneau de débogage est présent\n";

$url = 'http://localhost:8080/test-pcma-simple';

echo "📡 Test de $url...\n";

// Test avec curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Code HTTP: $httpCode\n";

if ($error) {
    echo "❌ Erreur curl: $error\n";
    exit(1);
}

if ($httpCode === 200) {
    echo "✅ Page accessible\n";
    echo "📏 Taille de la réponse: " . number_format(strlen($response)) . " caractères\n";
    
    // Vérifier les éléments de débogage
    $debugElements = [
        'debug-last-heard' => 'Panneau dernière phrase entendue',
        'debug-command' => 'Panneau commande détectée',
        'debug-extracted' => 'Panneau données extraites',
        'debug-status' => 'Panneau statut reconnaissance',
        'debug-history' => 'Panneau historique des phrases',
        'updateDebug' => 'Fonction updateDebug',
        'addToHistory' => 'Fonction addToHistory',
        'voiceHistory' => 'Variable voiceHistory'
    ];
    
    $allGood = true;
    foreach ($debugElements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description - NON TROUVÉ\n";
            $allGood = false;
        }
    }
    
    if ($allGood) {
        echo "\n🎉 **PANNEAU DE DÉBOGAGE COMPLÈTEMENT INSTALLÉ !**\n";
        echo "🔍 Maintenant vous pouvez voir EN TEMPS RÉEL :\n";
        echo "✅ Chaque phrase que l'assistant entend\n";
        echo "✅ Quelle commande il détecte\n";
        echo "✅ Quelles données il extrait\n";
        echo "✅ Le statut de la reconnaissance\n";
        echo "✅ L'historique complet des interactions\n";
        echo "\n📋 **Instructions de test avec débogage :**\n";
        echo "1. Allez sur http://localhost:8080/test-pcma-simple\n";
        echo "2. Regardez le panneau jaune '🔍 Débogage en temps réel'\n";
        echo "3. Cliquez sur 'Commencer l'examen PCMA'\n";
        echo "4. Parlez dans le microphone\n";
        echo "5. Observez les champs se remplir EN TEMPS RÉEL !\n";
        echo "\n💡 **Exemples à tester :**\n";
        echo "• 'Commencer l'examen PCMA' → Vous verrez la commande détectée\n";
        echo "• 'Il s'appelle Jean Dupont' → Vous verrez le nom extrait\n";
        echo "• 'Il a 25 ans' → Vous verrez l'âge extrait\n";
        echo "• En cas d'erreur réseau → Vous verrez l'erreur dans l'historique\n";
        echo "\n🎯 **Vous pouvez maintenant VOIR EXACTEMENT ce que l'assistant comprend !**\n";
    } else {
        echo "\n❌ **Problèmes détectés avec le panneau de débogage**\n";
        echo "🔧 Vérifiez les éléments manquants\n";
    }
} else {
    echo "❌ Erreur HTTP $httpCode\n";
    echo "📄 Corps de l'erreur:\n";
    echo substr($response, 0, 500) . "\n";
}
?>

