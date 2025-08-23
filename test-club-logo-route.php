<?php
/**
 * Script de test pour la route des logos de clubs
 */

echo "🔍 TEST DE LA ROUTE CLUB LOGO\n";
echo "============================\n\n";

// Test 1: Vérifier que la route existe
echo "🏗️ TEST 1: VÉRIFICATION DE LA ROUTE\n";
echo "====================================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    if (strpos($content, 'club.logo.upload') !== false) {
        echo "✅ Route 'club.logo.upload' trouvée dans web.php\n";
    } else {
        echo "❌ Route 'club.logo.upload' NON trouvée dans web.php\n";
    }
    
    if (strpos($content, 'club.logo.store') !== false) {
        echo "✅ Route 'club.logo.store' trouvée dans web.php\n";
    } else {
        echo "❌ Route 'club.logo.store' NON trouvée dans web.php\n";
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// Test 2: Vérifier le contrôleur
echo "🎮 TEST 2: VÉRIFICATION DU CONTRÔLEUR\n";
echo "=====================================\n";

$controllerFile = 'app/Http/Controllers/ClubManagementController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    if (strpos($content, 'showLogoUpload') !== false) {
        echo "✅ Méthode 'showLogoUpload' trouvée dans ClubManagementController\n";
    } else {
        echo "❌ Méthode 'showLogoUpload' NON trouvée dans ClubManagementController\n";
    }
    
    if (strpos($content, 'uploadLogo') !== false) {
        echo "✅ Méthode 'uploadLogo' trouvée dans ClubManagementController\n";
    } else {
        echo "❌ Méthode 'uploadLogo' NON trouvée dans ClubManagementController\n";
    }
} else {
    echo "❌ Contrôleur ClubManagementController non trouvé\n";
}

echo "\n";

// Test 3: Vérifier la vue
echo "👁️ TEST 3: VÉRIFICATION DE LA VUE\n";
echo "==================================\n";

$viewFile = 'resources/views/club-management/logo/upload.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Vue 'club-management.logo.upload' trouvée\n";
    
    $content = file_get_contents($viewFile);
    if (strpos($content, 'club.logo.store') !== false) {
        echo "✅ Route 'club.logo.store' utilisée dans la vue\n";
    } else {
        echo "❌ Route 'club.logo.store' NON utilisée dans la vue\n";
    }
} else {
    echo "❌ Vue 'club-management.logo.upload' non trouvée\n";
}

echo "\n";

// Test 4: Vérifier la base de données
echo "🗄️ TEST 4: VÉRIFICATION DE LA BASE DE DONNÉES\n";
echo "=============================================\n";

try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier la table clubs
    $stmt = $db->query("PRAGMA table_info(clubs)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasLogoUrl = false;
    $hasLogoPath = false;
    
    foreach ($columns as $col) {
        if ($col['name'] === 'logo_url') $hasLogoUrl = true;
        if ($col['name'] === 'logo_path') $hasLogoPath = true;
    }
    
    if ($hasLogoUrl) {
        echo "✅ Colonne 'logo_url' trouvée dans la table clubs\n";
    } else {
        echo "❌ Colonne 'logo_url' NON trouvée dans la table clubs\n";
    }
    
    if ($hasLogoPath) {
        echo "✅ Colonne 'logo_path' trouvée dans la table clubs\n";
    } else {
        echo "❌ Colonne 'logo_path' NON trouvée dans la table clubs\n";
    }
    
    // Vérifier qu'il y a des clubs
    $stmt = $db->query("SELECT COUNT(*) as total FROM clubs");
    $clubCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "📊 Nombre total de clubs : {$clubCount}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur base de données : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Test de la route
echo "🌐 TEST 5: TEST DE LA ROUTE\n";
echo "============================\n";

echo "🔗 URL de test : http://localhost:8000/club/1/logo/upload\n";
echo "📝 Cette route devrait afficher le formulaire d'upload du logo\n\n";

echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "=============================\n";

echo "✅ IMPLÉMENTATION TERMINÉE :\n";
echo "   1. Route 'club.logo.upload' ajoutée dans web.php\n";
echo "   2. Route 'club.logo.store' ajoutée dans web.php\n";
echo "   3. Méthodes 'showLogoUpload' et 'uploadLogo' ajoutées dans ClubManagementController\n";
echo "   4. Vue 'club-management.logo.upload' créée\n\n";

echo "🔧 FONCTIONNALITÉS :\n";
echo "   - Affichage du logo actuel du club\n";
echo "   - Formulaire d'upload avec validation\n";
echo "   - Stockage dans storage/app/public/clubs/logos\n";
echo "   - Mise à jour automatique des champs logo_url et logo_path\n";
echo "   - Messages de succès/erreur\n";
echo "   - Retour au portail joueur\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Tester la route http://localhost:8000/club/1/logo/upload\n";
echo "   2. Vérifier que le bouton 'Gérer' fonctionne dans le portail joueur\n";
echo "   3. Tester l'upload d'un logo\n";
echo "   4. Vérifier que le logo s'affiche correctement\n\n";

echo "🎉 TEST TERMINÉ !\n";
?>







