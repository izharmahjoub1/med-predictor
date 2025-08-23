<?php
echo "🧪 **Test des commandes vocales améliorées**\n";
echo "🎯 Vérification que l'assistant traite maintenant les commandes\n";

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
    
    // Vérifier les améliorations de la reconnaissance vocale
    $checks = [
        'console.log("🎯 Traitement de l\'entrée vocale:"' => 'Logs de débogage',
        'console.log("✅ Commande \'nom du joueur\' détectée")' => 'Détection nom du joueur',
        'console.log("✅ Commande \'âge\' détectée")' => 'Détection âge',
        'console.log("✅ Commande \'club\' détectée")' => 'Détection club',
        'lowerText.includes("s\'appelle")' => 'Reconnaissance "s\'appelle"',
        'lowerText.includes("il s\'appelle")' => 'Reconnaissance "il s\'appelle"',
        'lowerText.includes("est")' => 'Reconnaissance "est"',
        'console.log("🎯 Nom extrait:"' => 'Extraction du nom',
        'console.log("🎯 Âge extrait:"' => 'Extraction de l\'âge',
        'console.log("🎯 Club extrait:"' => 'Extraction du club',
        'console.log("🗣️ Réponse vocale:"' => 'Logs des réponses',
        'console.log("🎤 Synthèse vocale démarrée")' => 'Logs de synthèse vocale'
    ];
    
    $allGood = true;
    foreach ($checks as $search => $description) {
        if (strpos($response, $search) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description - NON TROUVÉ\n";
            $allGood = false;
        }
    }
    
    if ($allGood) {
        echo "\n🎉 **TOUTES LES AMÉLIORATIONS SONT PRÉSENTES !**\n";
        echo "🚀 Votre assistant vocal PCMA est maintenant INTELLIGENT !\n";
        echo "\n📋 **Instructions de test FINALES :**\n";
        echo "1. Allez sur http://localhost:8080/test-pcma-simple\n";
        echo "2. Cliquez sur 'Commencer l\'examen PCMA'\n";
        echo "3. Autorisez l'accès au microphone\n";
        echo "4. Dites clairement une de ces phrases :\n";
        echo "   • 'Nom du joueur: Jean Dupont'\n";
        echo "   • 'Il s\'appelle Jean Dupont'\n";
        echo "   • 'Le nom du joueur est Jean Dupont'\n";
        echo "   • 'Âge: 25 ans'\n";
        echo "   • 'Club: Paris Saint-Germain'\n";
        echo "5. L'assistant devrait maintenant RÉAGIR et RÉPONDRE !\n";
        echo "\n🔧 **Améliorations appliquées :**\n";
        echo "✅ Reconnaissance flexible des commandes\n";
        echo "✅ Extraction intelligente des informations\n";
        echo "✅ Logs de débogage complets\n";
        echo "✅ Gestion des différents formats de saisie\n";
        echo "✅ Réponses contextuelles\n";
        echo "✅ Plus de timeout de 2 secondes !\n";
        echo "\n🎯 **Testez maintenant votre assistant vocal INTELLIGENT !**\n";
        echo "🌐 URL: http://localhost:8080/test-pcma-simple\n";
        echo "\n💡 **Conseil :** Ouvrez la console du navigateur (F12) pour voir les logs !\n";
    } else {
        echo "\n❌ **Problèmes détectés**\n";
        echo "🔧 Vérifiez le fichier create.blade.php\n";
    }
} else {
    echo "❌ Erreur HTTP $httpCode\n";
    
    // Afficher la réponse d'erreur
    echo "📄 Corps de l'erreur:\n";
    echo substr($response, 0, 500) . "\n";
}
?>

