<?php
echo "🧪 **Test simple avec curl**\n";
echo "🎯 Test de la route /test-pcma-simple\n";

$url = 'http://localhost:8080/test-pcma-simple';

echo "📡 Test de $url...\n";

// Test avec curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Code HTTP: $httpCode\n";

if ($error) {
    echo "❌ Erreur curl: $error\n";
    exit(1);
}

if ($httpCode === 200) {
    echo "✅ Page accessible\n";
    
    // Extraire le corps de la réponse
    $bodyStart = strpos($response, "\r\n\r\n");
    if ($bodyStart !== false) {
        $body = substr($response, $bodyStart + 4);
        echo "📏 Taille du corps: " . strlen($body) . " caractères\n";
        
        // Vérifier les éléments critiques
        $checks = [
            'Google Assistant' => 'Titre de la carte',
            'Commencer l\'examen PCMA' => 'Bouton de démarrage',
            'voice-section' => 'Section vocale',
            'function startVoiceRecognition()' => 'Fonction de démarrage',
            'recognition = new' => 'Initialisation de recognition'
        ];
        
        foreach ($checks as $search => $description) {
            if (strpos($body, $search) !== false) {
                echo "✅ $description\n";
            } else {
                echo "❌ $description - NON TROUVÉ\n";
            }
        }
    }
} else {
    echo "❌ Erreur HTTP $httpCode\n";
    
    // Afficher la réponse d'erreur
    $bodyStart = strpos($response, "\r\n\r\n");
    if ($bodyStart !== false) {
        $body = substr($response, $bodyStart + 4);
        echo "📄 Corps de l'erreur:\n";
        echo substr($body, 0, 500) . "\n";
    }
}
?>

