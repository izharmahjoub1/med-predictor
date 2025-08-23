<?php

echo "🧪 Test et correction de la route /pcma/voice-fallback\n";
echo "==================================================\n\n";

// 1. Vérifier que le fichier de vue existe
$viewFile = 'resources/views/pcma/voice-fallback.blade.php';
echo "📁 Vérification du fichier de vue...\n";
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé: $viewFile\n";
    $viewSize = filesize($viewFile);
    echo "   📏 Taille: " . number_format($viewSize) . " octets\n";
} else {
    echo "❌ Fichier de vue manquant: $viewFile\n";
}

// 2. Vérifier la route dans web.php
echo "\n🔍 Vérification de la route dans web.php...\n";
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, '/pcma/voice-fallback') !== false) {
    echo "✅ Route trouvée dans web.php\n";
    
    // Trouver la ligne exacte
    $lines = explode("\n", $webRoutes);
    foreach ($lines as $lineNum => $line) {
        if (strpos($line, '/pcma/voice-fallback') !== false) {
            echo "   📍 Ligne " . ($lineNum + 1) . ": " . trim($line) . "\n";
            break;
        }
    }
} else {
    echo "❌ Route non trouvée dans web.php\n";
}

// 3. Vérifier qu'il n'y a pas de conflit dans api.php
echo "\n🔍 Vérification des conflits dans api.php...\n";
$apiRoutes = file_get_contents('routes/api.php');
if (strpos($apiRoutes, 'pcma/voice-fallback') !== false) {
    echo "⚠️  Route trouvée dans api.php - CONFLIT DÉTECTÉ !\n";
} else {
    echo "✅ Aucun conflit dans api.php\n";
}

// 4. Vérifier le cache des routes (fichiers de cache)
echo "\n🗂️  Vérification des fichiers de cache...\n";
$cacheFiles = [
    'bootstrap/cache/routes.php',
    'bootstrap/cache/config.php',
    'bootstrap/cache/packages.php'
];

foreach ($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile)) {
        echo "⚠️  Fichier de cache trouvé: $cacheFile\n";
        echo "   💡 Ce fichier peut causer des problèmes de routage\n";
    } else {
        echo "✅ Aucun fichier de cache: $cacheFile\n";
    }
}

// 5. Test de la route avec cURL
echo "\n🌐 Test de la route avec cURL...\n";
$url = 'http://localhost:8000/pcma/voice-fallback';

// Vérifier si le serveur répond
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Erreur cURL: $error\n";
    echo "   💡 Le serveur Laravel n'est peut-être pas démarré\n";
} else {
    echo "📊 Code de réponse HTTP: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "✅ Route accessible avec succès !\n";
    } elseif ($httpCode === 302) {
        echo "⚠️  Redirection détectée (peut-être vers login)\n";
    } elseif ($httpCode === 404) {
        echo "❌ Route non trouvée (404)\n";
        echo "   🔧 Problème de routage détecté\n";
    } else {
        echo "⚠️  Code de réponse inattendu: $httpCode\n";
    }
}

// 6. Recommandations
echo "\n🎯 Recommandations pour résoudre le 404:\n";
echo "   1. Arrêtez le serveur Laravel (Ctrl+C)\n";
echo "   2. Supprimez les fichiers de cache:\n";
echo "      rm -f bootstrap/cache/*.php\n";
echo "   3. Redémarrez le serveur:\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n";
echo "   4. Testez à nouveau: $url\n";

echo "\n💡 Si le problème persiste, vérifiez:\n";
echo "   - Les logs Laravel (storage/logs/laravel.log)\n";
echo "   - La configuration du serveur web\n";
echo "   - Les permissions des fichiers\n";

echo "\n🚀 Test terminé !\n";
