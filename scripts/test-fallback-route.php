<?php
/**
 * Script de test pour la route de fallback PCMA
 */

echo "🧪 Test de la route de fallback PCMA\n";
echo "===================================\n\n";

$url = 'http://localhost:8000/pcma/voice-fallback';

echo "📡 Test de la route: $url\n";

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
    echo "❌ Erreur cURL: $error\n";
} else {
    echo "📊 Code HTTP: $httpCode\n";
    
    if ($httpCode === 200) {
        echo "✅ Succès: La route fonctionne !\n";
        
        // Vérifier le contenu
        if (strpos($response, 'Formulaire PCMA') !== false) {
            echo "✅ Contenu: La page PCMA se charge correctement\n";
        } else {
            echo "⚠️  Contenu: La page se charge mais le contenu PCMA n'est pas détecté\n";
        }
        
        // Afficher les premières lignes
        $lines = explode("\n", $response);
        echo "\n📄 Premières lignes de la réponse:\n";
        for ($i = 0; $i < min(5, count($lines)); $i++) {
            if (!empty(trim($lines[$i]))) {
                echo "   " . trim($lines[$i]) . "\n";
            }
        }
        
    } elseif ($httpCode === 404) {
        echo "❌ Erreur 404: Route non trouvée\n";
        echo "💡 Vérifiez que:\n";
        echo "   1. Le serveur Laravel fonctionne (php artisan serve)\n";
        echo "   2. La route est dans routes/web.php\n";
        echo "   3. Le fichier de vue existe (resources/views/pcma/voice-fallback.blade.php)\n";
    } elseif ($httpCode === 500) {
        echo "❌ Erreur 500: Erreur serveur interne\n";
        echo "💡 Vérifiez les logs Laravel: tail -f storage/logs/laravel.log\n";
    } else {
        echo "❌ Erreur HTTP: $httpCode\n";
    }
}

echo "\n🔗 URL de test: $url\n";
echo "📁 Fichier de vue: resources/views/pcma/voice-fallback.blade.php\n";
echo "📁 Routes: routes/web.php (ligne ~3730)\n";
