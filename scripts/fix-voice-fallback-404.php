<?php

echo "🔧 Diagnostic et correction de la route /pcma/voice-fallback\n";
echo "========================================================\n\n";

// 1. Vérifier que le fichier de vue existe
$viewFile = 'resources/views/pcma/voice-fallback.blade.php';
echo "📁 Vérification du fichier de vue...\n";
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé: $viewFile\n";
} else {
    echo "❌ Fichier de vue manquant: $viewFile\n";
    echo "   Création du fichier...\n";
    
    // Créer le répertoire si nécessaire
    $dir = dirname($viewFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "   📁 Répertoire créé: $dir\n";
    }
    
    // Créer un fichier de vue simple pour test
    $simpleView = '<!DOCTYPE html>
<html>
<head>
    <title>PCMA Voice Fallback</title>
</head>
<body>
    <h1>PCMA Voice Fallback</h1>
    <p>Interface de secours pour PCMA</p>
</body>
</html>';
    
    file_put_contents($viewFile, $simpleView);
    echo "   📄 Fichier de vue créé\n";
}

// 2. Vérifier la route dans web.php
echo "\n🔍 Vérification de la route dans web.php...\n";
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, '/pcma/voice-fallback') !== false) {
    echo "✅ Route trouvée dans web.php\n";
} else {
    echo "❌ Route non trouvée dans web.php\n";
    echo "   Ajout de la route...\n";
    
    $routeToAdd = "\n// Interface web de fallback pour PCMA (complètement publique)\nRoute::get('/pcma/voice-fallback', function () {\n    return view('pcma.voice-fallback');\n})->name('pcma.voice-fallback');\n";
    
    $webRoutes .= $routeToAdd;
    file_put_contents('routes/web.php', $webRoutes);
    echo "   ✅ Route ajoutée\n";
}

// 3. Vérifier qu'il n'y a pas de conflit dans api.php
echo "\n🔍 Vérification des conflits dans api.php...\n";
$apiRoutes = file_get_contents('routes/api.php');
if (strpos($apiRoutes, 'pcma/voice-fallback') !== false) {
    echo "⚠️  Route trouvée dans api.php - suppression...\n";
    
    $lines = explode("\n", $apiRoutes);
    $newLines = [];
    foreach ($lines as $line) {
        if (strpos($line, 'pcma/voice-fallback') === false) {
            $newLines[] = $line;
        }
    }
    
    $apiRoutes = implode("\n", $newLines);
    file_put_contents('routes/api.php', $apiRoutes);
    echo "   ✅ Route supprimée de api.php\n";
} else {
    echo "✅ Aucun conflit dans api.php\n";
}

// 4. Vider le cache des routes
echo "\n🧹 Nettoyage du cache...\n";
echo "   Exécutez ces commandes manuellement:\n";
echo "   php artisan route:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";

// 5. Vérifier la liste des routes
echo "\n📋 Vérification de la liste des routes...\n";
echo "   Exécutez: php artisan route:list | grep pcma\n";

// 6. Test de la route
echo "\n🧪 Test de la route...\n";
echo "   Testez: http://localhost:8000/pcma/voice-fallback\n";

// 7. Vérification du serveur
echo "\n🚀 Vérification du serveur...\n";
echo "   Assurez-vous que le serveur Laravel est démarré:\n";
echo "   php artisan serve --host=0.0.0.0 --port=8000\n";

echo "\n🎯 Diagnostic terminé !\n";
echo "\n💡 Étapes de résolution:\n";
echo "   1. Exécutez les commandes de nettoyage du cache\n";
echo "   2. Redémarrez le serveur Laravel\n";
echo "   3. Testez la route dans votre navigateur\n";
echo "   4. Si le problème persiste, vérifiez les logs Laravel\n";
