<?php
echo "🧹 **Nettoyage des fonctions dupliquées**\n";
echo "🎯 **Problème** : Plusieurs fonctions startVoiceRecognition et initSpeechRecognition\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Compter les occurrences
$startCount = substr_count($content, 'function startVoiceRecognition()');
$initCount = substr_count($content, 'function initSpeechRecognition()');

echo "📊 Fonctions trouvées :\n";
echo "   - startVoiceRecognition: $startCount\n";
echo "   - initSpeechRecognition: $initCount\n";

if ($startCount <= 1 && $initCount <= 1) {
    echo "✅ Aucune duplication détectée\n";
    exit(0);
}

echo "🔧 Suppression des duplications...\n";

// Trouver la première occurrence de chaque fonction
$firstStart = strpos($content, 'function startVoiceRecognition()');
$firstInit = strpos($content, 'function initSpeechRecognition()');

// Extraire la première occurrence de startVoiceRecognition
$startStart = $firstStart;
$startEnd = strpos($content, 'function ', $startStart + 1);
if ($startEnd === false) {
    $startEnd = strlen($content);
}
$firstStartFunction = substr($content, $startStart, $startEnd - $startStart);

// Extraire la première occurrence de initSpeechRecognition
$initStart = $firstInit;
$initEnd = strpos($content, 'function ', $initStart + 1);
if ($initEnd === false) {
    $initEnd = strlen($content);
}
$firstInitFunction = substr($content, $initStart, $initEnd - $initStart);

// Supprimer TOUTES les occurrences
$content = preg_replace('/function startVoiceRecognition\(\)[^}]*}/s', '', $content);
$content = preg_replace('/function initSpeechRecognition\(\)[^}]*}/s', '', $content);

// Ajouter les fonctions uniques dans le bon ordre
$insertPos = strpos($content, '// Voice Input Section - UPDATED');
if ($insertPos !== false) {
    $insertPos = strpos($content, "\n", $insertPos) + 1;
    $newFunctions = "\n" . $firstInitFunction . "\n" . $firstStartFunction . "\n";
    $content = substr($content, 0, $insertPos) . $newFunctions . substr($content, $insertPos);
}

// Vérifier qu'il n'y a plus de duplications
$newStartCount = substr_count($content, 'function startVoiceRecognition()');
$newInitCount = substr_count($content, 'function initSpeechRecognition()');

echo "📊 Après nettoyage :\n";
echo "   - startVoiceRecognition: $newStartCount\n";
echo "   - initSpeechRecognition: $newInitCount\n";

if ($newStartCount == 1 && $newInitCount == 1) {
    echo "✅ Duplications supprimées avec succès\n";
    
    // Sauvegarder
    if (file_put_contents($file, $content)) {
        echo "✅ Fichier mis à jour\n";
        echo "🔄 Redémarrage du serveur...\n";
        
        exec('pkill -f "php artisan serve"');
        sleep(2);
        exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
        sleep(3);
        
        echo "✅ Serveur redémarré\n";
        echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
    } else {
        echo "❌ Erreur lors de la sauvegarde\n";
    }
} else {
    echo "❌ Échec du nettoyage\n";
}
?>

