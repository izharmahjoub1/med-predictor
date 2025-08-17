<?php
/**
 * Analyse du Portal Joueur en Ligne
 */

echo "🔍 ANALYSE DU PORTAL JOUEUR EN LIGNE\n";
echo "====================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Récupérer le contenu du portail joueur
echo "1️⃣ RÉCUPÉRATION DU PORTAL JOUEUR\n";
echo "----------------------------------\n";

$portalUrl = "$baseUrl/portail-joueur/7";
echo "🎯 URL analysée: $portalUrl\n";

$response = file_get_contents($portalUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès au portail\n";
} else {
    echo "✅ Portail accessible\n";
    
    // Analyser la structure des images
    echo "\n2️⃣ ANALYSE DE LA STRUCTURE DES IMAGES\n";
    echo "--------------------------------------\n";
    
    // Compter toutes les images
    $imgCount = substr_count($response, '<img');
    echo "📊 Nombre total d'images: $imgCount\n";
    
    // Identifier les images principales
    $imagesPrincipales = [
        'futbin' => 'Photo FIFA Futbin',
        'ea.com' => 'Photo EA FIFA',
        'logos-world' => 'Logo Club',
        'flagcdn' => 'Drapeau Nation',
        'association' => 'Logo Association',
        'federation' => 'Logo Fédération'
    ];
    
    echo "\n🔍 Images principales trouvées:\n";
    foreach ($imagesPrincipales as $recherche => $description) {
        if (strpos($response, $recherche) !== false) {
            echo "  ✅ $description: trouvé\n";
        } else {
            echo "  ❌ $description: NON trouvé\n";
        }
    }
    
    // Analyser la structure des logos
    echo "\n3️⃣ ANALYSE DE LA STRUCTURE DES LOGOS\n";
    echo "------------------------------------\n";
    
    // Chercher la section des logos
    if (preg_match('/<div class="flex items-center.*?space-x-4.*?<\/div>/s', $response, $matches)) {
        echo "✅ Section des logos trouvée\n";
        echo "📄 Contenu de la section:\n";
        echo substr($matches[0], 0, 500) . "...\n";
    } else {
        echo "❌ Section des logos non trouvée\n";
    }
    
    // Analyser l'ordre des éléments
    echo "\n4️⃣ ORDRE DES ÉLÉMENTS DANS LA HERO\n";
    echo "-----------------------------------\n";
    
    // Chercher les éléments dans l'ordre
    $elements = [
        'Chelsea FC' => 'Nom du club',
        'The Football Association' => 'Nom de l\'association',
        'Argentine' => 'Nom de la nation'
    ];
    
    foreach ($elements as $recherche => $description) {
        if (strpos($response, $recherche) !== false) {
            echo "  ✅ $description: $recherche\n";
        } else {
            echo "  ❌ $description: NON trouvé\n";
        }
    }
    
    // Analyser les séparateurs
    $separateurs = substr_count($response, 'w-px h-6 bg-white/30');
    echo "\n🔍 Nombre de séparateurs verticaux: $separateurs\n";
    
    // Analyser la structure complète
    echo "\n5️⃣ STRUCTURE COMPLÈTE IDENTIFIÉE\n";
    echo "--------------------------------\n";
    
    if (preg_match('/<div class="flex items-center.*?justify-center.*?space-x-4.*?<\/div>/s', $response, $matches)) {
        $sectionLogos = $matches[0];
        
        // Compter les divs d'images
        $divImages = substr_count($sectionLogos, '<div class="flex items-center space-x-2">');
        echo "📊 Nombre de blocs d'images: $divImages\n";
        
        // Compter les séparateurs
        $separateursSection = substr_count($sectionLogos, 'w-px h-6 bg-white/30');
        echo "📊 Nombre de séparateurs dans la section: $separateursSection\n";
        
        echo "\n📄 Structure de la section logos:\n";
        echo substr($sectionLogos, 0, 800) . "...\n";
    }
}

echo "\n6️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-------------------------------\n";

echo "🎯 Structure attendue:\n";
echo "1. Logo du club (Chelsea FC)\n";
echo "2. Logo de l'association (The Football Association)\n";
echo "3. Drapeau de la nation (Argentine)\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Vérifier la structure réelle dans le navigateur\n";
echo "2. Identifier le logo d'association manquant\n";
echo "3. Corriger la structure dans le code\n";

echo "\n✅ Analyse du portail joueur en ligne terminée !\n";

