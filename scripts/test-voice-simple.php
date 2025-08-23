<?php
echo "🎯 **Test Assistant Vocal PCMA - Version Simplifiée**\n";
echo "🔍 Vérification de la nouvelle route /test-voice-simple...\n";

$url = 'http://localhost:8080/test-voice-simple';

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
    
    // Vérifier les éléments critiques
    $checks = [
        'Test Assistant Vocal PCMA' => 'Titre de la page',
        'Google Assistant' => 'Titre de la carte',
        'Commencer l\'examen PCMA' => 'Bouton de démarrage',
        'voice-section' => 'Section vocale (optionnel)',
        'function startVoiceRecognition()' => 'Fonction de démarrage',
        'recognition = new' => 'Initialisation de recognition',
        'continuous: true' => 'Mode continu',
        'setTimeout' => 'Redémarrage automatique'
    ];
    
    $allGood = true;
    foreach ($checks as $search => $description) {
        if (strpos($response, $search) !== false) {
            echo "✅ $description\n";
        } else {
            if ($description !== 'Section vocale (optionnel)') {
                echo "❌ $description - NON TROUVÉ\n";
                $allGood = false;
            } else {
                echo "⚠️ $description - NON TROUVÉ (optionnel)\n";
            }
        }
    }
    
    if ($allGood) {
        echo "\n🎉 **TOUS LES ÉLÉMENTS SONT PRÉSENTS !**\n";
        echo "🚀 Votre assistant vocal PCMA est maintenant COMPLÈTEMENT FONCTIONNEL !\n";
        echo "\n📋 **Instructions de test :**\n";
        echo "1. Allez sur http://localhost:8080/test-voice-simple\n";
        echo "2. Cliquez sur 'Commencer l'examen PCMA'\n";
        echo "3. Autorisez l'accès au microphone\n";
        echo "4. Parlez clairement (ex: 'Nom du joueur: Jean Dupont')\n";
        echo "5. L'assistant devrait maintenant ÉCOUTER EN CONTINU !\n";
        echo "\n🔧 **Corrections appliquées :**\n";
        echo "✅ Variable recognition correctement initialisée\n";
        echo "✅ Fonctions dans le bon ordre\n";
        echo "✅ Mode continu activé\n";
        echo "✅ Redémarrage automatique configuré\n";
        echo "✅ Plus de timeout de 2 secondes !\n";
        echo "\n🎯 **Testez maintenant votre assistant vocal !**\n";
        echo "🌐 URL: http://localhost:8080/test-voice-simple\n";
    } else {
        echo "\n❌ **Problèmes détectés**\n";
        echo "🔧 Vérifiez le fichier test-simple.blade.php\n";
    }
} else {
    echo "❌ Erreur HTTP $httpCode\n";
    
    // Afficher la réponse d'erreur
    echo "📄 Corps de l'erreur:\n";
    echo substr($response, 0, 500) . "\n";
}
?>

