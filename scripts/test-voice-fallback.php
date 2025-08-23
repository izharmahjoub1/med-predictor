<?php

echo "🧪 Test de la route /pcma/voice-fallback\n";
echo "=====================================\n\n";

// Test de la route
$url = 'http://localhost:8000/pcma/voice-fallback';

echo "📡 Test de la route: $url\n";

// Utilisation de cURL pour tester la route
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Code de réponse HTTP: $httpCode\n";

if ($error) {
    echo "❌ Erreur cURL: $error\n";
} else {
    if ($httpCode === 200) {
        echo "✅ Route accessible avec succès !\n";
        
        // Extraire le contenu HTML
        $bodyStart = strpos($response, "\r\n\r\n");
        if ($bodyStart !== false) {
            $html = substr($response, $bodyStart + 4);
            
            // Vérifier la présence de contenu PCMA
            if (strpos($html, 'Formulaire PCMA') !== false) {
                echo "✅ Contenu PCMA détecté dans la réponse\n";
            } else {
                echo "⚠️  Contenu PCMA non détecté dans la réponse\n";
            }
            
            if (strpos($html, 'Vue.js') !== false || strpos($html, 'vue.global.js') !== false) {
                echo "✅ Vue.js détecté dans la réponse\n";
            } else {
                echo "⚠️  Vue.js non détecté dans la réponse\n";
            }
        }
    } elseif ($httpCode === 302) {
        echo "⚠️  Redirection détectée (peut-être vers login)\n";
    } elseif ($httpCode === 404) {
        echo "❌ Route non trouvée (404)\n";
    } else {
        echo "⚠️  Code de réponse inattendu: $httpCode\n";
    }
}

echo "\n🎯 Test terminé !\n";
echo "\n💡 Si vous obtenez une redirection, essayez d'ouvrir directement l'URL dans votre navigateur.\n";
echo "💡 Si vous obtenez une erreur 404, vérifiez que le serveur Laravel est démarré.\n";
