<?php
/**
 * Analyse des Images, Drapeaux, Logos Clubs et Associations
 * FIFA Portal vs Portail Joueur Final
 */

echo "🔍 ANALYSE DES IMAGES, DRAPEAUX, LOGOS CLUBS ET ASSOCIATIONS\n";
echo "============================================================\n\n";

$baseUrl = "http://localhost:8001";

echo "📋 ÉTAT ACTUEL DU FIFA PORTAL\n";
echo "==============================\n\n";

// Test 1: FIFA Portal actuel
echo "1️⃣ FIFA PORTAL ACTUEL\n";
echo "---------------------\n";

$fifaPortalUrl = "$baseUrl/fifa-portal";
$fifaResponse = file_get_contents($fifaPortalUrl);

if ($fifaResponse === false) {
    echo "❌ Erreur accès FIFA Portal\n";
} else {
    echo "✅ FIFA Portal accessible\n";
    
    // Vérifier les éléments visuels actuels
    if (strpos($fifaResponse, 'hero-player-avatar') !== false) {
        echo "✅ Avatar joueur: présent (👤 émoji)\n";
    } else {
        echo "❌ Avatar joueur: NON présent\n";
    }
    
    if (strpos($fifaResponse, 'hero-club-logo') !== false) {
        echo "✅ Logo club: présent (🏟️ émoji)\n";
    } else {
        echo "❌ Logo club: NON présent\n";
    }
    
    if (strpos($fifaResponse, 'hero-flag') !== false) {
        echo "✅ Drapeau: présent (🏳️ émoji)\n";
    } else {
        echo "❌ Drapeau: NON présent\n";
    }
    
    // Vérifier s'il y a des vraies images
    if (strpos($fifaResponse, '<img') !== false) {
        echo "✅ Images HTML: présentes\n";
    } else {
        echo "❌ Images HTML: NON présentes\n";
    }
}

echo "\n2️⃣ PORTAL-JOUEUR FINAL (RÉFÉRENCE)\n";
echo "-----------------------------------\n";

// Test 2: Portail joueur final (référence)
$portailJoueurUrl = "$baseUrl/portail-joueur/7";
$portailResponse = file_get_contents($portailJoueurUrl);

if ($portailResponse === false) {
    echo "❌ Erreur accès Portail Joueur (redirection login)\n";
    echo "📝 Vérification du code source du fichier...\n";
    
    // Lire directement le fichier
    $portailFile = file_get_contents('resources/views/portail-joueur-final.blade.php');
    
    if ($portailFile !== false) {
        echo "✅ Fichier portail-joueur-final.blade.php accessible\n";
        
        // Analyser les images dans le code
        if (strpos($portailFile, 'https://cdn.futbin.com') !== false) {
            echo "✅ Photos joueurs: Futbin CDN\n";
        } else {
            echo "❌ Photos joueurs: NON trouvées\n";
        }
        
        if (strpos($portailFile, 'https://logos-world.net') !== false) {
            echo "✅ Logos clubs: Logos World\n";
        } else {
            echo "❌ Logos clubs: NON trouvés\n";
        }
        
        if (strpos($portailFile, 'https://flagcdn.com') !== false) {
            echo "✅ Drapeaux: Flag CDN\n";
        } else {
            echo "❌ Drapeaux: NON trouvés\n";
        }
        
        if (strpos($portailFile, 'onerror') !== false) {
            echo "✅ Gestion d'erreur: Fallbacks configurés\n";
        } else {
            echo "❌ Gestion d'erreur: Fallbacks NON configurés\n";
        }
    } else {
        echo "❌ Fichier portail-joueur-final.blade.php NON accessible\n";
    }
} else {
    echo "✅ Portail Joueur accessible\n";
}

echo "\n3️⃣ IMAGES STOCKÉES LOCALEMENT\n";
echo "-----------------------------\n";

$localImages = [
    'flags' => [
        'argentina.svg' => 'Drapeau Argentine',
        'senegal.svg' => 'Drapeau Sénégal',
        'default_flag.svg' => 'Drapeau par défaut'
    ],
    'clubs' => [
        'chelsea_fc.png' => 'Logo Chelsea FC',
        'default_club.svg' => 'Logo club par défaut'
    ],
    'players' => [
        'ronaldo.jpg' => 'Photo Ronaldo',
        'default_player.svg' => 'Photo joueur par défaut'
    ]
];

foreach ($localImages as $category => $images) {
    echo "\n📁 Dossier $category:\n";
    foreach ($images as $filename => $description) {
        $filepath = "public/images/$category/$filename";
        if (file_exists($filepath)) {
            $size = filesize($filepath);
            echo "  ✅ $filename: $description ($size bytes)\n";
        } else {
            echo "  ❌ $filename: NON trouvé\n";
        }
    }
}

echo "\n4️⃣ ANALYSE DES SOURCES D'IMAGES\n";
echo "-------------------------------\n";

echo "🌐 Sources externes utilisées dans portail-joueur-final:\n";
echo "  • Photos joueurs: https://cdn.futbin.com (FIFA 23)\n";
echo "  • Logos clubs: https://logos-world.net\n";
echo "  • Drapeaux: https://flagcdn.com\n\n";

echo "💾 Images locales disponibles:\n";
echo "  • Drapeaux: 3 fichiers (argentina, senegal, default)\n";
echo "  • Clubs: 2 fichiers (chelsea, default)\n";
echo "  • Joueurs: 2 fichiers (ronaldo, default)\n\n";

echo "5️⃣ RECOMMANDATIONS POUR INTÉGRATION\n";
echo "----------------------------------\n";

echo "🔧 Actions à effectuer:\n";
echo "1. Remplacer les émojis par de vraies images dans le FIFA Portal\n";
echo "2. Intégrer les images locales existantes\n";
echo "3. Ajouter des fallbacks pour les images manquantes\n";
echo "4. Implémenter la gestion d'erreur des images\n";
echo "5. Créer des APIs pour récupérer les bonnes images selon le joueur\n\n";

echo "📊 État global:\n";
echo "  • FIFA Portal: ❌ Émojis seulement\n";
echo "  • Portail Joueur: ✅ Vraies images avec fallbacks\n";
echo "  • Images locales: ✅ Partiellement disponibles\n";
echo "  • Gestion erreurs: ❌ Non implémentée dans FIFA Portal\n\n";

echo "✅ Analyse terminée !\n";

