<?php
/**
 * Test des Vraies Images FIFA Portal
 */

echo "🔍 TEST DES VRAIES IMAGES FIFA PORTAL\n";
echo "=====================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Vérifier que les vraies images sont maintenant intégrées
echo "1️⃣ VÉRIFICATION DES VRAIES IMAGES INTÉGRÉES\n";
echo "--------------------------------------------\n";

$testUrl = "$baseUrl/fifa-portal";
echo "🎯 URL testée: $testUrl\n";

$response = file_get_contents($testUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès\n";
} else {
    echo "✅ Page accessible\n";
    
    // Vérifier que les vraies images sont présentes
    $vraiesImages = [
        'https://cdn.futbin.com/content/fifa23/img/players/15023.png' => 'Photo FIFA Futbin',
        'https://logos-world.net/wp-content/uploads/2020/06/Paris-Saint-Germain-PSG-Logo.png' => 'Logo PSG',
        'https://flagcdn.com/w40/ar.png' => 'Drapeau Argentine'
    ];
    
    echo "\n🔍 Vérification des vraies images:\n";
    foreach ($vraiesImages as $url => $description) {
        if (strpos($response, $url) !== false) {
            echo "  ✅ $description: $url\n";
        } else {
            echo "  ❌ $description: NON trouvé\n";
        }
    }
    
    // Vérifier que les émojis ont été supprimés
    if (strpos($response, '👤') === false && strpos($response, '🏟️') === false && strpos($response, '🏳️') === false) {
        echo "✅ Émojis supprimés du HTML\n";
    } else {
        echo "❌ Émojis encore présents dans le HTML\n";
    }
    
    // Compter les images
    $imgCount = substr_count($response, '<img');
    echo "📊 Nombre total d'images: $imgCount\n";
    
    // Vérifier les commentaires indiquant la logique du portal joueurs
    if (strpos($response, 'MÊME LOGIQUE QUE PORTAL JOUEURS') !== false) {
        echo "✅ Commentaires de logique portal joueurs présents\n";
    } else {
        echo "❌ Commentaires de logique portal joueurs NON présents\n";
    }
}

echo "\n2️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-----------------------------\n";

echo "📋 État des Vraies Images:\n";
echo "  • Photo FIFA Futbin: " . (strpos($response, 'https://cdn.futbin.com') !== false ? "✅" : "❌") . "\n";
echo "  • Logo PSG: " . (strpos($response, 'https://logos-world.net') !== false ? "✅" : "❌") . "\n";
echo "  • Drapeau Argentine: " . (strpos($response, 'https://flagcdn.com') !== false ? "✅" : "❌") . "\n";
echo "  • Émojis supprimés: " . (strpos($response, '👤') === false ? "✅" : "❌") . "\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Ouvrir $testUrl dans le navigateur\n";
echo "2. Vérifier que de vraies images s'affichent (pas d'émojis)\n";
echo "3. Vérifier que les images correspondent au portail joueurs\n";
echo "4. Tester avec un joueur spécifique: $testUrl?player_id=8\n";

echo "\n✅ Test des vraies images terminé !\n";

