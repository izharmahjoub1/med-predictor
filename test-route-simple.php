<?php

echo "🧪 Test simple de la route /pcma/voice-fallback\n";
echo "=============================================\n\n";

// 1. Vérifier que le fichier de vue existe
$viewFile = 'resources/views/pcma/voice-fallback.blade.php';
echo "📁 Fichier de vue: ";
if (file_exists($viewFile)) {
    echo "✅ TROUVÉ\n";
    $size = filesize($viewFile);
    echo "   📏 Taille: " . number_format($size) . " octets\n";
} else {
    echo "❌ MANQUANT\n";
}

// 2. Vérifier la route dans web.php
echo "\n🔍 Route dans web.php: ";
$webRoutes = file_get_contents('routes/web.php');
if (strpos($webRoutes, '/pcma/voice-fallback') !== false) {
    echo "✅ TROUVÉE\n";
} else {
    echo "❌ MANQUANTE\n";
}

// 3. Vérifier qu'il n'y a pas de conflit dans api.php
echo "🔍 Conflit dans api.php: ";
$apiRoutes = file_get_contents('routes/api.php');
if (strpos($apiRoutes, 'pcma/voice-fallback') !== false) {
    echo "⚠️  CONFLIT DÉTECTÉ\n";
} else {
    echo "✅ AUCUN CONFLIT\n";
}

// 4. Test de la route
echo "\n🌐 Test de la route...\n";
$url = 'http://localhost:8000/pcma/voice-fallback';

// Test simple avec cURL
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

echo "\n🎯 Résumé:\n";
echo "   - Route définie: " . (strpos($webRoutes, '/pcma/voice-fallback') !== false ? "✅" : "❌") . "\n";
echo "   - Fichier de vue: " . (file_exists($viewFile) ? "✅" : "❌") . "\n";
echo "   - Conflit API: " . (strpos($apiRoutes, 'pcma/voice-fallback') !== false ? "⚠️" : "✅") . "\n";
echo "   - Réponse HTTP: $httpCode\n";

echo "\n💡 Si vous obtenez toujours 404:\n";
echo "   1. Arrêtez le serveur Laravel (Ctrl+C)\n";
echo "   2. Redémarrez: php artisan serve --host=0.0.0.0 --port=8000\n";
echo "   3. Testez à nouveau: $url\n";
