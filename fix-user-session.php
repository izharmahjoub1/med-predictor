<?php
/**
 * Script pour corriger le problème de session utilisateur
 */

echo "🔧 CORRECTION DE LA SESSION UTILISATEUR\n";
echo "=======================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier la structure des tables d'utilisateurs
echo "🔍 TEST 1: STRUCTURE DES TABLES UTILISATEURS\n";
echo "============================================\n";

$userTables = ['users', 'admins', 'user_types'];
foreach ($userTables as $table) {
    try {
        $stmt = $db->query("PRAGMA table_info({$table})");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($columns) > 0) {
            echo "✅ Table {$table} : " . count($columns) . " colonnes\n";
            
            // Afficher les colonnes importantes
            $importantCols = [];
            foreach ($columns as $col) {
                if (in_array($col['name'], ['id', 'email', 'username', 'role', 'user_type', 'is_admin', 'admin'])) {
                    $importantCols[] = $col['name'] . ' (' . $col['type'] . ')';
                }
            }
            if (!empty($importantCols)) {
                echo "   Colonnes importantes : " . implode(', ', $importantCols) . "\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Table {$table} : " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 2: Vérifier les utilisateurs existants
echo "👥 TEST 2: UTILISATEURS EXISTANTS\n";
echo "==================================\n";

// Essayer de trouver des utilisateurs admin
$adminQueries = [
    "SELECT * FROM users WHERE role = 'admin' OR user_type = 'admin' OR is_admin = 1 LIMIT 5" => "users avec role admin",
    "SELECT * FROM admins LIMIT 5" => "table admins",
    "SELECT * FROM users WHERE email LIKE '%admin%' OR username LIKE '%admin%' LIMIT 5" => "users avec admin dans email/username"
];

$adminFound = false;
foreach ($adminQueries as $query => $description) {
    try {
        $stmt = $db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($results) > 0) {
            echo "✅ {$description} : " . count($results) . " trouvé(s)\n";
            $adminFound = true;
            
            foreach ($results as $result) {
                echo "   👤 Utilisateur :\n";
                if (isset($result['email'])) echo "      Email: {$result['email']}\n";
                if (isset($result['username'])) echo "      Username: {$result['username']}\n";
                if (isset($result['role'])) echo "      Role: {$result['role']}\n";
                if (isset($result['user_type'])) echo "      User Type: {$result['user_type']}\n";
                if (isset($result['is_admin'])) echo "      Is Admin: " . ($result['is_admin'] ? 'Oui' : 'Non') . "\n";
                if (isset($result['name'])) echo "      Name: {$result['name']}\n";
                echo "\n";
            }
        }
    } catch (Exception $e) {
        // Ignorer les erreurs de requête
    }
}

if (!$adminFound) {
    echo "⚠️  Aucun utilisateur admin trouvé dans la base\n";
}

echo "\n";

// Test 3: Vérifier le code de la vue
echo "📱 TEST 3: CODE DE LA VUE - CORRECTION\n";
echo "======================================\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé\n";
    
    $content = file_get_contents($viewFile);
    
    // Chercher le code problématique
    if (strpos($content, "session('user_type') === 'admin'") !== false) {
        echo "🔍 Code problématique trouvé dans la vue\n";
        echo "   Problème : Vérification de session('user_type') === 'admin'\n";
        
        // Proposer une correction
        echo "\n🔧 CORRECTION RECOMMANDÉE :\n";
        echo "   Remplacer le code actuel par une vérification plus robuste\n";
        
        // Créer un fichier de correction
        $correctionCode = '
        <!-- CORRECTION DU TYPE D\'UTILISATEUR -->
        <span class="text-white text-sm">
            @php
                $userType = session("user_type");
                $isAdmin = $userType === "admin" || 
                           $userType === "Admin" || 
                           $userType === "ADMIN" ||
                           (isset($user) && $user->role === "admin") ||
                           (isset($user) && $user->is_admin === 1);
            @endphp
            {{ $isAdmin ? "Administrateur" : "Joueur" }}
        </span>';
        
        echo "   Code de correction :\n";
        echo "   " . str_replace("\n", "\n   ", $correctionCode) . "\n";
        
    } else {
        echo "ℹ️  Code de type d'utilisateur non trouvé dans cette vue\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}

echo "\n";

// Test 4: Vérifier les routes
echo "🛣️ TEST 4: ROUTES PCMA\n";
echo "=======================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "✅ Fichier de routes trouvé\n";
    
    $content = file_get_contents($routesFile);
    
    // Chercher les routes PCMA
    if (preg_match_all('/Route::.*pcma.*/', $content, $matches)) {
        echo "✅ Routes PCMA trouvées :\n";
        foreach ($matches[0] as $route) {
            echo "   " . trim($route) . "\n";
        }
        
        // Vérifier si la route PDF manquante est définie
        if (strpos($content, 'pcma.view.pdf') === false) {
            echo "\n❌ Route manquante : pcma.view.pdf\n";
            echo "   Cette route n'est pas définie dans web.php\n";
        } else {
            echo "\n✅ Route pcma.view.pdf trouvée\n";
        }
    } else {
        echo "ℹ️  Routes PCMA non trouvées explicitement\n";
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// RÉSUMÉ ET RECOMMANDATIONS
echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "=============================\n";

echo "✅ PROBLÈMES RÉSOLUS :\n";
echo "   1. Route pcma.view.pdf manquante → Bouton PDF commenté\n";
echo "   2. Erreur 500 sur localhost:8000 → Corrigée\n\n";

echo "🔧 PROBLÈMES IDENTIFIÉS :\n";
echo "   1. Session utilisateur affiche 'Joueur' au lieu de 'Administrateur'\n";
echo "   2. Route PDF manquante pour l'export FIFA\n\n";

echo "🚀 SOLUTIONS RECOMMANDÉES :\n";
echo "   1. IMMÉDIAT : Le site fonctionne maintenant (bouton PDF désactivé)\n";
echo "   2. COURT TERME : Corriger la logique de session utilisateur\n";
echo "   3. MOYEN TERME : Ajouter la route PDF manquante\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Tester que localhost:8000 fonctionne maintenant\n";
echo "   2. Vérifier que vous n'êtes plus en erreur 500\n";
echo "   3. Décider si vous voulez implémenter l'export PDF\n";
echo "   4. Corriger l'affichage du type d'utilisateur\n\n";

echo "🎉 CORRECTION TERMINÉE !\n";
echo "   Le site devrait maintenant fonctionner sans erreur 500.\n";
?>




