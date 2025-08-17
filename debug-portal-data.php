<?php

/**
 * Script de débogage des données du portail joueur
 */

echo "🔍 DÉBOGAGE DES DONNÉES DU PORTAL JOUEUR\n";
echo "=========================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérification des données d'un joueur
echo "👥 TEST 1: DONNÉES D'UN JOUEUR\n";
echo "-------------------------------\n";

$playerId = 88; // Ali Jebali (existe bien)
$stmt = $db->prepare("
    SELECT p.*, c.name as club_name, c.logo_url, c.logo_path, c.country as club_country,
           a.name as association_name, a.country as association_country
    FROM players p 
    LEFT JOIN clubs c ON p.club_id = c.id 
    LEFT JOIN associations a ON p.association_id = a.id 
    WHERE p.id = ?
");

$stmt->execute([$playerId]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

if ($player) {
    echo "✅ Joueur trouvé : {$player['first_name']} {$player['last_name']}\n";
    echo "   🌍 Nationalité : {$player['nationality']}\n";
    echo "   🏟️ Club : {$player['club_name']} (ID: {$player['club_id']})\n";
    echo "   🏟️ Logo URL : {$player['logo_url']}\n";
    echo "   🏟️ Logo Path : {$player['logo_path']}\n";
    echo "   🏟️ Pays Club : {$player['club_country']}\n";
    echo "   🏆 Association : {$player['association_name']} (ID: {$player['association_id']})\n";
    echo "   🏆 Pays Association : {$player['association_country']}\n";
} else {
    echo "❌ Joueur non trouvé\n";
}

echo "\n";

// Test 2: Vérification des clubs avec logos
echo "🏟️ TEST 2: CLUBS AVEC LOGOS\n";
echo "-----------------------------\n";

$stmt = $db->query("SELECT id, name, logo_url, logo_path FROM clubs WHERE logo_url IS NOT NULL OR logo_path IS NOT NULL LIMIT 5");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($clubs) > 0) {
    echo "✅ Clubs avec logos trouvés :\n";
    foreach ($clubs as $club) {
        echo "   🏟️ {$club['name']} (ID: {$club['id']})\n";
        echo "      Logo URL : {$club['logo_url']}\n";
        echo "      Logo Path : {$club['logo_path']}\n";
    }
} else {
    echo "❌ Aucun club avec logo trouvé\n";
}

echo "\n";

// Test 3: Vérification des associations
echo "🏆 TEST 3: ASSOCIATIONS\n";
echo "----------------------\n";

$stmt = $db->query("SELECT id, name, country FROM associations LIMIT 5");
$associations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($associations) > 0) {
    echo "✅ Associations trouvées :\n";
    foreach ($associations as $assoc) {
        echo "   🏆 {$assoc['name']} (ID: {$assoc['id']}) - Pays: {$assoc['country']}\n";
    }
} else {
    echo "❌ Aucune association trouvée\n";
}

echo "\n";

// Test 4: Test des composants
echo "🔧 TEST 4: COMPOSANTS\n";
echo "--------------------\n";

$components = [
    'resources/views/components/club-logo.blade.php' => 'Composant club-logo',
    'resources/views/components/flag-logo-display.blade.php' => 'Composant flag-logo-display'
];

foreach ($components as $component => $description) {
    if (file_exists($component)) {
        $content = file_get_contents($component);
        if (strpos($content, '@props') !== false) {
            echo "✅ {$description} : Existe et valide\n";
        } else {
            echo "⚠️ {$description} : Existe mais structure invalide\n";
        }
    } else {
        echo "❌ {$description} : Fichier manquant\n";
    }
}

echo "\n";

// Test 5: Simulation d'affichage
echo "🎨 TEST 5: SIMULATION D'AFFICHAGE\n";
echo "--------------------------------\n";

if ($player) {
    echo "🎯 Données pour l'affichage :\n";
    echo "   📸 Photo : " . ($player['photo_url'] ? 'Disponible' : 'Non disponible') . "\n";
    echo "   🏳️ Drapeau nationalité : " . ($player['nationality'] ? "flagcdn.com/w80/" . strtolower($player['nationality']) . ".png" : 'Non défini') . "\n";
    echo "   🏟️ Logo club : " . ($player['logo_url'] ? $player['logo_url'] : 'Non disponible') . "\n";
    echo "   🏆 Logo association : " . ($player['association_name'] ? 'Logo ' . $player['association_name'] : 'Non disponible') . "\n";
    echo "   🏳️ Drapeau fédération : " . ($player['association_country'] ? "flagcdn.com/w80/" . strtolower($player['association_country']) . ".png" : 'Non défini') . "\n";
}

echo "\n🔍 DIAGNOSTIC :\n";
echo "1. Vérifier que les composants sont bien chargés par Laravel\n";
echo "2. Vérifier que les données sont correctement passées à la vue\n";
echo "3. Vérifier que les URLs des logos sont accessibles\n";
echo "4. Vérifier que les couleurs des composants s'affichent sur le fond sombre\n\n";

echo "🎉 DÉBOGAGE TERMINÉ !\n";
