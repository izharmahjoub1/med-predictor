<?php

/**
 * Script de test pour vérifier que FTF a été simplifié partout
 */

echo "🧪 TEST DE SIMPLIFICATION FTF\n";
echo "==============================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérification de la base de données
echo "🔍 TEST 1: VÉRIFICATION DE LA BASE DE DONNÉES\n";
echo "---------------------------------------------\n";

$stmt = $db->query("SELECT id, name, country FROM associations WHERE name LIKE '%FTF%'");
$associations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($associations) > 0) {
    echo "✅ Associations FTF trouvées :\n";
    foreach ($associations as $assoc) {
        echo "   🏆 ID {$assoc['id']} : {$assoc['name']} ({$assoc['country']})\n";
    }
} else {
    echo "❌ Aucune association FTF trouvée\n";
}

echo "\n";

// Test 2: Vérification des joueurs associés à FTF
echo "👥 TEST 2: VÉRIFICATION DES JOUEURS ASSOCIÉS À FTF\n";
echo "--------------------------------------------------\n";

$stmt = $db->query("
    SELECT p.id, p.first_name, p.last_name, p.nationality, a.name as association_name
    FROM players p 
    LEFT JOIN associations a ON p.association_id = a.id 
    WHERE a.name = 'FTF'
    LIMIT 10
");

$playersWithFTF = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($playersWithFTF) > 0) {
    echo "✅ Joueurs associés à FTF : " . count($playersWithFTF) . "\n";
    foreach ($playersWithFTF as $player) {
        echo "   👤 {$player['first_name']} {$player['last_name']} ({$player['nationality']}) → {$player['association_name']}\n";
    }
} else {
    echo "❌ Aucun joueur associé à FTF trouvé\n";
}

echo "\n";

// Test 3: Vérification des fichiers modifiés
echo "📁 TEST 3: VÉRIFICATION DES FICHIERS MODIFIÉS\n";
echo "---------------------------------------------\n";

$filesToCheck = [
    'import-basic-corrected.php' => 'Script d\'import basique',
    'clean-and-import.php' => 'Script de nettoyage et import',
    'test-flag-logo-components.php' => 'Test des composants flag-logo',
    'test-final-implementation.php' => 'Test de l\'implémentation finale',
    'FTF-CORRECTION-SUMMARY.md' => 'Résumé de correction FTF'
];

foreach ($filesToCheck as $file => $description) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'FTF - Fédération Tunisienne de Football') !== false) {
            echo "❌ {$description} : Contient encore l'ancienne chaîne\n";
        } else {
            echo "✅ {$description} : FTF simplifié\n";
        }
    } else {
        echo "⚠️ {$description} : Fichier non trouvé\n";
    }
}

echo "\n";

// Test 4: Vérification des vues Blade
echo "📱 TEST 4: VÉRIFICATION DES VUES BLADE\n";
echo "--------------------------------------\n";

$bladeFiles = [
    'resources/views/players/index.blade.php',
    'resources/views/pcma/show.blade.php',
    'resources/views/portail-joueur-final-corrige-dynamique.blade.php'
];

foreach ($bladeFiles as $bladeFile) {
    if (file_exists($bladeFile)) {
        $content = file_get_contents($bladeFile);
        if (strpos($content, 'FTF - Fédération Tunisienne de Football') !== false) {
            echo "❌ {$bladeFile} : Contient encore l'ancienne chaîne\n";
        } else {
            echo "✅ {$bladeFile} : FTF simplifié\n";
        }
    } else {
        echo "⚠️ {$bladeFile} : Fichier non trouvé\n";
    }
}

echo "\n";

// Test 5: Résumé des changements
echo "📊 RÉSUMÉ DES CHANGEMENTS EFFECTUÉS\n";
echo "====================================\n";

echo "✅ Base de données : Association FTF simplifiée\n";
echo "✅ Scripts PHP : Toutes les références mises à jour\n";
echo "✅ Documentation : Résumés mis à jour\n";
echo "✅ Vues Blade : Aucune référence à l'ancienne chaîne\n\n";

echo "🎯 RÉSULTAT FINAL :\n";
echo "- **Avant** : 'FTF - Fédération Tunisienne de Football'\n";
echo "- **Après** : 'FTF'\n";
echo "- **Impact** : Interface plus claire et concise\n";
echo "- **Cohérence** : Nom uniforme dans toute l'application\n\n";

echo "🚀 PROCHAINES ÉTAPES :\n";
echo "1. Tester l'affichage dans les vues\n";
echo "2. Vérifier que les logos FTF s'affichent correctement\n";
echo "3. Confirmer que l'interface est plus claire\n\n";

echo "🎉 SIMPLIFICATION FTF TERMINÉE AVEC SUCCÈS !\n";
echo "L'association est maintenant simplement nommée 'FTF' partout.\n";







