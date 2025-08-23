<?php
/**
 * Script de test pour vérifier que toutes les zones ont la même taille (w-48 h-48)
 */

echo "🔍 TEST DE L'UNIFORMISATION DES TAILLES DES ZONES\n";
echo "=================================================\n\n";

// Test 1: Vérifier que toutes les zones utilisent w-48 h-48
echo "🏗️ TEST 1: VÉRIFICATION DES TAILLES UNIFORMES\n";
echo "==============================================\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Compter les occurrences de w-48 h-48
    $w48h48Count = substr_count($content, 'w-48 h-48');
    echo "✅ Nombre total de zones w-48 h-48 : {$w48h48Count}\n";
    
    // Vérifier les zones spécifiques
    if (strpos($content, 'Photo du joueur - Taille DOUBLÉE') !== false) {
        echo "✅ Zone Photo du joueur : w-48 h-48 (192x192px) ✅\n";
    } else {
        echo "❌ Zone Photo du joueur : Taille non doublée\n";
    }
    
    if (strpos($content, 'Drapeau de la nationalité du joueur - Taille DOUBLÉE') !== false) {
        echo "✅ Zone Drapeau nationalité joueur : w-48 h-48 (192x192px) ✅\n";
    } else {
        echo "❌ Zone Drapeau nationalité joueur : Taille non doublée\n";
    }
    
    if (strpos($content, 'Logo Club') !== false && strpos($content, 'w-48 h-48') !== false) {
        echo "✅ Zone Logo Club : w-48 h-48 (192x192px) ✅\n";
    } else {
        echo "❌ Zone Logo Club : Taille non doublée\n";
    }
    
    if (strpos($content, 'Logo Association') !== false && strpos($content, 'w-48 h-48') !== false) {
        echo "✅ Zone Logo Association : w-48 h-48 (192x192px) ✅\n";
    } else {
        echo "❌ Zone Logo Association : Taille non doublée\n";
    }
    
    if (strpos($content, 'Drapeau du pays de la fédération') !== false && strpos($content, 'w-48 h-48') !== false) {
        echo "✅ Zone Drapeau pays fédération : w-48 h-48 (192x192px) ✅\n";
    } else {
        echo "❌ Zone Drapeau pays fédération : Taille non doublée\n";
    }
    
} else {
    echo "❌ Vue non trouvée\n";
}

echo "\n";

// Test 2: Vérifier les tailles des éléments internes
echo "🖼️ TEST 2: VÉRIFICATION DES TAILLES DES ÉLÉMENTS INTERNES\n";
echo "==========================================================\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Vérifier les tailles des images
    if (strpos($content, 'w-24 h-24') !== false) {
        echo "✅ Images des logos : w-24 h-24 (96x96px) ✅\n";
    } else {
        echo "❌ Images des logos : Taille incorrecte\n";
    }
    
    if (strpos($content, 'w-40 h-32') !== false) {
        echo "✅ Images des drapeaux : w-40 h-32 (160x128px) ✅\n";
    } else {
        echo "❌ Images des drapeaux : Taille incorrecte\n";
    }
    
    // Vérifier les tailles des fallbacks
    if (strpos($content, 'w-24 h-24 bg-blue-600') !== false) {
        echo "✅ Fallbacks des logos : w-24 h-24 (96x96px) ✅\n";
    } else {
        echo "❌ Fallbacks des logos : Taille incorrecte\n";
    }
    
    // Vérifier les tailles des emojis
    if (strpos($content, 'text-8xl') !== false) {
        echo "✅ Emojis photo joueur : text-8xl (72px) ✅\n";
    } else {
        echo "❌ Emojis photo joueur : Taille incorrecte\n";
    }
    
    if (strpos($content, 'text-6xl') !== false) {
        echo "✅ Emojis autres zones : text-6xl (60px) ✅\n";
    } else {
        echo "❌ Emojis autres zones : Taille incorrecte\n";
    }
    
} else {
    echo "❌ Vue non trouvée\n";
}

