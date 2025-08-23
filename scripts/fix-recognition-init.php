<?php
echo "🔧 **Correction de l'initialisation de la reconnaissance vocale**\n";
echo "🎯 **Problème** : recognition is not defined - initSpeechRecognition() n'est pas appelé\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Vérifier si initSpeechRecognition est déjà présent
if (strpos($content, 'function initSpeechRecognition()') === false) {
    echo "❌ Fonction initSpeechRecognition() non trouvée\n";
    exit(1);
}

// Vérifier si startVoiceRecognition appelle initSpeechRecognition
if (strpos($content, 'initSpeechRecognition()') === false) {
    echo "❌ Appel à initSpeechRecognition() non trouvé dans startVoiceRecognition\n";
    exit(1);
}

// Vérifier l'ordre des fonctions
$initPos = strpos($content, 'function initSpeechRecognition()');
$startPos = strpos($content, 'function startVoiceRecognition()');

if ($startPos < $initPos) {
    echo "❌ startVoiceRecognition() est définie AVANT initSpeechRecognition()\n";
    echo "🔧 Réorganisation de l'ordre des fonctions...\n";
    
    // Extraire les deux fonctions
    $initFunction = '';
    $startFunction = '';
    
    // Trouver initSpeechRecognition complète
    $initStart = strpos($content, 'function initSpeechRecognition()');
    $initEnd = strpos($content, 'function startVoiceRecognition()', $initStart);
    if ($initEnd === false) {
        $initEnd = strpos($content, 'function ', $initStart + 1);
    }
    if ($initEnd === false) {
        $initEnd = strlen($content);
    }
    $initFunction = substr($content, $initStart, $initEnd - $initStart);
    
    // Trouver startVoiceRecognition complète
    $startStart = strpos($content, 'function startVoiceRecognition()');
    $startEnd = strpos($content, 'function ', $startStart + 1);
    if ($startEnd === false) {
        $startEnd = strlen($content);
    }
    $startFunction = substr($content, $startStart, $startEnd - $startStart);
    
    // Supprimer les deux fonctions
    $content = str_replace($initFunction, '', $content);
    $content = str_replace($startFunction, '', $content);
    
    // Ajouter dans le bon ordre
    $insertPos = strpos($content, '// Voice Input Section - UPDATED');
    if ($insertPos !== false) {
        $insertPos = strpos($content, "\n", $insertPos) + 1;
        $newFunctions = "\n" . $initFunction . "\n" . $startFunction . "\n";
        $content = substr($content, 0, $insertPos) . $newFunctions . substr($content, $insertPos);
    }
    
    echo "✅ Fonctions réorganisées\n";
} else {
    echo "✅ Ordre des fonctions correct\n";
}

// Vérifier que startVoiceRecognition appelle bien initSpeechRecognition
if (strpos($content, 'initSpeechRecognition()') === false) {
    echo "❌ Appel à initSpeechRecognition() manquant\n";
    exit(1);
}

// Vérifier que la variable recognition est bien déclarée globalement
if (strpos($content, 'let recognition') === false && strpos($content, 'var recognition') === false) {
    echo "❌ Variable recognition non déclarée globalement\n";
    echo "🔧 Ajout de la déclaration globale...\n";
    
    $insertPos = strpos($content, '// Voice Input Section - UPDATED');
    if ($insertPos !== false) {
        $insertPos = strpos($content, "\n", $insertPos) + 1;
        $globalVar = "\nlet recognition = null;\n";
        $content = substr($content, 0, $insertPos) . $globalVar . substr($content, $insertPos);
    }
}

// Vérifier que initSpeechRecognition assigne bien à la variable globale
if (strpos($content, 'recognition = new') === false) {
    echo "❌ initSpeechRecognition n'initialise pas la variable recognition\n";
    exit(1);
}

// Sauvegarder
if (file_put_contents($file, $content)) {
    echo "✅ Fichier mis à jour avec succès\n";
    echo "🔄 Redémarrage du serveur Laravel...\n";
    
    // Redémarrer le serveur
    exec('pkill -f "php artisan serve"');
    sleep(2);
    exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
    sleep(3);
    
    echo "✅ Serveur redémarré\n";
    echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}
?>

