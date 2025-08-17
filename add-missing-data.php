<?php
/**
 * Script pour ajouter les données manquantes : SDOH et Performance VS grandes équipes
 */

echo "🔧 AJOUT DES DONNÉES MANQUANTES\n";
echo "===============================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier la structure des tables
echo "🔍 TEST 1: STRUCTURE DES TABLES\n";
echo "===============================\n";

$tables = ['players', 'health_scores', 'performance_stats', 'sdoh_data'];
foreach ($tables as $table) {
    try {
        $stmt = $db->query("PRAGMA table_info({$table})");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "✅ Table {$table} : " . count($columns) . " colonnes\n";
        
        if (count($columns) > 0) {
            echo "   Colonnes principales : ";
            $mainCols = array_slice(array_column($columns, 'name'), 0, 5);
            echo implode(', ', $mainCols);
            if (count($columns) > 5) echo " (+" . (count($columns) - 5) . " autres)";
            echo "\n";
        }
    } catch (Exception $e) {
        echo "❌ Table {$table} : " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 2: Vérifier les données existantes
echo "📊 TEST 2: DONNÉES EXISTANTES\n";
echo "=============================\n";

// Vérifier les joueurs avec données
$stmt = $db->query("SELECT COUNT(*) as total FROM players");
$totalPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
echo "👥 Total des joueurs : {$totalPlayers}\n";

// Vérifier les données SDOH
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM sdoh_data");
    $totalSDOH = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "🌍 Données SDOH : {$totalSDOH}\n";
} catch (Exception $e) {
    echo "🌍 Données SDOH : Table non trouvée\n";
}

// Vérifier les données de performance
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM performance_stats");
    $totalPerf = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "⚽ Données de performance : {$totalPerf}\n";
} catch (Exception $e) {
    echo "⚽ Données de performance : Table non trouvée\n";
}

echo "\n";

// Test 3: Créer ou mettre à jour les tables si nécessaire
echo "🏗️ TEST 3: CRÉATION/MISE À JOUR DES TABLES\n";
echo "==========================================\n";

// Table SDOH
$createSDOH = "
CREATE TABLE IF NOT EXISTS sdoh_data (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    player_id INTEGER NOT NULL,
    social_support_score DECIMAL(3,2) DEFAULT 0.75,
    housing_stability_score DECIMAL(3,2) DEFAULT 0.80,
    education_score DECIMAL(3,2) DEFAULT 0.70,
    cultural_adaptation_score DECIMAL(3,2) DEFAULT 0.65,
    financial_stability_score DECIMAL(3,2) DEFAULT 0.70,
    family_situation TEXT DEFAULT 'Stable',
    living_conditions TEXT DEFAULT 'Club housing',
    language_barriers TEXT DEFAULT 'French/Arabic',
    social_network_score DECIMAL(3,2) DEFAULT 0.75,
    overall_sdoh_score DECIMAL(3,2) DEFAULT 0.72,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id)
)";

try {
    $db->exec($createSDOH);
    echo "✅ Table sdoh_data créée/vérifiée\n";
} catch (Exception $e) {
    echo "❌ Erreur création table sdoh_data : " . $e->getMessage() . "\n";
}

// Table Performance VS grandes équipes
$createPerfVS = "
CREATE TABLE IF NOT EXISTS performance_vs_top_teams (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    player_id INTEGER NOT NULL,
    opponent_team TEXT NOT NULL,
    match_date DATE,
    goals_scored INTEGER DEFAULT 0,
    assists INTEGER DEFAULT 0,
    minutes_played INTEGER DEFAULT 90,
    pass_accuracy DECIMAL(5,2) DEFAULT 85.0,
    tackles_won INTEGER DEFAULT 0,
    interceptions INTEGER DEFAULT 0,
    shots_on_target INTEGER DEFAULT 0,
    duels_won INTEGER DEFAULT 0,
    rating DECIMAL(3,1) DEFAULT 7.0,
    performance_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id)
)";

