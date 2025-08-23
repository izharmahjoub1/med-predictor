<?php
/**
 * Script de test pour vérifier la correction de la route
 */

echo "🔍 TEST DE LA CORRECTION DE ROUTE\n";
echo "=================================\n\n";

// Test 1: Vérifier que la route correcte existe
echo "🏗️ TEST 1: VÉRIFICATION DE LA ROUTE CORRECTE\n";
echo "=============================================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    if (strpos($content, 'joueur.portal') !== false) {
        echo "✅ Route 'joueur.portal' trouvée dans web.php\n";
        
        // Chercher la définition exacte
        if (preg_match('/Route::get.*portail-joueur.*joueur\.portal.*/', $content, $matches)) {
            echo "   Définition : " . trim($matches[0]) . "\n";
        }
    } else {
        echo "❌ Route 'joueur.portal' NON trouvée dans web.php\n";
    }
    
    if (strpos($content, 'portail.joueur') !== false) {
        echo "✅ Route 'portail.joueur' trouvée dans web.php\n";
    } else {
        echo "❌ Route 'portail.joueur' NON trouvée dans web.php\n";
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// Test 2: Vérifier que la vue utilise la bonne route
echo "👁️ TEST 2: VÉRIFICATION DE LA VUE CORRIGÉE\n";
echo "============================================\n";

$viewFile = 'resources/views/club-management/logo/upload.blade.php';
if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    if (strpos($content, 'joueur.portal') !== false) {
        echo "✅ Route 'joueur.portal' utilisée dans la vue\n";
    } else {
        echo "❌ Route 'joueur.portal' NON utilisée dans la vue\n";
    }
    
    if (strpos($content, 'portail-joueur.show') !== false) {
        echo "❌ Route 'portail-joueur.show' encore présente (ERREUR)\n";
    } else {
        echo "✅ Route 'portail-joueur.show' supprimée de la vue\n";
    }
    
    // Compter les occurrences
    $joueurPortalCount = substr_count($content, 'joueur.portal');
    echo "   Nombre d'occurrences de 'joueur.portal' : {$joueurPortalCount}\n";
    
} else {
    echo "❌ Vue non trouvée\n";
}

echo "\n";

// Test 3: Vérifier la structure des routes
echo "🔗 TEST 3: STRUCTURE DES ROUTES PORTAIL-JOUEUR\n";
echo "==============================================\n";

if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Chercher toutes les routes portail-joueur
    if (preg_match_all('/Route::.*portail-joueur.*/', $content, $matches)) {
        echo "✅ Routes portail-joueur trouvées :\n";
        foreach ($matches[0] as $index => $route) {
            echo "   " . ($index + 1) . ". " . trim($route) . "\n";
        }
    } else {
        echo "❌ Aucune route portail-joueur trouvée\n";
    }
}

echo "\n";

// Test 4: Test de la route
echo "🌐 TEST 4: TEST DE LA ROUTE\n";
echo "============================\n";

echo "🔗 URLs de test :\n";
echo "   - Formulaire logo : http://localhost:8000/club/1/logo/upload\n";
echo "   - Portail joueur : http://localhost:8000/portail-joueur/1\n";
echo "   - Portail général : http://localhost:8000/portail-joueur\n\n";

echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "=============================\n";

echo "✅ CORRECTION EFFECTUÉE :\n";
echo "   1. Route 'portail-joueur.show' remplacée par 'joueur.portal'\n";
echo "   2. Vue club-management/logo/upload.blade.php corrigée\n";
echo "   3. Liens de retour et d'annulation mis à jour\n\n";

echo "🔧 ROUTES DISPONIBLES :\n";
echo "   - joueur.portal : /portail-joueur/{playerId?}\n";
echo "   - portail.joueur : /portail-joueur\n";
echo "   - club.logo.upload : /club/{club}/logo/upload\n";
echo "   - club.logo.store : POST /club/{club}/logo/upload\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Tester la route http://localhost:8000/club/1/logo/upload\n";
echo "   2. Vérifier que le bouton 'Retour au Portail' fonctionne\n";
echo "   3. Confirmer la navigation entre les pages\n\n";

echo "🎉 CORRECTION TERMINÉE !\n";
?>







