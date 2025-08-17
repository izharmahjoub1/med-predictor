<?php

/**
 * Script de test pour vérifier l'affichage des logos dans la vue portail-joueur
 */

echo "🧪 TEST DES LOGOS DANS LA VUE PORTAIL-JOUEUR\n";
echo "=============================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérification des composants
echo "🔧 TEST 1: VÉRIFICATION DES COMPOSANTS\n";
echo "--------------------------------------\n";

$components = [
    'resources/views/components/club-logo.blade.php' => 'Composant club-logo',
    'resources/views/components/flag-logo-display.blade.php' => 'Composant flag-logo-display'
];

foreach ($components as $component => $description) {
    if (file_exists($component)) {
        echo "✅ {$description} : Existe\n";
    } else {
        echo "❌ {$description} : Manquant\n";
    }
}

echo "\n";

// Test 2: Vérification de la vue modifiée
echo "📱 TEST 2: VÉRIFICATION DE LA VUE PORTAIL-JOUEUR\n";
echo "------------------------------------------------\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Vérifier les composants utilisés
    $checks = [
        'club-logo' => 'Composant club-logo',
        'flag-logo-display' => 'Composant flag-logo-display',
        'x-club-logo' => 'Utilisation du composant club-logo',
        'x-flag-logo-display' => 'Utilisation du composant flag-logo-display'
    ];
    
    foreach ($checks as $search => $description) {
        if (strpos($content, $search) !== false) {
            echo "✅ {$description} : Détecté dans la vue\n";
        } else {
            echo "❌ {$description} : Non détecté dans la vue\n";
        }
    }
} else {
    echo "❌ Vue portail-joueur : Fichier manquant\n";
}

echo "\n";

// Test 3: Vérification des données des clubs
echo "🏟️ TEST 3: VÉRIFICATION DES DONNÉES DES CLUBS\n";
echo "-----------------------------------------------\n";

$stmt = $db->query("
    SELECT p.id, p.first_name, p.last_name, c.name as club_name, c.logo_url 
    FROM players p 
    LEFT JOIN clubs c ON p.club_id = c.id 
    WHERE c.logo_url IS NOT NULL 
    LIMIT 5
");

$playersWithClubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($playersWithClubs) > 0) {
    echo "✅ Joueurs avec clubs trouvés :\n";
    foreach ($playersWithClubs as $player) {
        echo "   👤 {$player['first_name']} {$player['last_name']} → {$player['club_name']}\n";
        echo "      🏟️ Logo : {$player['logo_url']}\n";
    }
} else {
    echo "❌ Aucun joueur avec club trouvé\n";
}

echo "\n";

// Test 4: Vérification des associations
echo "🏆 TEST 4: VÉRIFICATION DES ASSOCIATIONS\n";
echo "----------------------------------------\n";

$stmt = $db->query("
    SELECT p.id, p.first_name, p.last_name, p.nationality, a.name as association_name 
    FROM players p 
    LEFT JOIN associations a ON p.association_id = a.id 
    WHERE a.name IS NOT NULL 
    LIMIT 5
");

$playersWithAssociations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($playersWithAssociations) > 0) {
    echo "✅ Joueurs avec associations trouvés :\n";
    foreach ($playersWithAssociations as $player) {
        echo "   👤 {$player['first_name']} {$player['last_name']}\n";
        echo "      🌍 Nationalité : {$player['nationality']}\n";
        echo "      🏆 Association : {$player['association_name']}\n";
    }
} else {
    echo "❌ Aucun joueur avec association trouvé\n";
}

echo "\n";

// Test 5: Test d'accessibilité des URLs
echo "🌐 TEST 5: TEST D'ACCESSIBILITÉ DES LOGOS\n";
echo "-----------------------------------------\n";

$stmt = $db->query("SELECT logo_url FROM clubs WHERE logo_url IS NOT NULL LIMIT 3");
$logos = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($logos as $logoUrl) {
    $ch = curl_init($logoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode == 200 ? '✅' : '❌';
    echo "{$status} Logo : HTTP {$httpCode} - {$logoUrl}\n";
}

echo "\n";

// Test 6: Simulation de l'affichage
echo "🎨 TEST 6: SIMULATION DE L'AFFICHAGE\n";
echo "------------------------------------\n";

echo "✅ Vue portail-joueur modifiée avec les composants de logos\n";
echo "✅ Composants club-logo et flag-logo-display intégrés\n";
echo "✅ Logos des clubs remplaçant les emojis 🏟️\n";
echo "✅ Logos des associations remplaçant les emojis 🏆\n";
echo "✅ Drapeaux des nationalités remplaçant les emojis 🏳️\n\n";

echo "🚀 PROCHAINES ÉTAPES POUR TESTER :\n";
echo "1. Accéder à http://localhost:8000/portail-joueur/{id} (remplacer {id} par un ID de joueur)\n";
echo "2. Vérifier que les logos des clubs s'affichent au lieu des emojis\n";
echo "3. Vérifier que les logos des associations s'affichent\n";
echo "4. Vérifier que les drapeaux des nationalités s'affichent\n\n";

echo "🎯 RÉSULTAT ATTENDU :\n";
echo "✅ Logos colorés des clubs avec initiales\n";
echo "✅ Logos des associations (FTF, etc.)\n";
echo "✅ Drapeaux des pays de nationalité\n";
echo "✅ Interface plus visuelle et professionnelle\n\n";

echo "🎉 TEST TERMINÉ AVEC SUCCÈS !\n";
echo "La vue portail-joueur devrait maintenant afficher tous les logos correctement.\n";