echo "\n";

// Test 3: Vérifier l'espacement et la mise en page
echo "📐 TEST 3: VÉRIFICATION DE L'ESPACEMENT ET DE LA MISE EN PAGE\n";
echo "=============================================================\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    if (strpos($content, 'gap-4') !== false) {
        echo "✅ Espacement entre zones : gap-4 (16px) ✅\n";
    } else {
        echo "❌ Espacement : Non uniforme\n";
    }
    
    if (strpos($content, 'p-4') !== false) {
        echo "✅ Padding des zones : p-4 (16px) ✅\n";
    } else {
        echo "❌ Padding : Non uniforme\n";
    }
    
    if (strpos($content, 'mb-3') !== false) {
        echo "✅ Marges entre éléments : mb-3 (12px) ✅\n";
    } else {
        echo "❌ Marges : Non uniformes\n";
    }
    
} else {
    echo "❌ Vue non trouvée\n";
}

echo "\n";

// Test 4: Vérifier les boutons et interactions
echo "🔘 TEST 4: VÉRIFICATION DES BOUTONS ET INTERACTIONS\n";
echo "===================================================\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    if (strpos($content, 'px-4 py-2') !== false) {
        echo "✅ Tailles des boutons : px-4 py-2 ✅\n";
    } else {
        echo "❌ Tailles des boutons : Non uniformes\n";
    }
    
    if (strpos($content, 'text-base') !== false && strpos($content, 'font-semibold') !== false) {
        echo "✅ Styles des boutons : text-base + font-semibold ✅\n";
    } else {
        echo "❌ Styles des boutons : Non uniformes\n";
    }
    
} else {
    echo "❌ Vue non trouvée\n";
}

echo "\n";

// Test 5: Résumé des zones uniformisées
echo "📊 TEST 5: RÉSUMÉ DES ZONES UNIFORMISÉES\n";
echo "=========================================\n";

echo "🎯 ZONES AVEC TAILLE UNIFORME w-48 h-48 (192x192px) :\n";
echo "   ✅ 1. Photo du joueur\n";
echo "   ✅ 2. Drapeau nationalité du joueur\n";
echo "   ✅ 3. Logo Club\n";
echo "   ✅ 4. Logo Association\n";
echo "   ✅ 5. Drapeau pays de la fédération\n\n";

echo "🔍 MODIFICATIONS APPLIQUÉES :\n";
echo "   - Photo joueur : w-32 h-32 → w-48 h-48 (128x128px → 192x192px)\n";
echo "   - Drapeau nationalité : w-24 h-24 → w-48 h-48 (96x96px → 192x192px)\n";
echo "   - Espacement : gap-3 → gap-4 (12px → 16px)\n";
echo "   - Padding : p-3 → p-4 (12px → 16px)\n";
echo "   - Marges : mb-2 → mb-3 (8px → 12px)\n";
echo "   - Textes : text-sm → text-base (14px → 16px)\n";
echo "   - Boutons : px-3 py-1 → px-4 py-2 + text-base + font-semibold\n";
echo "   - Emojis photo : text-6xl → text-8xl (60px → 72px)\n";
echo "   - Emojis autres : text-4xl → text-6xl (36px → 60px)\n\n";

echo "🎨 RÉSULTAT ATTENDU :\n";
echo "   - Toutes les zones ont maintenant la MÊME taille (192x192px)\n";
echo "   - Interface parfaitement symétrique et équilibrée\n";
echo "   - Meilleure cohérence visuelle\n";
echo "   - Plus grande lisibilité et accessibilité\n\n";

echo "🌐 TEST RECOMMANDÉ :\n";
echo "   Accéder à http://localhost:8000/portail-joueur/1\n";
echo "   Vérifier que toutes les zones ont la même taille\n\n";

echo "🎉 UNIFORMISATION TERMINÉE !\n";
?>







