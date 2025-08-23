<?php

echo "🧹 Nettoyage manuel du cache Laravel\n";
echo "===================================\n\n";

// 1. Supprimer les fichiers de cache
$cacheFiles = [
    'bootstrap/cache/routes.php',
    'bootstrap/cache/config.php',
    'bootstrap/cache/packages.php',
    'bootstrap/cache/services.php'
];

echo "🗂️  Suppression des fichiers de cache...\n";
foreach ($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile)) {
        if (unlink($cacheFile)) {
            echo "✅ Supprimé: $cacheFile\n";
        } else {
            echo "❌ Erreur lors de la suppression: $cacheFile\n";
        }
    } else {
        echo "ℹ️  Fichier non trouvé: $cacheFile\n";
    }
}

// 2. Vérifier que la route est bien définie
echo "\n🔍 Vérification de la route /pcma/voice-fallback...\n";
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, '/pcma/voice-fallback') !== false) {
    echo "✅ Route trouvée dans web.php\n";
} else {
    echo "❌ Route non trouvée - ajout...\n";
    
    $routeToAdd = "\n// Interface web de fallback pour PCMA (complètement publique)\nRoute::get('/pcma/voice-fallback', function () {\n    return view('pcma.voice-fallback');\n})->name('pcma.voice-fallback');\n";
    
    $webRoutes .= $routeToAdd;
    if (file_put_contents('routes/web.php', $webRoutes)) {
        echo "✅ Route ajoutée avec succès\n";
    } else {
        echo "❌ Erreur lors de l'ajout de la route\n";
    }
}

// 3. Vérifier le fichier de vue
echo "\n📁 Vérification du fichier de vue...\n";
$viewFile = 'resources/views/pcma/voice-fallback.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé: $viewFile\n";
    $viewSize = filesize($viewFile);
    echo "   📏 Taille: " . number_format($viewSize) . " octets\n";
} else {
    echo "❌ Fichier de vue manquant - création...\n";
    
    // Créer le répertoire si nécessaire
    $dir = dirname($viewFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "   📁 Répertoire créé: $dir\n";
    }
    
    // Créer un fichier de vue simple
    $simpleView = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCMA Voice Fallback</title>
</head>
<body>
    <h1>🏥 PCMA Voice Fallback</h1>
    <p>Interface de secours pour PCMA</p>
    <p>✅ Route fonctionnelle !</p>
</body>
</html>';
    
    if (file_put_contents($viewFile, $simpleView)) {
        echo "✅ Fichier de vue créé\n";
    } else {
        echo "❌ Erreur lors de la création du fichier de vue\n";
    }
}

// 4. Instructions finales
echo "\n🎯 Nettoyage terminé !\n";
echo "\n📋 Prochaines étapes:\n";
echo "   1. Arrêtez le serveur Laravel (Ctrl+C)\n";
echo "   2. Redémarrez le serveur:\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n";
echo "   3. Testez la route:\n";
echo "      http://localhost:8000/pcma/voice-fallback\n";
echo "\n💡 Si le problème persiste, vérifiez les logs Laravel\n";
echo "   storage/logs/laravel.log\n";