try {
    $db->exec($createPerfVS);
    echo "✅ Table performance_vs_top_teams créée/vérifiée\n";
} catch (Exception $e) {
    echo "❌ Erreur création table performance_vs_top_teams : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Ajouter des données SDOH pour les joueurs existants
echo "🌍 TEST 4: AJOUT DES DONNÉES SDOH\n";
echo "=================================\n";

$stmt = $db->query("SELECT id, first_name, last_name, nationality FROM players ORDER BY id LIMIT 10");
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sdohAdded = 0;
foreach ($players as $player) {
    try {
        // Vérifier si les données SDOH existent déjà
        $checkStmt = $db->prepare("SELECT COUNT(*) as count FROM sdoh_data WHERE player_id = ?");
        $checkStmt->execute([$player['id']]);
        $exists = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        
        if (!$exists) {
            // Générer des scores SDOH réalistes basés sur la nationalité
            $nationality = strtolower($player['nationality']);
            
            // Scores adaptés selon la nationalité
            if (strpos($nationality, 'tunisie') !== false) {
                $socialSupport = 0.85; // Support familial fort
                $housingStability = 0.90; // Logement stable
                $culturalAdaptation = 0.95; // Pas de barrière culturelle
                $languageBarriers = 'Aucune barrière';
            } elseif (strpos($nationality, 'maroc') !== false || strpos($nationality, 'algérie') !== false) {
                $socialSupport = 0.80;
                $housingStability = 0.85;
                $culturalAdaptation = 0.90;
                $languageBarriers = 'Minimales';
            } else {
                $socialSupport = 0.70;
                $housingStability = 0.75;
                $culturalAdaptation = 0.65;
                $languageBarriers = 'Modérées';
            }
            
            $overallScore = ($socialSupport + 0.80 + 0.70 + $culturalAdaptation + 0.70) / 5;
            
            $insertStmt = $db->prepare("
                INSERT INTO sdoh_data (
                    player_id, social_support_score, housing_stability_score, 
                    education_score, cultural_adaptation_score, financial_stability_score,
                    family_situation, living_conditions, language_barriers,
                    social_network_score, overall_sdoh_score
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $insertStmt->execute([
                $player['id'], $socialSupport, 0.80, 0.70, $culturalAdaptation, 0.70,
                'Stable', 'Club housing', $languageBarriers, 0.75, $overallScore
            ]);
            
            if ($result) {
                echo "✅ SDOH ajouté pour {$player['first_name']} {$player['last_name']} (Score: " . number_format($overallScore, 2) . ")\n";
                $sdohAdded++;
            }
        } else {
            echo "ℹ️  SDOH déjà existant pour {$player['first_name']} {$player['last_name']}\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur SDOH pour {$player['first_name']} {$player['last_name']} : " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Total SDOH ajoutés : {$sdohAdded}\n";

echo "\n";

// Test 5: Ajouter des données de performance VS grandes équipes
echo "⚽ TEST 5: AJOUT DES DONNÉES DE PERFORMANCE VS GRANDES ÉQUIPES\n";
echo "============================================================\n";

$topTeams = ['Real Madrid', 'Barcelona', 'Manchester City', 'Liverpool', 'Bayern Munich', 'PSG', 'Juventus', 'AC Milan'];
$perfAdded = 0;

foreach ($players as $player) {
    // Ajouter 2-3 performances VS grandes équipes par joueur
    $numMatches = rand(2, 3);
    
    for ($i = 0; $i < $numMatches; $i++) {
        try {
            $opponent = $topTeams[array_rand($topTeams)];
            $matchDate = date('Y-m-d', strtotime('-' . rand(1, 365) . ' days'));
            
            // Générer des stats réalistes
            $goals = rand(0, 2);
            $assists = rand(0, 1);
            $minutes = rand(45, 90);
            $passAccuracy = 75 + rand(0, 20);
            $tackles = rand(0, 5);
            $interceptions = rand(0, 3);
            $shots = rand(0, 4);
            $duels = rand(2, 8);
            $rating = 6.0 + (rand(0, 40) / 10);
            
            $insertStmt = $db->prepare("
                INSERT INTO performance_vs_top_teams (
                    player_id, opponent_team, match_date, goals_scored, assists,
                    minutes_played, pass_accuracy, tackles_won, interceptions,
                    shots_on_target, duels_won, rating, performance_notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $notes = "Performance " . ($rating >= 7.5 ? "excellente" : ($rating >= 6.5 ? "bonne" : "moyenne")) . " contre {$opponent}";
            
            $result = $insertStmt->execute([
                $player['id'], $opponent, $matchDate, $goals, $assists,
                $minutes, $passAccuracy, $tackles, $interceptions,
                $shots, $duels, $rating, $notes
            ]);
            
            if ($result) {
                $perfAdded++;
            }
        } catch (Exception $e) {
            // Ignorer les erreurs de doublon
        }
    }
    
    echo "✅ Performance VS grandes équipes ajoutée pour {$player['first_name']} {$player['last_name']}\n";
}

echo "\n📊 Total performances ajoutées : {$perfAdded}\n";

echo "\n";

// Test 6: Vérification finale
echo "🎯 TEST 6: VÉRIFICATION FINALE\n";
echo "==============================\n";

try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM sdoh_data");
    $totalSDOHFinal = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "🌍 Données SDOH finales : {$totalSDOHFinal}\n";
} catch (Exception $e) {
    echo "🌍 Données SDOH finales : Erreur\n";
}

try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM performance_vs_top_teams");
    $totalPerfFinal = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "⚽ Données de performance finales : {$totalPerfFinal}\n";
} catch (Exception $e) {
    echo "⚽ Données de performance finales : Erreur\n";
}

echo "\n🎉 AJOUT DES DONNÉES MANQUANTES TERMINÉ !\n";
echo "==========================================\n";
echo "✅ Tables créées/vérifiées\n";
echo "✅ Données SDOH ajoutées\n";
echo "✅ Données de performance VS grandes équipes ajoutées\n";
echo "\n🚀 Prochaine étape : Améliorer la barre de recherche\n";
?>




