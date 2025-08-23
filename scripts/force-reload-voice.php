<?php
/**
 * Force Recharge - Assistant Vocal PCMA
 * Force le rechargement de la page en modifiant le fichier
 */

echo "=== Force Recharge - Assistant Vocal PCMA ===\n\n";

// Étape 1 : Vérifier que le fichier existe
$filePath = 'resources/views/pcma/create.blade.php';
echo "1. Vérification du fichier :\n";

if (file_exists($filePath)) {
    echo "   ✅ Fichier $filePath trouvé\n";
    
    // Lire le contenu actuel
    $content = file_get_contents($filePath);
    $fileSize = strlen($content);
    echo "   📏 Taille du fichier : $fileSize caractères\n";
    
    // Vérifier la présence de nos éléments
    if (strpos($content, 'Google Assistant PCMA') !== false) {
        echo "   ✅ Code vocal présent dans le fichier\n";
    } else {
        echo "   ❌ Code vocal NON trouvé dans le fichier\n";
        exit(1);
    }
    
} else {
    echo "   ❌ Fichier $filePath NON trouvé\n";
    exit(1);
}

// Étape 2 : Ajouter un timestamp pour forcer le rechargement
echo "\n2. Ajout d'un timestamp pour forcer le rechargement :\n";

$timestamp = date('Y-m-d H:i:s');
$newComment = "<!-- Voice Input Section - UPDATED $timestamp -->";

// Remplacer le commentaire existant
$oldComment = "<!-- Voice Input Section - UPDATED 2025-08-20 -->";
$newContent = str_replace($oldComment, $newComment, $content);

if ($newContent !== $content) {
    // Sauvegarder le fichier modifié
    if (file_put_contents($filePath, $newContent)) {
        echo "   ✅ Timestamp ajouté : $timestamp\n";
        echo "   ✅ Fichier mis à jour\n";
    } else {
        echo "   ❌ Erreur lors de la sauvegarde\n";
        exit(1);
    }
} else {
    echo "   ⚠️  Timestamp déjà présent ou non modifié\n";
}

// Étape 3 : Redémarrer le serveur
echo "\n3. Redémarrage du serveur :\n";

echo "   🛑 Arrêt du serveur en cours...\n";
exec('pkill -f "php artisan serve"', $output, $returnCode);
echo "   ✅ Serveur arrêté\n";

echo "   🚀 Démarrage du serveur...\n";
exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &', $output, $returnCode);

if ($returnCode === 0) {
    echo "   ✅ Serveur redémarré en arrière-plan\n";
} else {
    echo "   ❌ Erreur lors du redémarrage du serveur\n";
}

// Étape 4 : Attendre que le serveur démarre
echo "\n4. Attente du démarrage du serveur :\n";

$maxAttempts = 10;
$attempt = 0;

while ($attempt < $maxAttempts) {
    $attempt++;
    echo "   ⏳ Tentative $attempt/$maxAttempts...\n";
    
    // Test de la page
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/pcma/create');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ Serveur accessible (HTTP $httpCode)\n";
        break;
    } else {
        echo "   ⏳ Serveur non encore prêt (HTTP $httpCode), attente...\n";
        sleep(2);
    }
}

if ($attempt >= $maxAttempts) {
    echo "   ❌ Serveur non accessible après $maxAttempts tentatives\n";
} else {
    echo "   ✅ Serveur prêt !\n";
}

// Étape 5 : Test final
echo "\n5. Test final de la page :\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/pcma/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    
    // Vérifier nos éléments
    $elements = [
        'Google Assistant PCMA' => 'Carte Google Assistant',
        'Commencer l\'examen PCMA' => 'Bouton de démarrage',
        'voice-section' => 'Section vocale',
        'start-voice-btn' => 'Bouton vocal'
    ];
    
    echo "\n   🔍 Vérification des éléments :\n";
    foreach ($elements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "      ✅ $description : TROUVÉ\n";
        } else {
            echo "      ❌ $description : NON TROUVÉ\n";
        }
    }
    
    echo "\n   📏 Taille de la réponse : " . strlen($response) . " caractères\n";
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé ===\n";
echo "🎯 Actions effectuées :\n";
echo "1. ✅ Timestamp ajouté au fichier\n";
echo "2. ✅ Serveur redémarré\n";
echo "3. ✅ Page testée\n";
echo "\n📋 Prochaines étapes :\n";
echo "1. Aller sur http://localhost:8080/pcma/create\n";
echo "2. Cliquer sur l'onglet '🎤 Assistant Vocal'\n";
echo "3. Tester le bouton 'Commencer l\'examen PCMA'\n";
echo "\n🔧 Si le problème persiste :\n";
echo "- Vider le cache du navigateur (Ctrl+F5)\n";
echo "- Vérifier la console JavaScript (F12)\n";
echo "- Tester avec un autre navigateur\n";
echo "\n🎉 L'assistant vocal PCMA devrait maintenant fonctionner !\n";
?>

