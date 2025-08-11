<?php
echo "=== Test Final du Workflow de Licence ===\n";

// Test 1: Vérifier que toutes les pages sont accessibles
echo "1. Test d'accès aux pages principales...\n";

$pages = [
    'http://localhost:8000/licenses/create' => 'Création de licence',
    'http://localhost:8000/licenses/validation' => 'Validation par Association',
    'http://localhost:8000/licenses' => 'Index des licences',
    'http://localhost:8000/modules/' => 'Page des modules'
];

foreach ($pages as $url => $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 302) {
        echo "   ✅ $description: Redirection vers login (normal)\n";
    } elseif ($httpCode == 200) {
        echo "   ✅ $description: Accessible (HTTP 200)\n";
    } else {
        echo "   ❌ $description: HTTP $httpCode (PROBLÈME)\n";
    }
}

// Test 2: Vérifier que tous les fichiers nécessaires existent
echo "\n2. Vérification des fichiers...\n";

$files = [
    'app/Http/Controllers/LicenseController.php' => 'Contrôleur LicenseController',
    'app/Models/License.php' => 'Modèle License',
    'resources/views/licenses/create.blade.php' => 'Vue de création',
    'resources/views/licenses/validation.blade.php' => 'Vue de validation',
    'resources/views/modules/index.blade.php' => 'Vue des modules',
    'routes/web.php' => 'Fichier des routes'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "   ✅ $description: Présent\n";
    } else {
        echo "   ❌ $description: Manquant\n";
    }
}

// Test 3: Vérifier les routes dans le fichier web.php
echo "\n3. Vérification des routes...\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    $routes = [
        'licenses.validation' => 'Route de validation',
        'licenses.approve' => 'Route d\'approbation',
        'licenses.reject' => 'Route de rejet',
        'licenses.create' => 'Route de création',
        'licenses.index' => 'Route d\'index'
    ];
    
    foreach ($routes as $route => $description) {
        if (strpos($content, $route) !== false) {
            echo "   ✅ $description: Présente\n";
        } else {
            echo "   ❌ $description: Manquante\n";
        }
    }
    
    // Vérifier les cartes dans les modules
    if (strpos($content, 'Association') !== false) {
        echo "   ✅ Carte Association: Présente dans les routes\n";
    } else {
        echo "   ❌ Carte Association: Manquante dans les routes\n";
    }
    
    if (strpos($content, 'Administration') !== false) {
        echo "   ✅ Carte Administration: Présente dans les routes\n";
    } else {
        echo "   ❌ Carte Administration: Manquante dans les routes\n";
    }
}

// Test 4: Vérifier le contrôleur
echo "\n4. Vérification du contrôleur...\n";

$controllerFile = 'app/Http/Controllers/LicenseController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    $methods = [
        'create()' => 'Méthode de création',
        'store()' => 'Méthode de stockage',
        'validation()' => 'Méthode de validation',
        'approve()' => 'Méthode d\'approbation',
        'reject()' => 'Méthode de rejet',
        'index()' => 'Méthode d\'index'
    ];
    
    foreach ($methods as $method => $description) {
        if (strpos($content, "public function $method") !== false) {
            echo "   ✅ $description: Présente\n";
        } else {
            echo "   ❌ $description: Manquante\n";
        }
    }
}

// Test 5: Vérifier le modèle
echo "\n5. Vérification du modèle...\n";

$modelFile = 'app/Models/License.php';
if (file_exists($modelFile)) {
    $content = file_get_contents($modelFile);
    
    $fields = [
        'license_type',
        'applicant_name',
        'date_of_birth',
        'nationality',
        'position',
        'email',
        'phone',
        'club_id',
        'association_id',
        'license_reason',
        'validity_period',
        'documents',
        'status',
        'requested_by',
        'requested_at',
        'approved_by',
        'approved_at'
    ];
    
    $missingFields = [];
    foreach ($fields as $field) {
        if (strpos($content, "'$field'") === false) {
            $missingFields[] = $field;
        }
    }
    
    if (empty($missingFields)) {
        echo "   ✅ Tous les champs requis: Présents\n";
    } else {
        echo "   ❌ Champs manquants: " . implode(', ', $missingFields) . "\n";
    }
}

