<?php

/**
 * Test simple pour vérifier l'affichage des logos simplifiés
 */

echo "🧪 TEST DES LOGOS SIMPLIFIÉS\n";
echo "============================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérification des données d'un joueur avec club et association
echo "👥 TEST 1: DONNÉES D'UN JOUEUR\n";
echo "-------------------------------\n";

$playerId = 88; // Ali Jebali
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
    echo "   🏆 Association : {$player['association_name']} (ID: {$player['association_id']})\n";
    echo "   🏆 Pays Association : {$player['association_country']}\n";
} else {
    echo "❌ Joueur non trouvé\n";
}

echo "\n";

// Test 2: Simulation de l'affichage des logos
echo "🎨 TEST 2: SIMULATION DE L'AFFICHAGE\n";
echo "------------------------------------\n";

if ($player) {
    echo "🎯 Logos qui devraient s'afficher :\n";
    
    // Logo du club
    if ($player['club_name'] && $player['logo_url']) {
        echo "   🏟️ Club {$player['club_name']} :\n";
        echo "      - Logo URL : {$player['logo_url']}\n";
        echo "      - Fallback : " . strtoupper(substr($player['club_name'], 0, 2)) . " (bleu)\n";
    } else {
        echo "   🏟️ Club : Emoji 🏟️\n";
    }
    
    // Logo de l'association
    if ($player['association_name']) {
        echo "   🏆 Association {$player['association_name']} :\n";
        if (str_contains(strtolower($player['association_name']), 'ftf')) {
            echo "      - Logo : FTF (bleu)\n";
        } else {
            echo "      - Logo : " . strtoupper(substr($player['association_name'], 0, 3)) . " (gris)\n";
        }
    } else {
        echo "   🏆 Association : Emoji 🏆\n";
    }
    
    // Drapeau de nationalité
    if ($player['nationality']) {
        echo "   🏳️ Nationalité {$player['nationality']} :\n";
        echo "      - Drapeau : flagcdn.com/w80/" . strtolower($player['nationality']) . ".png\n";
    } else {
        echo "   🏳️ Nationalité : Emoji 🏳️\n";
    }
    
    // Drapeau du pays de la fédération
    if ($player['association_country']) {
        echo "   🏳️ Pays fédération {$player['association_country']} :\n";
        echo "      - Drapeau : flagcdn.com/w80/" . strtolower($player['association_country']) . ".png\n";
    } else {
        echo "   🏳️ Pays fédération : Emoji 🏳️\n";
    }
}

echo "\n";

// Test 3: Vérification des URLs des logos
echo "🌐 TEST 3: TEST D'ACCESSIBILITÉ DES LOGOS\n";
echo "----------------------------------------\n";

if ($player && $player['logo_url']) {
    $ch = curl_init($player['logo_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode == 200 ? '✅' : '❌';
    echo "{$status} Logo du club : HTTP {$httpCode} - {$player['logo_url']}\n";
} else {
    echo "⚠️ Pas de logo de club à tester\n";
}

echo "\n";

// Test 4: Instructions de test
echo "🚀 INSTRUCTIONS DE TEST\n";
echo "-----------------------\n";

echo "1. Accéder à : http://localhost:8000/portail-joueur/88\n";
echo "2. Vérifier que les logos s'affichent :\n";
echo "   ✅ Logo du club AS Gabès (violet avec 'AG')\n";
echo "   ✅ Logo de l'association FTF (bleu avec 'FTF')\n";
echo "   ✅ Drapeau de la Tunisie (nationalité)\n";
echo "   ✅ Drapeau de la Tunisie (pays de la fédération)\n";
echo "\n";

echo "🎯 RÉSULTAT ATTENDU :\n";
echo "✅ Logos des clubs visibles avec initiales colorées\n";
echo "✅ Logo FTF visible en bleu\n";
echo "✅ Drapeaux des nationalités et fédérations visibles\n";
echo "✅ Interface claire et informative\n\n";

echo "🎉 TEST TERMINÉ !\n";
echo "Les logos simplifiés devraient maintenant s'afficher correctement.\n";




