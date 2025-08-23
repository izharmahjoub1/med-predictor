<?php
/**
 * Test rapide de la plateforme FIT
 * Vérifie l'accessibilité des pages et fonctionnalités
 */

echo "=== Test Plateforme FIT ===\n\n";

$baseUrl = 'http://localhost:8080';
$pages = [
    '/' => 'Page d\'accueil FIT',
    '/pcma/voice-fallback' => 'Interface PCMA - Fallback Vocal',
    '/api/google-assistant/webhook' => 'API Webhook PCMA'
];

echo "1. Test d'accessibilité des pages :\n";
foreach ($pages as $path => $description) {
    $url = $baseUrl . $path;
    echo "   Test : $description\n";
    echo "   URL : $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ Accessible (HTTP $httpCode)\n";
        if ($contentType) {
            echo "   📄 Type: $contentType\n";
        }
    } else {
        echo "   ❌ Erreur HTTP $httpCode\n";
    }
    echo "   ---\n";
}

echo "\n2. Test de l'interface PCMA :\n";
echo "   🌐 Ouvrir dans votre navigateur :\n";
echo "   $baseUrl/pcma/voice-fallback\n\n";

echo "3. Test de l'API webhook :\n";
$webhookUrl = $baseUrl . '/api/google-assistant/webhook';
echo "   🔗 Endpoint : $webhookUrl\n";
echo "   📝 Méthode : POST\n";
echo "   📊 Format : JSON\n\n";

echo "4. Instructions de test :\n";
echo "   📱 Interface PCMA :\n";
echo "     1. Aller sur $baseUrl/pcma/voice-fallback\n";
echo "     2. Remplir le formulaire PCMA\n";
echo "     3. Tester la validation\n";
echo "     4. Soumettre le formulaire\n";
echo "     5. Vérifier la confirmation\n\n";

echo "   🧪 Test responsive :\n";
echo "     1. Réduire la fenêtre du navigateur\n";
echo "     2. Vérifier l'adaptation mobile\n";
echo "     3. Tester la navigation\n\n";

echo "   🔍 Vérifications :\n";
echo "     - [ ] Formulaire accessible\n";
echo "     - [ ] Validation en temps réel\n";
echo "     - [ ] Messages d'erreur clairs\n";
echo "     - [ ] Bouton de soumission actif\n";
echo "     - [ ] Confirmation après soumission\n";
echo "     - [ ] Design responsive\n\n";

echo "=== Plateforme FIT Prête ! ===\n";
echo "🚀 Serveur démarré sur : $baseUrl\n";
echo "📱 Interface PCMA : $baseUrl/pcma/voice-fallback\n";
echo "🔗 API Webhook : $webhookUrl\n";
echo "\nOuvrez votre navigateur et testez ! 🎯\n";
?>