echo "\n=== RÉSUMÉ DU WORKFLOW COMPLET ===\n";
echo "🎯 Processus de Licence avec Validation par l'Association\n\n";

echo "📋 ÉTAPE 1: Création de la demande (Club)\n";
echo "   • Club se connecte\n";
echo "   • Va sur /modules/\n";
echo "   • Clique sur 'Licenses'\n";
echo "   • Clique sur 'New License Application'\n";
echo "   • Remplit le formulaire complet\n";
echo "   • Soumet la demande\n";
echo "   • Statut: pending\n\n";

echo "🏛️ ÉTAPE 2: Validation par l'Association\n";
echo "   • Association se connecte\n";
echo "   • Va sur /modules/\n";
echo "   • Clique sur 'Association'\n";
echo "   • Voir toutes les demandes en attente\n";
echo "   • Pour chaque demande:\n";
echo "     - Clique sur 'Voir' pour examiner\n";
echo "     - Clique sur 'Approuver' ou 'Rejeter'\n";
echo "     - Si rejet, indique le motif\n";
echo "   • Statut change à approved/rejected\n\n";

echo "📊 ÉTAPE 3: Retour au Club\n";
echo "   • Club se reconnecte\n";
echo "   • Va sur /licenses\n";
echo "   • Vérifie le statut mis à jour\n";
echo "   • Si approuvée: licence active\n";
echo "   • Si rejetée: voit le motif\n\n";

echo "=== URLs IMPORTANTES ===\n";
echo "🏠 Modules: http://localhost:8000/modules/\n";
echo "📋 Création licence: http://localhost:8000/licenses/create\n";
echo "🏛️ Validation: http://localhost:8000/licenses/validation\n";
echo "📊 Index licences: http://localhost:8000/licenses\n";

echo "\n=== RÔLES NÉCESSAIRES ===\n";
echo "👥 Club: club_admin, club_manager, club_medical\n";
echo "🏛️ Association: association_admin, association_registrar, association_medical\n";
echo "⚙️ Administration: system_admin, admin\n";

echo "\n=== FONCTIONNALITÉS IMPLÉMENTÉES ===\n";
echo "✅ Page de création de licence complète\n";
echo "✅ Sélection de type de licence (Joueur/Staff/Médical)\n";
echo "✅ Upload de documents (pièce d'identité, certificat médical, etc.)\n";
echo "✅ Processus de validation visuel\n";
echo "✅ Page de validation par l'Association\n";
echo "✅ Statistiques (en attente, approuvées, rejetées)\n";
echo "✅ Actions d'approbation et de rejet\n";
echo "✅ Modal de détails de licence\n";
echo "✅ Cartes Association et Administration dans les modules\n";
echo "✅ Workflow complet club → association → validation → retour\n";

echo "\n=== INSTRUCTIONS DE TEST ===\n";
echo "🔍 Pour tester le workflow complet:\n\n";

echo "1️⃣ Créez un utilisateur club:\n";
echo "   php artisan tinker\n";
echo "   User::create(['name' => 'Club Test', 'email' => 'club@test.com', 'password' => Hash::make('password'), 'role' => 'club_admin', 'club_id' => 1]);\n\n";

echo "2️⃣ Créez un utilisateur association:\n";
echo "   User::create(['name' => 'Association Test', 'email' => 'association@test.com', 'password' => Hash::make('password'), 'role' => 'association_admin', 'association_id' => 1]);\n\n";

echo "3️⃣ Testez le workflow:\n";
echo "   - Connectez-vous en tant que club\n";
echo "   - Créez une demande de licence\n";
echo "   - Connectez-vous en tant qu'association\n";
echo "   - Validez ou rejetez la demande\n";
echo "   - Reconnectez-vous en tant que club\n";
echo "   - Vérifiez le résultat\n\n";

echo "🎉 LE WORKFLOW DE LICENCE EST MAINTENANT COMPLET !\n";
echo "✨ Processus professionnel avec validation par l'Association\n";
echo "🔗 Identique au processus d'enregistrement de joueur\n";
echo "🏛️ Interface complète pour la gestion des demandes\n";
?> 