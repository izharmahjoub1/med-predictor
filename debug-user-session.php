<?php
/**
 * Script de diagnostic pour le problème de session et type d'utilisateur
 */

echo "🔍 DIAGNOSTIC DE LA SESSION UTILISATEUR\n";
echo "=======================================\n\n";

// Test 1: Vérifier si nous sommes dans un contexte Laravel
echo "🏗️ TEST 1: CONTEXTE LARAVEL\n";
echo "============================\n";

if (file_exists('bootstrap/app.php')) {
    echo "✅ Application Laravel détectée\n";
    
    // Essayer de démarrer Laravel
    try {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        echo "✅ Laravel démarré avec succès\n";
        
        // Vérifier la session
        if (session_status() === PHP_SESSION_ACTIVE) {
            echo "✅ Session active\n";
        } else {
            echo "❌ Session non active\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors du démarrage de Laravel : " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Application Laravel non détectée\n";
}

echo "\n";

// Test 2: Vérifier la base de données des utilisateurs
echo "👥 TEST 2: BASE DE DONNÉES UTILISATEURS\n";
echo "=======================================\n";

try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n";
    
    // Vérifier les tables d'utilisateurs
    $tables = ['users', 'admins', 'user_types', 'roles'];
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("PRAGMA table_info({$table})");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($columns) > 0) {
                echo "✅ Table {$table} : " . count($columns) . " colonnes\n";
                
                // Afficher quelques colonnes clés
                $keyColumns = array_slice(array_column($columns, 'name'), 0, 5);
                echo "   Colonnes principales : " . implode(', ', $keyColumns);
                if (count($columns) > 5) echo " (+" . (count($columns) - 5) . " autres)";
                echo "\n";
            }
        } catch (Exception $e) {
            echo "❌ Table {$table} : " . $e->getMessage() . "\n";
        }
    }
    
    // Vérifier les utilisateurs admin
    echo "\n🔍 Recherche d'utilisateurs admin :\n";
    
    // Essayer différentes requêtes selon les tables disponibles
    $adminQueries = [
        "SELECT * FROM users WHERE role = 'admin' OR user_type = 'admin' LIMIT 5" => "users avec role/user_type admin",
        "SELECT * FROM admins LIMIT 5" => "table admins",
        "SELECT * FROM users WHERE email LIKE '%admin%' OR username LIKE '%admin%' LIMIT 5" => "users avec admin dans email/username"
    ];
    
    foreach ($adminQueries as $query => $description) {
        try {
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) > 0) {
                echo "   ✅ {$description} : " . count($results) . " trouvé(s)\n";
                foreach ($results as $result) {
                    $display = [];
                    if (isset($result['email'])) $display[] = "Email: " . $result['email'];
                    if (isset($result['username'])) $display[] = "Username: " . $result['username'];
                    if (isset($result['role'])) $display[] = "Role: " . $result['role'];
                    if (isset($result['user_type'])) $display[] = "User Type: " . $result['user_type'];
                    if (isset($result['name'])) $display[] = "Name: " . $result['name'];
                    
                    echo "      - " . implode(' | ', $display) . "\n";
                }
            } else {
                echo "   ℹ️  {$description} : Aucun résultat\n";
            }
        } catch (Exception $e) {
            echo "   ❌ {$description} : " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Vérifier le code de la vue
echo "📱 TEST 3: CODE DE LA VUE\n";
echo "==========================\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé\n";
    
    $content = file_get_contents($viewFile);
    
    // Chercher le code qui affiche le type d'utilisateur
    if (preg_match('/session\(\'user_type\'\)\s*===\s*\'admin\'\s*\?\s*\'([^\']+)\'\s*:\s*\'([^\']+)\'/', $content, $matches)) {
        echo "✅ Code de type d'utilisateur trouvé :\n";
        echo "   Si admin : '{$matches[1]}'\n";
        echo "   Si non-admin : '{$matches[2]}'\n";
        
        // Vérifier le contexte autour de ce code
        $start = max(0, strpos($content, $matches[0]) - 100);
        $end = min(strlen($content), strpos($content, $matches[0]) + 200);
        $context = substr($content, $start, $end - $start);
        
        echo "\n   Contexte du code :\n";
        echo "   " . str_replace("\n", "\n   ", $context) . "\n";
    } else {
        echo "❌ Code de type d'utilisateur non trouvé\n";
        
        // Chercher des alternatives
        $alternatives = [
            'user_type' => 'user_type',
            'role' => 'role',
            'is_admin' => 'is_admin',
            'admin' => 'admin'
        ];
        
        foreach ($alternatives as $key => $description) {
            if (strpos($content, $key) !== false) {
                echo "   ℹ️  Mot-clé '{$key}' trouvé dans la vue\n";
            }
        }
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}

echo "\n";

// Test 4: Vérifier les routes et contrôleurs
echo "🛣️ TEST 4: ROUTES ET CONTRÔLEURS\n";
echo "================================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "✅ Fichier de routes trouvé\n";
    
    $content = file_get_contents($routesFile);
    
    // Chercher les routes liées au portail joueur
    if (preg_match_all('/Route::.*joueur.*portal.*/', $content, $matches)) {
        echo "✅ Routes portail joueur trouvées :\n";
        foreach ($matches[0] as $route) {
            echo "   " . trim($route) . "\n";
        }
    } else {
        echo "ℹ️  Routes portail joueur non trouvées explicitement\n";
    }
    
    // Chercher les routes d'authentification
    if (strpos($content, 'Auth::') !== false) {
        echo "✅ Routes d'authentification détectées\n";
    }
    
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// RÉSUMÉ ET RECOMMANDATIONS
echo "🎯 RÉSUMÉ DU DIAGNOSTIC\n";
echo "========================\n";

echo "🔍 PROBLÈME IDENTIFIÉ :\n";
echo "   Le type d'utilisateur affiche 'Joueur' au lieu de 'Administrateur'\n";
echo "   même pour un compte System Admin\n\n";

echo "🔧 CAUSES POSSIBLES :\n";
echo "   1. La session 'user_type' n'est pas initialisée lors de la connexion\n";
echo "   2. La valeur de session n'est pas 'admin' mais autre chose\n";
echo "   3. Le code de connexion ne définit pas le bon type d'utilisateur\n";
echo "   4. La logique de vérification dans la vue est incorrecte\n\n";

echo "🚀 SOLUTIONS RECOMMANDÉES :\n";
echo "   1. Vérifier le code de connexion (LoginController)\n";
echo "   2. S'assurer que la session 'user_type' est définie\n";
echo "   3. Vérifier la valeur exacte stockée dans la session\n";
echo "   4. Corriger la logique d'affichage dans la vue\n\n";

echo "🎉 DIAGNOSTIC TERMINÉ !\n";
?>




