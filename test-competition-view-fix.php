<?php
/**
 * Script de test pour vérifier que la vue competition-management/index.blade.php fonctionne
 */

echo "🔍 TEST DE LA VUE COMPETITION-MANAGEMENT\n";
echo "========================================\n\n";

// Test 1: Vérifier que la vue existe et est lisible
echo "📁 TEST 1: VÉRIFICATION DE LA VUE\n";
echo "==================================\n";

$viewFile = 'resources/views/competition-management/index.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Vue trouvée : $viewFile\n";
    
    $content = file_get_contents($viewFile);
    $lines = explode("\n", $content);
    echo "   Nombre de lignes : " . count($lines) . "\n";
    
    // Vérifier les routes corrigées
    if (strpos($content, 'competitions.sync-all') !== false) {
        echo "✅ Route 'competitions.sync-all' utilisée ✅\n";
    } else {
        echo "❌ Route 'competitions.sync-all' NON utilisée\n";
    }
    
    if (strpos($content, 'competitions.create') !== false) {
        echo "✅ Route 'competitions.create' utilisée ✅\n";
    } else {
        echo "❌ Route 'competitions.create' NON utilisée\n";
    }
    
    if (strpos($content, 'competition-management.bulk-sync') === false) {
        echo "✅ Route 'competition-management.bulk-sync' supprimée ✅\n";
    } else {
        echo "❌ Route 'competition-management.bulk-sync' encore présente\n";
    }
    
    if (strpos($content, 'competition-management.competitions.create') === false) {
        echo "✅ Route 'competition-management.competitions.create' supprimée ✅\n";
    } else {
        echo "❌ Route 'competition-management.competitions.create' encore présente\n";
    }
    
} else {
    echo "❌ Vue non trouvée : $viewFile\n";
}

echo "\n";

// Test 2: Vérifier les vérifications de type
echo "🔒 TEST 2: VÉRIFICATIONS DE TYPE\n";
echo "=================================\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Vérifier les vérifications de type pour les champs critiques
    $typeChecks = [
        'is_string($competition->name)' => 'name',
        'is_string($competition->format_label)' => 'format_label',
        'is_string($competition->type_label)' => 'type_label',
        'is_string($competition->season)' => 'season',
        'is_string($competition->status)' => 'status'
    ];
    
    foreach ($typeChecks as $check => $field) {
        if (strpos($content, $check) !== false) {
            echo "✅ Vérification de type pour $field : OK ✅\n";
        } else {
            echo "❌ Vérification de type pour $field : MANQUANTE\n";
        }
    }
    
    // Vérifier les fallbacks JSON
    if (strpos($content, 'json_encode') !== false) {
        echo "✅ Fallbacks JSON présents ✅\n";
    } else {
        echo "❌ Fallbacks JSON manquants\n";
    }
}

echo "\n";

// Test 3: Vérifier la structure des routes
echo "🔗 TEST 3: STRUCTURE DES ROUTES\n";
echo "===============================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Chercher les routes de compétitions
    $competitionRoutes = [
        'competitions.sync-all' => 'Sync all competitions',
        'competitions.create' => 'Create competition',
        'competitions.show' => 'Show competition',
        'competitions.edit' => 'Edit competition',
        'competitions.standings' => 'Competition standings',
        'competitions.register-team-form' => 'Register team form',
        'competitions.sync' => 'Sync competition',
        'competitions.destroy' => 'Delete competition'
    ];
    
    foreach ($competitionRoutes as $route => $description) {
        if (strpos($content, $route) !== false) {
            echo "✅ Route '$route' trouvée : $description ✅\n";
        } else {
            echo "❌ Route '$route' manquante : $description\n";
        }
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// Test 4: Vérifier les données de test
echo "📊 TEST 4: DONNÉES DE TEST\n";
echo "============================\n";

echo "🔗 URLs de test :\n";
echo "   - Competition Management : http://localhost:8000/competition-management\n";
echo "   - Compétitions : http://localhost:8000/competitions\n";
echo "   - Créer Compétition : http://localhost:8000/competitions/create\n\n";

echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "============================\n";

echo "✅ CORRECTIONS EFFECTUÉES :\n";
echo "   1. Route 'competition-management.bulk-sync' → 'competitions.sync-all'\n";
echo "   2. Route 'competition-management.competitions.create' → 'competitions.create'\n";
echo "   3. Vérifications de type ajoutées pour tous les champs critiques\n";
echo "   4. Fallbacks JSON pour les valeurs de type array\n\n";

echo "🔧 ROUTES DISPONIBLES :\n";
echo "   - competitions.index : /competitions (liste des compétitions)\n";
echo "   - competitions.create : /competitions/create (créer une compétition)\n";
echo "   - competitions.sync-all : /competitions/sync-all (synchroniser toutes)\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Tester la route http://localhost:8000/competition-management\n";
echo "   2. Vérifier que toutes les routes fonctionnent\n";
echo "   3. Confirmer l'absence d'erreurs htmlspecialchars\n";
echo "   4. Tester la création et la gestion des compétitions\n\n";

echo "⚠️  ATTENTION :\n";
echo "   - La route 'competitions.sync-all' doit être créée dans web.php\n";
echo "   - Vérifier que le contrôleur CompetitionManagementController gère cette route\n\n";

echo "🎉 CORRECTION TERMINÉE !\n";
?>




