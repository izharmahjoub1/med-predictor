<?php

/**
 * Script de test pour vérifier l'accès aux données des joueurs
 */

echo "🧪 TEST D'ACCÈS AUX DONNÉES DES JOUEURS\n";
echo "=========================================\n\n";

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

// Test 1: Nombre total de joueurs
$stmt = $db->query("SELECT COUNT(*) as total FROM players");
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
echo "📊 TEST 1 - Nombre total de joueurs : {$total}\n";

// Test 2: Joueurs avec FIT Score
$stmt = $db->query("SELECT COUNT(*) as total FROM players WHERE ghs_overall_score > 0");
$withFitScore = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
echo "📊 TEST 2 - Joueurs avec FIT Score : {$withFitScore}\n";

// Test 3: Répartition par nationalité
echo "\n🌍 TEST 3 - Répartition par nationalité :\n";
$stmt = $db->query("SELECT nationality, COUNT(*) as count FROM players GROUP BY nationality ORDER BY count DESC");
$nationalities = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($nationalities as $nat) {
    echo "   {$nat['nationality']}: {$nat['count']} joueurs\n";
}

// Test 4: Répartition par club
echo "\n🏟️ TEST 4 - Répartition par club :\n";
$stmt = $db->query("
    SELECT c.name as club_name, COUNT(p.id) as count 
    FROM players p 
    JOIN clubs c ON p.club_id = c.id 
    GROUP BY c.name 
    ORDER BY count DESC
");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($clubs as $club) {
    echo "   {$club['club_name']}: {$club['count']} joueurs\n";
}

// Test 5: Statistiques des FIT Scores
echo "\n📈 TEST 5 - Statistiques des FIT Scores :\n";
$stmt = $db->query("
    SELECT 
        MIN(ghs_overall_score) as min_score,
        MAX(ghs_overall_score) as max_score,
        AVG(ghs_overall_score) as avg_score
    FROM players 
    WHERE ghs_overall_score > 0
");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
echo "   Score minimum : " . round($stats['min_score'], 1) . "\n";
echo "   Score maximum : " . round($stats['max_score'], 1) . "\n";
echo "   Score moyen : " . round($stats['avg_score'], 1) . "\n";

// Test 6: Exemple de joueur complet
echo "\n👤 TEST 6 - Exemple de joueur complet :\n";
$stmt = $db->query("
    SELECT 
        p.name, p.nationality, p.position, p.age,
        p.ghs_overall_score, p.ghs_physical_score, p.ghs_mental_score,
        p.ghs_civic_score, p.ghs_sleep_score,
        p.market_value, p.availability, p.form_percentage,
        c.name as club_name
    FROM players p 
    JOIN clubs c ON p.club_id = c.id 
    WHERE p.ghs_overall_score > 0 
    ORDER BY p.ghs_overall_score DESC 
    LIMIT 1
");
$player = $stmt->fetch(PDO::FETCH_ASSOC);
if ($player) {
    echo "   Nom : {$player['name']}\n";
    echo "   Nationalité : {$player['nationality']}\n";
    echo "   Poste : {$player['position']}\n";
    echo "   Âge : {$player['age']}\n";
    echo "   Club : {$player['club_name']}\n";
    echo "   FIT Score global : {$player['ghs_overall_score']}\n";
    echo "   Scores par pilier :\n";
    echo "     - Santé (Physical) : {$player['ghs_physical_score']}\n";
    echo "     - Performance (Mental) : {$player['ghs_mental_score']}\n";
    echo "     - SDOH (Civic) : {$player['ghs_civic_score']}\n";
    echo "     - Adhérence (Sleep) : {$player['ghs_sleep_score']}\n";
    echo "   Valeur marchande : " . number_format($player['market_value'], 0, ',', ' ') . " €\n";
    echo "   Disponibilité : {$player['availability']}\n";
    echo "   Forme : {$player['form_percentage']}%\n";
}

// Test 7: Vérification des données de santé
echo "\n🏥 TEST 7 - Vérification des données de santé :\n";
$stmt = $db->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN injury_risk_level = 'high' THEN 1 ELSE 0 END) as high_risk,
        SUM(CASE WHEN injury_risk_level = 'medium' THEN 1 ELSE 0 END) as medium_risk,
        SUM(CASE WHEN injury_risk_level = 'low' THEN 1 ELSE 0 END) as low_risk
    FROM players 
    WHERE injury_risk_level IS NOT NULL
");
$healthStats = $stmt->fetch(PDO::FETCH_ASSOC);
echo "   Joueurs avec évaluation des risques : {$healthStats['total']}\n";
echo "   Risque élevé : {$healthStats['high_risk']}\n";
echo "   Risque moyen : {$healthStats['medium_risk']}\n";
echo "   Risque faible : {$healthStats['low_risk']}\n";

echo "\n🎉 TESTS TERMINÉS AVEC SUCCÈS !\n";
echo "Les données sont bien accessibles dans la base de données.\n";
echo "La route /players nécessite une authentification (d'où le code 302).\n";
echo "Vous pouvez maintenant utiliser ces données dans votre application FIT.\n";




