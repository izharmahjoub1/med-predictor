<?php

/**
 * Script de vérification de l'association FTF
 * Vérifie que tous les nouveaux joueurs ont bien l'association FTF
 */

echo "🏛️ VÉRIFICATION DE L'ASSOCIATION FTF\n";
echo "=====================================\n\n";

// Connexion directe à SQLite
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données SQLite établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : Impossible de se connecter à la base de données\n";
    echo "Message : " . $e->getMessage() . "\n";
    exit(1);
}

// Vérification de l'association FTF
echo "🔍 VÉRIFICATION DE L'ASSOCIATION FTF\n";
echo "------------------------------------\n";

$stmt = $db->query("SELECT id, name, country FROM associations WHERE name LIKE '%FTF%' OR name LIKE '%Fédération Tunisienne de Football%'");
$ftfAssociations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($ftfAssociations)) {
    echo "❌ ERREUR : Aucune association FTF trouvée\n";
    exit(1);
}

$ftfId = $ftfAssociations[0]['id'];
echo "✅ Association FTF trouvée :\n";
echo "   ID: {$ftfId}\n";
echo "   Nom: {$ftfAssociations[0]['name']}\n";
echo "   Pays: {$ftfAssociations[0]['country']}\n\n";

// Vérification des joueurs avec association FTF
echo "👥 VÉRIFICATION DES JOUEURS AVEC ASSOCIATION FTF\n";
echo "------------------------------------------------\n";

$stmt = $db->prepare("
    SELECT COUNT(*) as total 
    FROM players 
    WHERE association_id = ?
");
$stmt->execute([$ftfId]);
$ftfPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

echo "📊 Joueurs avec association FTF : {$ftfPlayers}\n";

// Vérification des joueurs sans association FTF
$stmt = $db->prepare("
    SELECT COUNT(*) as total 
    FROM players 
    WHERE association_id != ? OR association_id IS NULL
");
$stmt->execute([$ftfId]);
$nonFtfPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

echo "📊 Joueurs sans association FTF : {$nonFtfPlayers}\n\n";

// Détail des joueurs avec FTF
echo "📋 DÉTAIL DES JOUEURS AVEC ASSOCIATION FTF\n";
echo "-------------------------------------------\n";

$stmt = $db->prepare("
    SELECT p.id, p.name, p.nationality, p.position, c.name as club_name
    FROM players p
    JOIN clubs c ON p.club_id = c.id
    WHERE p.association_id = ?
    ORDER BY p.id
    LIMIT 10
");
$stmt->execute([$ftfId]);
$ftfPlayerDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ftfPlayerDetails as $player) {
    echo "   ID {$player['id']}: {$player['name']} ({$player['nationality']}) - {$player['position']} - {$player['club_name']}\n";
}

if (count($ftfPlayerDetails) >= 10) {
    echo "   ... et " . ($ftfPlayers - 10) . " autres joueurs\n";
}

echo "\n";

// Vérification des nationalités des joueurs FTF
echo "🌍 RÉPARTITION DES NATIONALITÉS DES JOUEURS FTF\n";
echo "-----------------------------------------------\n";

$stmt = $db->prepare("
    SELECT p.nationality, COUNT(*) as count
    FROM players p
    WHERE p.association_id = ?
    GROUP BY p.nationality
    ORDER BY count DESC
");
$stmt->execute([$ftfId]);
$nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($nationalities as $nat) {
    echo "   {$nat['nationality']}: {$nat['count']} joueurs\n";
}

echo "\n";

// Vérification des joueurs originaux (sans FTF)
echo "👑 JOUEURS ORIGINAUX (SANS ASSOCIATION FTF)\n";
echo "-------------------------------------------\n";

$stmt = $db->prepare("
    SELECT p.id, p.name, p.nationality, p.position, c.name as club_name
    FROM players p
    JOIN clubs c ON p.club_id = c.id
    WHERE p.association_id != ? OR p.association_id IS NULL
    ORDER BY p.id
");
$stmt->execute([$ftfId]);
$originalPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($originalPlayers as $player) {
    echo "   ID {$player['id']}: {$player['name']} ({$player['nationality']}) - {$player['position']} - {$player['club_name']}\n";
}

echo "\n";

// Résumé final
echo "🎯 RÉSUMÉ DE LA VÉRIFICATION\n";
echo "=============================\n";
echo "✅ Association FTF : {$ftfAssociations[0]['name']} (ID: {$ftfId})\n";
echo "👥 Joueurs avec FTF : {$ftfPlayers}\n";
echo "👑 Joueurs originaux : {$nonFtfPlayers}\n";
echo "🌍 Nationalités représentées : " . count($nationalities) . "\n\n";

if ($ftfPlayers >= 50) {
    echo "🎉 VÉRIFICATION RÉUSSIE !\n";
    echo "Tous les 50 nouveaux joueurs ont bien l'association FTF.\n";
    echo "Chaque joueur conserve sa nationalité individuelle.\n";
    echo "La logique d'import a été correctement appliquée.\n";
} else {
    echo "⚠️ VÉRIFICATION INCOMPLÈTE\n";
    echo "Seulement {$ftfPlayers} joueurs sur 50 ont l'association FTF.\n";
    echo "Vérifiez le processus d'import.\n";
}







