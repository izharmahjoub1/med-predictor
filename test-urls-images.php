<?php
/**
 * Test des URLs d'Images FIFA Portal
 */

echo "🔍 TEST DES URLS D'IMAGES FIFA PORTAL\n";
echo "=====================================\n\n";

// Test 1: Vérifier l'accessibilité des URLs d'images
echo "1️⃣ TEST D'ACCESSIBILITÉ DES URLS D'IMAGES\n";
echo "------------------------------------------\n";

$urlsImages = [
    'https://cdn.futbin.com/content/fifa23/img/players/122.png' => 'Photo FIFA ID 122',
    'https://flagcdn.com/w40/ar.png' => 'Drapeau Argentine',
    'https://logos-world.net/wp-content/uploads/2020/06/Paris-Saint-Germain-PSG-Logo.png' => 'Logo PSG'
];

foreach ($urlsImages as $url => $description) {
    echo "🔍 Test de: $description\n";
    echo "   URL: $url\n";
    
    $headers = get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "   ✅ Statut: Accessible (200)\n";
    } elseif ($headers && strpos($headers[0], '404') !== false) {
        echo "   ❌ Statut: Non trouvé (404)\n";
    } else {
        echo "   ⚠️ Statut: Erreur ou inaccessible\n";
    }
    
    // Test de téléchargement
    $imageContent = @file_get_contents($url);
    if ($imageContent !== false) {
        $imageSize = strlen($imageContent);
        echo "   ✅ Téléchargement: Réussi ($imageSize bytes)\n";
    } else {
        echo "   ❌ Téléchargement: Échoué\n";
    }
    
    echo "   ---\n";
}

// Test 2: Vérifier les URLs alternatives
echo "\n2️⃣ TEST DES URLS ALTERNATIVES\n";
echo "-------------------------------\n";

$urlsAlternatives = [
    'https://www.ea.com/fifa/ultimate-team/web-app/content/24B23FDE-7A35-41C2-8A2-F83DFDB2E2A2/2024/fut/items/images/mobile/portraits/122.png' => 'Photo EA FIFA',
    'https://flagcdn.com/w40/gb.png' => 'Drapeau Angleterre (alternative)',
    'https://www.logofootball.net/wp-content/uploads/Chelsea-FC-Logo.png' => 'Logo Chelsea (alternative)'
];

foreach ($urlsAlternatives as $url => $description) {
    echo "🔍 Test de: $description\n";
    echo "   URL: $url\n";
    
    $headers = get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "   ✅ Statut: Accessible (200)\n";
    } elseif ($headers && strpos($headers[0], '404') !== false) {
        echo "   ❌ Statut: Non trouvé (404)\n";
    } else {
        echo "   ⚠️ Statut: Erreur ou inaccessible\n";
    }
    
    echo "   ---\n";
}

// Test 3: Vérifier les images locales
echo "\n3️⃣ TEST DES IMAGES LOCALES\n";
echo "----------------------------\n";

$imagesLocales = [
    '/images/players/default_player.svg' => 'Joueur par défaut',
    '/images/clubs/default_club.svg' => 'Club par défaut',
    '/images/flags/default_flag.svg' => 'Drapeau par défaut'
];

foreach ($imagesLocales as $path => $description) {
    $fullPath = __DIR__ . '/public' . $path;
    echo "🔍 Test de: $description\n";
    echo "   Chemin: $path\n";
    
    if (file_exists($fullPath)) {
        $fileSize = filesize($fullPath);
        echo "   ✅ Fichier: Existe ($fileSize bytes)\n";
    } else {
        echo "   ❌ Fichier: N'existe pas\n";
    }
    
    echo "   ---\n";
}

echo "\n4️⃣ RECOMMANDATIONS\n";
echo "-------------------\n";

echo "🔧 Si les URLs externes ne fonctionnent pas:\n";
echo "1. Utiliser des images locales en fallback\n";
echo "2. Créer des images par défaut\n";
echo "3. Utiliser des icônes SVG intégrées\n";
echo "4. Vérifier la connectivité internet\n";

echo "\n✅ Test des URLs d'images terminé !\n";

