<?php
/**
 * Test du Chargement des Images FIFA Portal
 */

echo "🔍 TEST DU CHARGEMENT DES IMAGES FIFA PORTAL\n";
echo "===========================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Vérifier la structure HTML des images
echo "1️⃣ VÉRIFICATION DE LA STRUCTURE HTML DES IMAGES\n";
echo "-----------------------------------------------\n";

$testUrl = "$baseUrl/fifa-portal";
echo "🎯 URL testée: $testUrl\n";

$response = file_get_contents($testUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès\n";
} else {
    echo "✅ Page accessible\n";
    
    // Vérifier que les émojis ont été supprimés
    if (strpos($response, '👤') === false && strpos($response, '🏟️') === false && strpos($response, '🏳️') === false) {
        echo "✅ Émojis supprimés du HTML\n";
    } else {
        echo "❌ Émojis encore présents dans le HTML\n";
        
        // Identifier où sont les émojis
        if (strpos($response, '👤') !== false) echo "  • 👤 trouvé dans le HTML\n";
        if (strpos($response, '🏟️') !== false) echo "  • 🏟️ trouvé dans le HTML\n";
        if (strpos($response, '🏳️') !== false) echo "  • 🏳️ trouvé dans le HTML\n";
    }
    
    // Vérifier la présence des balises img
    if (strpos($response, '<img') !== false) {
        echo "✅ Balises HTML <img> présentes\n";
        
        // Compter et identifier les images
        $imgCount = substr_count($response, '<img');
        echo "📊 Nombre d'images trouvées: $imgCount\n";
        
        // Extraire les informations des images
        preg_match_all('/<img[^>]+>/', $response, $matches);
        echo "\n🔍 Détail des images trouvées:\n";
        foreach ($matches[0] as $index => $imgTag) {
            echo "  " . ($index + 1) . ". $imgTag\n";
        }
    } else {
        echo "❌ Aucune balise HTML <img> trouvée\n";
    }
    
    // Vérifier les IDs des éléments d'images
    $imageIds = [
        'hero-player-photo',
        'hero-club-logo',
        'hero-flag'
    ];
    
    echo "\n🔍 Vérification des IDs des éléments d'images:\n";
    foreach ($imageIds as $id) {
        if (strpos($response, $id) !== false) {
            echo "  ✅ $id: présent\n";
        } else {
            echo "  ❌ $id: NON présent\n";
        }
    }
    
    // Vérifier les sources d'images
    $imageSources = [
        'https://cdn.futbin.com' => 'Photos FIFA Futbin',
        'https://www.ea.com/fifa' => 'Photos FIFA EA',
        'https://flagcdn.com' => 'Drapeaux Flag CDN',
        'https://logos-world.net' => 'Logos Clubs'
    ];
    
    echo "\n🔍 Vérification des sources d'images:\n";
    foreach ($imageSources as $url => $description) {
        if (strpos($response, $url) !== false) {
            echo "  ✅ $description: $url\n";
        } else {
            echo "  ❌ $description: NON trouvé\n";
        }
    }
    
    // Vérifier la gestion d'erreur
    if (strpos($response, 'onerror') !== false) {
        echo "✅ Gestion d'erreur des images (onerror) configurée\n";
    } else {
        echo "❌ Gestion d'erreur des images NON configurée\n";
    }
    
    // Vérifier les fallbacks
    if (strpos($response, 'data:image/svg+xml;base64') !== false) {
        echo "✅ Fallbacks SVG en base64 configurés\n";
    } else {
        echo "❌ Fallbacks SVG NON configurés\n";
    }
}

echo "\n2️⃣ DIAGNOSTIC DES PROBLÈMES POTENTIELS\n";
echo "----------------------------------------\n";

echo "🔍 Problèmes identifiés:\n";

// Vérifier si les émojis sont encore dans le JavaScript
if (strpos($response, '⚽') !== false || strpos($response, '🏟️') !== false || strpos($response, '🏳️') !== false) {
    echo "  ❌ Émojis encore présents dans le JavaScript\n";
} else {
    echo "  ✅ Émojis supprimés du JavaScript\n";
}

// Vérifier si les fonctions d'images sont bien présentes
$imageFunctions = [
    'updateHeroImages',
    'updatePlayerPhoto',
    'updateClubLogo',
    'updateNationalityFlag'
];

echo "\n🔍 Vérification des fonctions d'images:\n";
foreach ($imageFunctions as $function) {
    if (strpos($response, $function) !== false) {
        echo "  ✅ $function: présente\n";
    } else {
        echo "  ❌ $function: NON présente\n";
    }
}

echo "\n3️⃣ RECOMMANDATIONS POUR RÉSOUDRE LE PROBLÈME\n";
echo "-----------------------------------------------\n";

echo "🔧 Actions à effectuer:\n";
echo "1. Vérifier que le navigateur charge bien les images\n";
echo "2. Ouvrir la console développeur (F12) pour voir les erreurs\n";
echo "3. Vérifier que les URLs d'images sont accessibles\n";
echo "4. Tester avec un joueur spécifique: $testUrl?player_id=8\n";
echo "5. Vérifier que updateHeroImages() est appelée\n";

echo "\n📊 État global des images:\n";
echo "  • HTML: " . (strpos($response, '<img') !== false ? "✅" : "❌") . "\n";
echo "  • JavaScript: " . (strpos($response, 'updateHeroImages') !== false ? "✅" : "❌") . "\n";
echo "  • Sources externes: " . (strpos($response, 'https://cdn.futbin.com') !== false ? "✅" : "❌") . "\n";
echo "  • Gestion d'erreur: " . (strpos($response, 'onerror') !== false ? "✅" : "❌") . "\n";

echo "\n✅ Test de chargement des images terminé !\n";

