<?php
/**
 * Script de test pour le contrôleur des compétitions
 */

echo "🔍 TEST DU CONTRÔLEUR DES COMPÉTITIONS\n";
echo "======================================\n\n";

// Test 1: Vérifier si nous sommes dans un contexte Laravel
echo "🏗️ TEST 1: CONTEXTE LARAVEL\n";
echo "============================\n";

if (file_exists('bootstrap/app.php')) {
    echo "✅ Application Laravel détectée\n";
    
    try {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        echo "✅ Laravel démarré avec succès\n";
        
        // Essayer d'accéder au contrôleur
        if (class_exists('App\Http\Controllers\CompetitionManagementController')) {
            echo "✅ Contrôleur CompetitionManagementController trouvé\n";
        } else {
            echo "❌ Contrôleur CompetitionManagementController non trouvé\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors du démarrage de Laravel : " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Application Laravel non détectée\n";
}

echo "\n";

// Test 2: Vérifier les routes et leurs contrôleurs
echo "🛣️ TEST 2: ROUTES ET CONTRÔLEURS\n";
echo "=================================\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "✅ Fichier de routes trouvé\n";
    
    $content = file_get_contents($routesFile);
    
    // Chercher les routes de compétition
    if (preg_match_all('/Route::.*competition.*/', $content, $matches)) {
        echo "✅ Routes de compétition trouvées :\n";
        foreach ($matches[0] as $route) {
            echo "   " . trim($route) . "\n";
        }
    }
    
    // Chercher les contrôleurs utilisés
    if (preg_match_all('/\[([^\]]+Controller)::class/', $content, $matches)) {
        echo "\n✅ Contrôleurs utilisés :\n";
        foreach ($matches[1] as $controller) {
            echo "   - {$controller}\n";
        }
    }
} else {
    echo "❌ Fichier de routes non trouvé\n";
}

echo "\n";

// Test 3: Vérifier la base de données des relations
echo "🔗 TEST 3: RELATIONS ET CHAMPS COMPLEXES\n";
echo "========================================\n";

try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier les tables liées
    $relatedTables = ['fifa_connect_ids', 'associations', 'seasons'];
    foreach ($relatedTables as $table) {
        try {
            $stmt = $db->query("PRAGMA table_info({$table})");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($columns) > 0) {
                echo "✅ Table {$table} : " . count($columns) . " colonnes\n";
            }
        } catch (Exception $e) {
            echo "❌ Table {$table} : " . $e->getMessage() . "\n";
        }
    }
    
    // Vérifier les relations dans competitions
    echo "\n🔍 Vérification des relations dans competitions :\n";
    
    $stmt = $db->query("
        SELECT c.id, c.name, c.type, c.season, c.status,
               f.fifa_id as fifa_connect_id,
               a.name as association_name,
               s.name as season_name
        FROM competitions c
        LEFT JOIN fifa_connect_ids f ON c.fifa_connect_id = f.id
        LEFT JOIN associations a ON c.association_id = a.id
        LEFT JOIN seasons s ON c.season_id = s.id
        LIMIT 3
    ");
    
    $competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($competitions as $comp) {
        echo "\n🏆 Compétition ID {$comp['id']} :\n";
        foreach ($comp as $field => $value) {
            $type = gettype($value);
            echo "   {$field} : Type {$type} - ";
            
            if (is_string($value)) {
                echo "String : '{$value}'\n";
            } elseif (is_null($value)) {
                echo "NULL\n";
            } else {
                echo "Autre : " . var_export($value, true) . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur base de données : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Vérifier les accesseurs et mutateurs
echo "🔧 TEST 4: ACCESSEURS ET MUTATEURS\n";
echo "==================================\n";

// Chercher dans les modèles
$modelFiles = [
    'app/Models/Competition.php',
    'app/Models/FifaConnectId.php',
    'app/Models/Association.php'
];

foreach ($modelFiles as $modelFile) {
    if (file_exists($modelFile)) {
        echo "✅ Modèle trouvé : {$modelFile}\n";
        
        $content = file_get_contents($modelFile);
        
        // Chercher les accesseurs
        if (preg_match_all('/function get(\w+)Attribute/', $content, $matches)) {
            echo "   Accesseurs : " . implode(', ', $matches[1]) . "\n";
        }
        
        // Chercher les casts
        if (strpos($content, 'protected $casts') !== false) {
            echo "   Casts définis : Oui\n";
        }
        
    } else {
        echo "❌ Modèle non trouvé : {$modelFile}\n";
    }
}

echo "\n";

// Test 5: Simulation du problème
echo "🎯 TEST 5: SIMULATION DU PROBLÈME\n";
echo "==================================\n";

echo "🔍 L'erreur se produit probablement dans l'une de ces situations :\n";
echo "   1. Un accesseur retourne un array au lieu d'un string\n";
echo "   2. Une relation retourne un objet complexe\n";
echo "   3. Un champ JSON est mal décodé\n";
echo "   4. Un mutateur transforme mal les données\n\n";

echo "🔧 SOLUTIONS IMMÉDIATES :\n";
echo "   1. Vérifier tous les accesseurs dans les modèles\n";
echo "   2. S'assurer que les relations retournent des strings\n";
echo "   3. Ajouter des vérifications de type dans la vue\n";
echo "   4. Utiliser des helpers de sécurité\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Examiner les modèles Eloquent\n";
echo "   2. Vérifier les accesseurs et mutateurs\n";
echo "   3. Tester les relations une par une\n";
echo "   4. Corriger le problème à la source\n\n";

echo "🎉 DIAGNOSTIC DU CONTRÔLEUR TERMINÉ !\n";
?>
