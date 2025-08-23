<?php
/**
 * Script de diagnostic complet pour l'erreur 404 PCMA
 */

echo "🔍 Diagnostic complet de l'erreur 404 PCMA\n";
echo "==========================================\n\n";

// 1. Vérifier l'existence du fichier de vue
echo "📁 1. Vérification du fichier de vue\n";
$viewFile = 'resources/views/pcma/voice-fallback.blade.php';
if (file_exists($viewFile)) {
    echo "   ✅ Fichier de vue trouvé: $viewFile\n";
    echo "   📊 Taille: " . filesize($viewFile) . " octets\n";
} else {
    echo "   ❌ Fichier de vue manquant: $viewFile\n";
    echo "   💡 Création du dossier et du fichier...\n";
    
    // Créer le dossier
    if (!is_dir('resources/views/pcma')) {
        mkdir('resources/views/pcma', 0755, true);
        echo "   📁 Dossier créé: resources/views/pcma\n";
    }
    
    // Créer le fichier de vue minimal
    $minimalView = '<!DOCTYPE html>
<html>
<head>
    <title>PCMA Fallback</title>
</head>
<body>
    <h1>Interface PCMA de secours</h1>
    <p>Cette page fonctionne !</p>
</body>
</html>';
    
    file_put_contents($viewFile, $minimalView);
    echo "   📄 Fichier de vue créé avec contenu minimal\n";
}

// 2. Vérifier la configuration des routes
echo "\n📋 2. Vérification des routes\n";

// Vérifier web.php
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, 'pcma/voice-fallback') !== false) {
    echo "   ✅ Route trouvée dans routes/web.php\n";
    
    // Trouver la ligne exacte
    $lines = explode("\n", $webRoutes);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'pcma/voice-fallback') !== false) {
            echo "   📍 Ligne " . ($i + 1) . ": " . trim($line) . "\n";
            break;
        }
    }
} else {
    echo "   ❌ Route non trouvée dans routes/web.php\n";
    echo "   💡 Ajout de la route...\n";
    
    $routeToAdd = "\n// Interface web de fallback pour PCMA (complètement publique)\nRoute::get('/pcma/voice-fallback', function () {\n    return view('pcma.voice-fallback');\n})->name('pcma.voice-fallback');\n";
    
    file_put_contents('routes/web.php', $routeToAdd, FILE_APPEND);
    echo "   ✅ Route ajoutée à routes/web.php\n";
}

// Vérifier api.php
$apiRoutes = file_get_contents('routes/api.php');
if (strpos($apiRoutes, 'pcma/voice-fallback') !== false) {
    echo "   ⚠️  Route trouvée dans routes/api.php (à supprimer)\n";
    echo "   💡 Suppression de la route dupliquée...\n";
    
    // Supprimer les lignes contenant pcma/voice-fallback
    $lines = explode("\n", $apiRoutes);
    $filteredLines = [];
    foreach ($lines as $line) {
        if (strpos($line, 'pcma/voice-fallback') === false) {
            $filteredLines[] = $line;
        }
    }
    
    file_put_contents('routes/api.php', implode("\n", $filteredLines));
    echo "   ✅ Route supprimée de routes/api.php\n";
} else {
    echo "   ✅ Aucune route dupliquée dans routes/api.php\n";
}

// 3. Vérifier la structure des dossiers
echo "\n📂 3. Vérification de la structure des dossiers\n";
$dirs = [
    'resources/views',
    'resources/views/pcma',
    'routes'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "   ✅ Dossier: $dir\n";
    } else {
        echo "   ❌ Dossier manquant: $dir\n";
        mkdir($dir, 0755, true);
        echo "   📁 Dossier créé: $dir\n";
    }
}

// 4. Nettoyer le cache Laravel
echo "\n🧹 4. Nettoyage du cache Laravel\n";
$cacheCommands = [
    'php artisan route:clear',
    'php artisan config:clear',
    'php artisan view:clear',
    'php artisan cache:clear'
];

foreach ($cacheCommands as $command) {
    echo "   🔄 Exécution: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if (strpos($output, 'error') !== false) {
        echo "   ⚠️  Avertissement: " . trim($output) . "\n";
    } else {
        echo "   ✅ Succès: " . trim($output) . "\n";
    }
}

// 5. Vérifier les routes enregistrées
echo "\n📋 5. Vérification des routes enregistrées\n";
$routeList = shell_exec('php artisan route:list 2>&1');
if (strpos($routeList, 'pcma/voice-fallback') !== false) {
    echo "   ✅ Route PCMA trouvée dans la liste des routes\n";
} else {
    echo "   ❌ Route PCMA non trouvée dans la liste des routes\n";
    echo "   💡 Vérifiez la configuration et redémarrez le serveur\n";
}

// 6. Test de la route
echo "\n🧪 6. Test de la route PCMA\n";
$url = 'http://localhost:8000/pcma/voice-fallback';

// Test avec curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "   ❌ Erreur cURL: $error\n";
    echo "   💡 Le serveur Laravel n'est peut-être pas démarré\n";
} else {
    echo "   📊 Code HTTP: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "   ✅ Succès: La route PCMA fonctionne !\n";
    } elseif ($httpCode === 404) {
        echo "   ❌ Erreur 404: Route non trouvée\n";
        echo "   💡 Solutions possibles:\n";
        echo "      1. Redémarrer le serveur Laravel\n";
        echo "      2. Vérifier les permissions des fichiers\n";
        echo "      3. Vérifier la configuration .env\n";
    } elseif ($httpCode === 500) {
        echo "   ❌ Erreur 500: Erreur serveur interne\n";
        echo "   💡 Vérifiez les logs: tail -f storage/logs/laravel.log\n";
    } else {
        echo "   ❌ Erreur HTTP: $httpCode\n";
    }
}

// 7. Recommandations
echo "\n💡 7. Recommandations\n";
echo "   🔄 Redémarrez le serveur Laravel:\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n\n";
echo "   🧪 Testez à nouveau:\n";
echo "      http://localhost:8000/pcma/voice-fallback\n\n";
echo "   📊 Si le problème persiste:\n";
echo "      php scripts/test-fallback-route.php\n\n";

echo "🎯 Diagnostic terminé !\n";
echo "Vérifiez les résultats ci-dessus et suivez les recommandations.\n";
