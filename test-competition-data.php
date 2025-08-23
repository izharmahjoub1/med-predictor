<?php
/**
 * Script de test pour diagnostiquer le problème de type dans les compétitions
 */

echo "🔍 DIAGNOSTIC DES DONNÉES DE COMPÉTITION\n";
echo "========================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier la structure de la table competitions
echo "🏗️ TEST 1: STRUCTURE DE LA TABLE COMPETITIONS\n";
echo "=============================================\n";

try {
    $stmt = $db->query("PRAGMA table_info(competitions)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($columns) > 0) {
        echo "✅ Table competitions : " . count($columns) . " colonnes\n";
        
        foreach ($columns as $col) {
            echo "   - {$col['name']} ({$col['type']})\n";
        }
    } else {
        echo "❌ Table competitions vide ou inexistante\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur table competitions : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Vérifier les données des compétitions
echo "📊 TEST 2: DONNÉES DES COMPÉTITIONS\n";
echo "===================================\n";

try {
    $stmt = $db->query("SELECT * FROM competitions LIMIT 5");
    $competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($competitions) > 0) {
        echo "✅ Compétitions trouvées : " . count($competitions) . "\n\n";
        
        foreach ($competitions as $index => $comp) {
            echo "🏆 Compétition " . ($index + 1) . " :\n";
            echo "   ID : {$comp['id']}\n";
            echo "   Nom : " . (is_string($comp['name']) ? $comp['name'] : 'ARRAY/OBJECT') . "\n";
            echo "   Type : " . (is_string($comp['type']) ? $comp['type'] : 'ARRAY/OBJECT') . "\n";
            echo "   Saison : " . (is_string($comp['season']) ? $comp['season'] : 'ARRAY/OBJECT') . "\n";
            echo "   Statut : " . (is_string($comp['status']) ? $comp['status'] : 'ARRAY/OBJECT') . "\n";
            
            // Vérifier les champs problématiques
            if (isset($comp['format_label'])) {
                $type = gettype($comp['format_label']);
                echo "   Format Label : Type {$type} - ";
                if (is_string($comp['format_label'])) {
                    echo "Valeur : {$comp['format_label']}\n";
                } elseif (is_array($comp['format_label'])) {
                    echo "ARRAY : " . json_encode($comp['format_label']) . "\n";
                } else {
                    echo "AUTRE : " . var_export($comp['format_label'], true) . "\n";
                }
            }
            
            if (isset($comp['type_label'])) {
                $type = gettype($comp['type_label']);
                echo "   Type Label : Type {$type} - ";
                if (is_string($comp['type_label'])) {
                    echo "Valeur : {$comp['type_label']}\n";
                } elseif (is_array($comp['type_label'])) {
                    echo "ARRAY : " . json_encode($comp['type_label']) . "\n";
                } else {
                    echo "AUTRE : " . var_export($comp['type_label'], true) . "\n";
                }
            }
            
            echo "\n";
        }
    } else {
        echo "ℹ️  Aucune compétition trouvée\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lecture compétitions : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Vérifier les relations
echo "🔗 TEST 3: RELATIONS ET CHAMPS COMPLEXES\n";
echo "========================================\n";

try {
    // Vérifier s'il y a des champs JSON
    $stmt = $db->query("SELECT name, type FROM pragma_table_info('competitions') WHERE type LIKE '%json%' OR type LIKE '%array%'");
    $jsonColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($jsonColumns) > 0) {
        echo "⚠️  Colonnes potentiellement problématiques :\n";
        foreach ($jsonColumns as $col) {
            echo "   - {$col['name']} ({$col['type']})\n";
        }
    } else {
        echo "✅ Aucune colonne JSON/ARRAY détectée\n";
    }
    
    // Vérifier les valeurs NULL ou vides
    $stmt = $db->query("SELECT COUNT(*) as total FROM competitions WHERE name IS NULL OR name = ''");
    $nullNames = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "   Noms NULL/vides : {$nullNames}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur vérification relations : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Simulation de l'affichage
echo "🎨 TEST 4: SIMULATION DE L'AFFICHAGE\n";
echo "===================================\n";

try {
    $stmt = $db->query("SELECT * FROM competitions LIMIT 1");
    $comp = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($comp) {
        echo "✅ Test d'affichage pour la première compétition :\n";
        
        // Simuler le code de la vue
        $tests = [
            'name' => $comp['name'] ?? 'N/A',
            'format_label' => $comp['format_label'] ?? 'N/A',
            'type_label' => $comp['type_label'] ?? 'N/A',
            'season' => $comp['season'] ?? 'N/A',
            'status' => $comp['status'] ?? 'N/A'
        ];
        
        foreach ($tests as $field => $value) {
            $type = gettype($value);
            echo "   {$field} :\n";
            echo "      Type : {$type}\n";
            echo "      Valeur : ";
            
            if (is_string($value)) {
                echo "STRING - '{$value}'\n";
            } elseif (is_array($value)) {
                echo "ARRAY - " . json_encode($value) . "\n";
            } elseif (is_null($value)) {
                echo "NULL\n";
            } else {
                echo "AUTRE - " . var_export($value, true) . "\n";
            }
            
            // Test de sécurité
            if (is_string($value)) {
                $safe = htmlspecialchars($value);
                echo "      ✅ htmlspecialchars OK : '{$safe}'\n";
            } else {
                echo "      ❌ htmlspecialchars ÉCHEC - Type non string\n";
            }
            
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur simulation affichage : " . $e->getMessage() . "\n";
}

echo "\n";

// RÉSUMÉ ET RECOMMANDATIONS
echo "🎯 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "=============================\n";

echo "🔍 PROBLÈME IDENTIFIÉ :\n";
echo "   TypeError: htmlspecialchars(): Argument #1 ($string) must be of type string, array given\n";
echo "   Cela signifie qu'un champ attendu comme string est en fait un array\n\n";

echo "🔧 SOLUTIONS RECOMMANDÉES :\n";
echo "   1. Vérifier que tous les champs sont bien des strings\n";
echo "   2. Ajouter des vérifications de type plus strictes\n";
echo "   3. Convertir les arrays en strings avec json_encode()\n";
echo "   4. Nettoyer la base de données si nécessaire\n\n";

echo "📋 PROCHAINES ÉTAPES :\n";
echo "   1. Identifier le champ problématique exact\n";
echo "   2. Corriger le type de données dans la base\n";
echo "   3. Renforcer les vérifications dans la vue\n";
echo "   4. Tester l'affichage\n\n";

echo "🎉 DIAGNOSTIC TERMINÉ !\n";
?>







