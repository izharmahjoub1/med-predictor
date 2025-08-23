<?php
echo "🔧 **Correction de la syntaxe Blade**\n";
echo "🎯 **Problème** : @endpush sans @push correspondant\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

echo "📏 Taille du fichier: " . number_format(strlen($content)) . " caractères\n";

// Vérifier les directives Blade
$pushCount = substr_count($content, '@push');
$endpushCount = substr_count($content, '@endpush');

echo "📊 Directives Blade trouvées:\n";
echo "   @push: $pushCount\n";
echo "   @endpush: $endpushCount\n";

if ($pushCount === 0 && $endpushCount > 0) {
    echo "❌ Problème détecté: @endpush sans @push correspondant\n";
    echo "🔧 Correction en cours...\n";
    
    // Supprimer le @endpush orphelin
    $content = str_replace('@endpush', '', $content);
    
    // Corriger la balise script mal fermée
    $content = str_replace('</script>>', '</script>', $content);
    
    echo "✅ @endpush supprimé\n";
    echo "✅ Balise script corrigée\n";
} elseif ($pushCount === $endpushCount) {
    echo "✅ Directives @push/@endpush équilibrées\n";
} else {
    echo "⚠️ Déséquilibre des directives @push/@endpush\n";
}

// Vérifier l'équilibre des balises
$divOpen = substr_count($content, '<div');
$divClose = substr_count($content, '</div>');
$scriptOpen = substr_count($content, '<script');
$scriptClose = substr_count($content, '</script>');

echo "\n🔍 Équilibre des balises:\n";
echo "   Div: $divOpen ouvertes, $divClose fermées - " . ($divOpen === $divClose ? "✅ Équilibré" : "❌ Déséquilibré") . "\n";
echo "   Script: $scriptOpen ouvertes, $scriptClose fermées - " . ($scriptOpen === $scriptClose ? "✅ Équilibré" : "❌ Déséquilibré") . "\n";

// Sauvegarder
if (file_put_contents($file, $content)) {
    echo "✅ Fichier corrigé avec succès\n";
    
    // Vérifier que la correction a fonctionné
    $newContent = file_get_contents($file);
    $newPushCount = substr_count($newContent, '@push');
    $newEndpushCount = substr_count($newContent, '@endpush');
    
    echo "📊 Après correction:\n";
    echo "   @push: $newPushCount\n";
    echo "   @endpush: $newEndpushCount\n";
    
    if ($newPushCount === $newEndpushCount) {
        echo "✅ Syntaxe Blade corrigée\n";
        echo "🔄 Redémarrage du serveur...\n";
        
        exec('pkill -f "php artisan serve"');
        sleep(2);
        exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
        sleep(3);
        
        echo "✅ Serveur redémarré\n";
        echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
    } else {
        echo "❌ La correction n'a pas fonctionné\n";
    }
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}
?>

