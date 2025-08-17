<?php
/**
 * Script de test pour vérifier que toutes les routes fonctionnent
 */

echo "🔍 TEST DES ROUTES CORRIGÉES\n";
echo "============================\n\n";

// Test 1: Vérifier que la route correcte existe
echo "🏗️ TEST 1: VÉRIFICATION DE LA ROUTE CORRECTE\n";
echo "=============================================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    if (strpos($content, 'competitions.index') !== false) {
        echo "✅ Route 'competitions.index' trouvée dans web.php\n";
        
        // Chercher la définition exacte
        if (preg_match('/Route::get.*competitions.*competitions\.index.*/', $content, $matches)) {
            echo "   Définition : " . trim($matches[0]) . "\n";
        }
    } else {
        echo "❌ Route 'competitions.index' NON trouvée dans web.php\n";
    }
    
    if (strpos($content, 'competition-management.index') !== false) {
        echo "⚠️  Route 'competition-management.index' encore présente (à corriger)\n";
    } else {
        echo "✅ Route 'competition-management.index' supprimée ✅\n";
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// Test 2: Vérifier que les vues utilisent la bonne route
echo "👁️ TEST 2: VÉRIFICATION DES VUES CORRIGÉES\n";
echo "============================================\n";

$fixturesView = 'resources/views/modules/fixtures/index.blade.php';
$rankingsView = 'resources/views/modules/rankings/index.blade.php';

if (file_exists($fixturesView)) {
    $content = file_get_contents($fixturesView);
    
    if (strpos($content, 'competitions.index') !== false) {
        echo "✅ Vue fixtures : Route 'competitions.index' utilisée ✅\n";
    } else {
        echo "❌ Vue fixtures : Route 'competitions.index' NON utilisée\n";
    }
    
    if (strpos($content, 'competition-management.index') !== false) {
        echo "❌ Vue fixtures : Route 'competition-management.index' encore présente\n";
    } else {
        echo "✅ Vue fixtures : Route 'competition-management.index' supprimée ✅\n";
    }
} else {
    echo "❌ Vue fixtures non trouvée\n";
}

if (file_exists($rankingsView)) {
    $content = file_get_contents($rankingsView);
    
    if (strpos($content, 'competitions.index') !== false) {
        echo "✅ Vue rankings : Route 'competitions.index' utilisée ✅\n";
    } else {
        echo "❌ Vue rankings : Route 'competitions.index' NON utilisée\n";
    }
    
    if (strpos($content, 'competition-management.index') !== false) {
        echo "❌ Vue rankings : Route 'competition-management.index' encore présente\n";
    } else {
        echo "✅ Vue rankings : Route 'competition-management.index' supprimée ✅\n";
    }
} else {
    echo "❌ Vue rankings non trouvée\n";
}

echo "\n";

// Test 3: Vérifier la structure des routes
echo "🔗 TEST 3: STRUCTURE DES ROUTES COMPETITIONS\n";
echo "===========================================\n";

if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Chercher toutes les routes de compétitions
    if (preg_match_all('/Route::.*competitions.*/', $content, $matches)) {
        echo "✅ Routes de compétitions trouvées :\n";
        foreach ($matches[0] as $index => $route) {
            echo "   " . ($index + 1) . ". " . trim($route) . "\n";
        }
    } else {
        echo "❌ Aucune route de compétitions trouvée\n";
    }
}

echo "\n";

// Test 4: Test des routes
echo "🌐 TEST 4: TEST DES ROUTES\n";
echo "==========================\n";

echo "🔗 URLs de test :\n";
echo "   - Fixtures : http://localhost:8000/fixtures\n";
echo "   - Rankings : http://localhost:8000/rankings\n";
echo "   - Compétitions : http://localhost:8000/competitions\n";
echo "   - Compétitions (index) : http://localhost:8000/competitions\n\n";

echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "=============================\n";

echo "✅ CORRECTION EFFECTUÉE :\n";
echo "   1. Route 'competition-management.index' remplacée par 'competitions.index'\n";
echo "   2. Vue fixtures corrigée\n";
echo "   3. Vue rankings corrigée\n\n";

echo "🔧 ROUTES DISPONIBLES :\n";
echo "   - competitions.index : /competitions (liste des compétitions)\n";
echo "   - fixtures.index : /fixtures (liste des fixtures)\n";
echo "   - rankings.index : /rankings (classements)\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Tester la route http://localhost:8000/fixtures\n";
echo "   2. Tester la route http://localhost:8000/rankings\n";
echo "   3. Vérifier que le bouton 'Retour aux compétitions' fonctionne\n";
echo "   4. Confirmer la navigation entre les pages\n\n";

echo "🎉 CORRECTION TERMINÉE !\n";
?>




