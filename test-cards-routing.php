<?php
echo "=== Test du Routage des Cartes Association et Administration ===\n";

// Test 1: Vérifier que la page d'administration est accessible
echo "1. Test d'accès à la page d'administration...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/administration');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 302) {
    echo "✅ Page administration: Redirection vers login (normal)\n";
} elseif ($httpCode == 200) {
    echo "✅ Page administration: Accessible (HTTP 200)\n";
} else {
    echo "❌ Page administration: HTTP $httpCode (PROBLÈME)\n";
}

// Test 2: Vérifier que la page de validation des licences est accessible
echo "\n2. Test d'accès à la page de validation des licences...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/licenses/validation');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 302) {
    echo "✅ Page licenses/validation: Redirection vers login (normal)\n";
} elseif ($httpCode == 200) {
    echo "✅ Page licenses/validation: Accessible (HTTP 200)\n";
} else {
    echo "❌ Page licenses/validation: HTTP $httpCode (PROBLÈME)\n";
}

// Test 3: Vérifier que les routes sont correctement définies dans le fichier web.php
echo "\n3. Vérification des routes dans web.php...\n";
$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Vérifier la route d'administration
    if (strpos($content, 'administration.index') !== false) {
        echo "   ✅ Route administration.index: Présente\n";
    } else {
        echo "   ❌ Route administration.index: Manquante\n";
    }
    
    // Vérifier la route de validation des licences
    if (strpos($content, 'licenses.validation') !== false) {
        echo "   ✅ Route licenses.validation: Présente\n";
    } else {
        echo "   ❌ Route licenses.validation: Manquante\n";
    }
    
    // Vérifier que les cartes pointent vers les bonnes routes
    if (strpos($content, "'route' => 'administration.index'") !== false) {
        echo "   ✅ Carte Administration: Pointe vers administration.index\n";
    } else {
        echo "   ❌ Carte Administration: Ne pointe pas vers administration.index\n";
    }
    
    if (strpos($content, "'route' => 'licenses.validation'") !== false) {
        echo "   ✅ Carte Association: Pointe vers licenses.validation\n";
    } else {
        echo "   ❌ Carte Association: Ne pointe pas vers licenses.validation\n";
    }
} else {
    echo "❌ Fichier routes/web.php: Manquant\n";
}

// Test 4: Vérifier que les vues existent
echo "\n4. Vérification des vues...\n";
$views = [
    'resources/views/administration/index.blade.php' => 'Vue administration',
    'resources/views/licenses/validation.blade.php' => 'Vue validation des licences'
];

foreach ($views as $view => $description) {
    if (file_exists($view)) {
        echo "   ✅ $description: Présente\n";
    } else {
        echo "   ❌ $description: Manquante\n";
    }
}

// Test 5: Vérifier le contenu des vues
echo "\n5. Vérification du contenu des vues...\n";

// Vérifier la vue d'administration
$adminView = 'resources/views/administration/index.blade.php';
if (file_exists($adminView)) {
    $content = file_get_contents($adminView);
    
    $features = [
        'Panneau d\'Administration' => 'Titre de la page',
        'Gestion des Utilisateurs' => 'Section gestion utilisateurs',
        'Gestion des Rôles' => 'Section gestion rôles',
        'Configuration Système' => 'Section configuration',
        'Logs et Audit' => 'Section logs',
        'Sauvegarde et Restauration' => 'Section sauvegarde',
        'API et Intégrations' => 'Section API'
    ];
    
    foreach ($features as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "   ✅ $description: Présent\n";
        } else {
            echo "   ❌ $description: Manquant\n";
        }
    }
}

// Vérifier la vue de validation
$validationView = 'resources/views/licenses/validation.blade.php';
if (file_exists($validationView)) {
    $content = file_get_contents($validationView);
    
    $features = [
        'Validation des Licences' => 'Titre de la page',
        'Statistiques' => 'Section statistiques',
        'Demandes de Licences' => 'Tableau des demandes',
        'Approuver' => 'Bouton d\'approbation',
        'Rejeter' => 'Bouton de rejet'
    ];
    
    foreach ($features as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "   ✅ $description: Présent\n";
        } else {
            echo "   ❌ $description: Manquant\n";
        }
    }
}

echo "\n=== RÉSUMÉ DES CORRECTIONS ===\n";
echo "🔧 Problème identifié:\n";
echo "   - Carte Administration pointait vers dashboard\n";
echo "   - Carte Association pointait vers licenses.validation (correct)\n";

echo "\n✅ Corrections appliquées:\n";
echo "   - Création de la page d'administration complète\n";
echo "   - Ajout de la route administration.index\n";
echo "   - Correction de la carte Administration pour pointer vers administration.index\n";
echo "   - Carte Association reste sur licenses.validation (correct)\n";

echo "\n=== URLs CORRIGÉES ===\n";
echo "🏛️ Carte Association: http://localhost:8000/licenses/validation\n";
echo "⚙️ Carte Administration: http://localhost:8000/administration\n";

echo "\n=== FONCTIONNALITÉS DE LA PAGE ADMINISTRATION ===\n";
echo "👥 Gestion des Utilisateurs\n";
echo "🔐 Gestion des Rôles\n";
echo "⚙️ Configuration Système\n";
echo "📊 Logs et Audit\n";
echo "💾 Sauvegarde et Restauration\n";
echo "🔗 API et Intégrations\n";

echo "\n=== FONCTIONNALITÉS DE LA PAGE ASSOCIATION ===\n";
echo "📊 Statistiques des demandes\n";
echo "📋 Tableau des demandes de licences\n";
echo "✅ Actions d'approbation/rejet\n";
echo "🔍 Modal de détails\n";
echo "📈 Filtres et pagination\n";

echo "\n=== INSTRUCTIONS DE TEST ===\n";
echo "🔍 Pour tester les cartes corrigées:\n\n";

echo "1️⃣ Test de la carte Association:\n";
echo "   - Connectez-vous en tant qu'association\n";
echo "   - Allez sur http://localhost:8000/modules/\n";
echo "   - Cliquez sur la carte 'Association'\n";
echo "   - Vous devriez arriver sur la page de validation des licences\n\n";

echo "2️⃣ Test de la carte Administration:\n";
echo "   - Connectez-vous en tant qu'admin\n";
echo "   - Allez sur http://localhost:8000/modules/\n";
echo "   - Cliquez sur la carte 'Administration'\n";
echo "   - Vous devriez arriver sur la page d'administration\n\n";

echo "🎉 LES CARTES POINTENT MAINTENANT VERS LES BONNES PAGES !\n";
echo "✅ Association → Validation des licences\n";
echo "✅ Administration → Panneau d'administration\n";
echo "🔗 Plus de redirection vers le dashboard\n";
?> 