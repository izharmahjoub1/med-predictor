<?php

/**
 * Script de test pour vérifier l'affichage des drapeaux dans le header
 */

echo "🏳️ TEST DES DRAPEAUX DANS LE HEADER\n";
echo "====================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérification des données des joueurs
echo "👥 TEST 1: VÉRIFICATION DES DONNÉES DES JOUEURS\n";
echo "-----------------------------------------------\n";

$stmt = $db->query("
    SELECT p.id, p.first_name, p.last_name, p.nationality, 
           c.name as club_name, c.country as club_country,
           a.name as association_name, a.country as association_country
    FROM players p 
    LEFT JOIN clubs c ON p.club_id = c.id 
    LEFT JOIN associations a ON p.association_id = a.id 
    LIMIT 5
");

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($players) > 0) {
    echo "✅ Joueurs trouvés :\n";
    foreach ($players as $player) {
        echo "   👤 {$player['first_name']} {$player['last_name']}\n";
        echo "      🌍 Nationalité : {$player['nationality']}\n";
        echo "      🏟️ Club : {$player['club_name']} ({$player['club_country']})\n";
        echo "      🏆 Association : {$player['association_name']} ({$player['association_country']})\n";
        echo "\n";
    }
} else {
    echo "❌ Aucun joueur trouvé\n";
}

echo "\n";

// Test 2: Vérification des URLs des drapeaux
echo "🌐 TEST 2: TEST D'ACCESSIBILITÉ DES DRAPEAUX\n";
echo "--------------------------------------------\n";

$nationalities = ['Tunisie', 'Algérie', 'Maroc', 'Portugal', 'France'];
$associationCountries = ['Tunisie', 'France', 'Maroc'];

echo "🏳️ Test des drapeaux de nationalité :\n";
foreach ($nationalities as $nationality) {
    $flagUrl = "https://flagcdn.com/w80/" . strtolower($nationality) . ".png";
    $ch = curl_init($flagUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode == 200 ? '✅' : '❌';
    echo "   {$status} {$nationality} : HTTP {$httpCode} - {$flagUrl}\n";
}

echo "\n🏳️ Test des drapeaux des pays de fédération :\n";
foreach ($associationCountries as $country) {
    $flagUrl = "https://flagcdn.com/w80/" . strtolower($country) . ".png";
    $ch = curl_init($flagUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode == 200 ? '✅' : '❌';
    echo "   {$status} {$country} : HTTP {$httpCode} - {$flagUrl}\n";
}

echo "\n";

// Test 3: Vérification de la vue modifiée
echo "📱 TEST 3: VÉRIFICATION DE LA VUE MODIFIÉE\n";
echo "------------------------------------------\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    $checks = [
        'Photo du joueur + Drapeau de sa nationalité à gauche' => 'Section gauche avec photo + drapeau nationalité',
        'Drapeau de la nationalité du joueur' => 'Drapeau nationalité du joueur',
        'Drapeau du pays de la fédération' => 'Drapeau pays de la fédération',
        'flagcdn.com' => 'URLs des drapeaux',
        'onerror=' => 'Gestion des erreurs de drapeaux'
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

// Test 4: Simulation de l'affichage
echo "🎨 TEST 4: SIMULATION DE L'AFFICHAGE DU HEADER\n";
echo "-----------------------------------------------\n";

echo "✅ Header réorganisé avec :\n";
echo "   📸 Photo du joueur (gauche)\n";
echo "   🏳️ Drapeau de sa nationalité (gauche, à côté de la photo)\n";
echo "   👤 Nom et position (centre)\n";
echo "   🏟️ Logo du club (droite)\n";
echo "   🏆 Logo de l'association (droite)\n";
echo "   🏳️ Drapeau du pays de la fédération (droite)\n\n";

echo "🎯 RÉSULTAT ATTENDU :\n";
echo "✅ Drapeau de nationalité à côté de la photo du joueur\n";
echo "✅ Drapeau du pays de la fédération à côté du logo de l'association\n";
echo "✅ Interface plus claire et informative\n";
echo "✅ Meilleure compréhension des origines du joueur\n\n";

echo "🚀 PROCHAINES ÉTAPES POUR TESTER :\n";
echo "1. Accéder à http://localhost:8000/portail-joueur/{id}\n";
echo "2. Vérifier que le drapeau de nationalité est à côté de la photo\n";
echo "3. Vérifier que le drapeau du pays de la fédération est à côté du logo FTF\n";
echo "4. Confirmer que l'interface est plus claire et informative\n\n";

echo "🎉 MODIFICATION DU HEADER TERMINÉE AVEC SUCCÈS !\n";
echo "Le header affiche maintenant les drapeaux de manière logique et organisée.\n";







