<?php

/**
 * Script d'import simple du Dataset - 50 Joueurs Tunisie 2024-2025
 * Utilise directement SQLite sans Laravel
 */

echo "🚀 IMPORT SIMPLE DU DATASET - 50 JOUEURS TUNISIE 2024-2025\n";
echo "==========================================================\n\n";

// Charger le dataset
$dataset = json_decode(file_get_contents('dataset-50-joueurs-tunisie-2024-2025.json'), true);

if (!$dataset) {
    echo "❌ ERREUR : Impossible de charger le fichier JSON\n";
    exit(1);
}

echo "✅ Fichier JSON chargé avec succès\n";
echo "👥 Nombre de joueurs à importer : " . count($dataset['joueurs']) . "\n\n";

// Connexion directe à SQLite
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données SQLite établie\n";
} catch (Exception $e) {
    echo "❌ ERREUR : Impossible de se connecter à la base de données\n";
    echo "Message : " . $e->getMessage() . "\n";
    exit(1);
}

// Commencer l'import
echo "📥 DÉBUT DE L'IMPORT...\n";
echo "------------------------\n";

$successCount = 0;
$errorCount = 0;

foreach ($dataset['joueurs'] as $joueur) {
    try {
        // Importer le joueur principal
        $playerId = importPlayer($db, $joueur);
        
        if ($playerId) {
            // Importer les données des 5 piliers dans les tables appropriées
            importHealthData($db, $playerId, $joueur['pillar_1_health']);
            importPerformanceData($db, $playerId, $joueur['pillar_2_performance']);
            importSDOHData($db, $playerId, $joueur['pillar_3_sdoh']);
            importMarketValueData($db, $playerId, $joueur['pillar_4_market_value']);
            importAdherenceData($db, $playerId, $joueur['pillar_5_adherence_availability']);
            
            $successCount++;
            echo "✅ Joueur {$joueur['prenom']} {$joueur['nom']} importé avec succès (ID: {$playerId})\n";
        } else {
            $errorCount++;
            echo "❌ ERREUR : Impossible d'importer le joueur {$joueur['prenom']} {$joueur['nom']}\n";
        }
        
    } catch (Exception $e) {
        $errorCount++;
        echo "❌ ERREUR lors de l'import du joueur {$joueur['prenom']} {$joueur['nom']} : " . $e->getMessage() . "\n";
    }
}

echo "\n📊 RÉSUMÉ DE L'IMPORT\n";
echo "----------------------\n";
echo "✅ Importés avec succès : {$successCount} joueurs\n";
echo "❌ Erreurs : {$errorCount} joueurs\n";
echo "📈 Taux de succès : " . round(($successCount / count($dataset['joueurs'])) * 100, 1) . "%\n\n";

if ($successCount === count($dataset['joueurs'])) {
    echo "🎉 IMPORT TERMINÉ AVEC SUCCÈS !\n";
    echo "Tous les joueurs ont été importés dans la base de données.\n";
} else {
    echo "⚠️ IMPORT PARTIEL\n";
    echo "Certains joueurs n'ont pas pu être importés.\n";
}

// Fonctions d'import adaptées
function importPlayer($db, $joueur) {
    // Vérifier si le club existe, sinon le créer
    $clubId = getOrCreateClub($db, $joueur['club']);
    
    // Vérifier si l'association existe, sinon la créer
    $associationId = getOrCreateAssociation($db, $joueur['nationalite']);
    
    // Calculer le FIT Score global (GHS Overall Score)
    $fitScore = $joueur['fit_score_calcul']['fit_score_final'];
    
    // Créer le joueur avec la structure existante
    $stmt = $db->prepare("
        INSERT INTO players (
            first_name, last_name, name, age, nationality, position, 
            club_id, association_id, height, weight, preferred_foot, 
            date_of_birth, birth_date, ghs_overall_score, ghs_physical_score, 
            ghs_mental_score, ghs_civic_score, ghs_sleep_score, market_value, 
            value_eur, wage_eur, availability, form_percentage, fitness_score, 
            status, fifa_connect_id, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now')
        )
    ");
    
    $stmt->execute([
        $joueur['prenom'],
        $joueur['nom'],
        $joueur['prenom'] . ' ' . $joueur['nom'],
        $joueur['age'],
        $joueur['nationalite'],
        $joueur['poste'],
        $clubId,
        $associationId,
        $joueur['taille'],
        $joueur['poids'],
        $joueur['pied_fort'],
        $joueur['date_naissance'],
        $joueur['date_naissance'],
        $fitScore,
        $joueur['fit_score_calcul']['health_score'],
        $joueur['fit_score_calcul']['performance_score'],
        $joueur['fit_score_calcul']['sdoh_score'],
        $joueur['fit_score_calcul']['adherence_score'],
        $joueur['pillar_4_market_value']['valeur_marchande'],
        $joueur['pillar_4_market_value']['valeur_marchande'],
        $joueur['pillar_4_market_value']['salaire_mensuel'],
        $joueur['pillar_5_adherence_availability']['disponibilite_generale'] > 80 ? 'available' : 'limited',
        $joueur['pillar_5_adherence_availability']['taux_presence_entrainements'],
        $joueur['fit_score_calcul']['health_score'],
        'active',
        'TUN_' . str_pad($joueur['id'], 3, '0', STR_PAD_LEFT)
    ]);
    
    return $db->lastInsertId();
}

function getOrCreateClub($db, $clubName) {
    $stmt = $db->prepare("SELECT id FROM clubs WHERE name = ?");
    $stmt->execute([$clubName]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($club) {
        return $club['id'];
    }
    
    // Créer le club
    $stmt = $db->prepare("INSERT INTO clubs (name, country, created_at, updated_at) VALUES (?, 'Tunisie', datetime('now'), datetime('now'))");
    $stmt->execute([$clubName]);
    
    return $db->lastInsertId();
}

function getOrCreateAssociation($db, $nationality) {
    $stmt = $db->prepare("SELECT id FROM associations WHERE name = ?");
    $stmt->execute([$nationality]);
    $association = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($association) {
        return $association['id'];
    }
    
    // Créer l'association
    $stmt = $db->prepare("INSERT INTO associations (name, country, created_at, updated_at) VALUES (?, ?, datetime('now'), datetime('now'))");
    $stmt->execute([$nationality, $nationality]);
    
    return $db->lastInsertId();
}

function importHealthData($db, $playerId, $healthData) {
    // Importer dans la table health_scores
    $stmt = $db->prepare("
        INSERT INTO health_scores (player_id, score, category, details, created_at, updated_at) 
        VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        $playerId,
        $healthData['score_pcma'],
        $healthData['statut_pcma'],
        json_encode($healthData)
    ]);
    
    // Importer les blessures dans la table injuries
    foreach ($healthData['historique_blessures'] as $blessure) {
        $stmt = $db->prepare("
            INSERT INTO injuries (player_id, type, severity, start_date, end_date, duration_days, description, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
        ");
        $stmt->execute([
            $playerId,
            $blessure['type'],
            $blessure['gravite'],
            $blessure['date_debut'],
            $blessure['date_fin'],
            $blessure['duree_indisponibilite'],
            $blessure['localisation']
        ]);
    }
    
    // Mettre à jour le score de risque de blessure dans players
    $injuryRisk = count($healthData['historique_blessures']) > 2 ? 'high' : (count($healthData['historique_blessures']) > 1 ? 'medium' : 'low');
    $stmt = $db->prepare("
        UPDATE players SET 
        injury_risk_level = ?, 
        injury_risk_score = ?, 
        injury_risk_reason = ?, 
        injury_risk_last_assessed = datetime('now') 
        WHERE id = ?
    ");
    $stmt->execute([
        $injuryRisk,
        count($healthData['historique_blessures']) * 20,
        'Historique de blessures: ' . count($healthData['historique_blessures']) . ' blessure(s)',
        $playerId
    ]);
}

function importPerformanceData($db, $playerId, $performanceData) {
    // Importer dans la table player_performances
    $stmt = $db->prepare("
        INSERT INTO player_performances (player_id, season, matches_played, minutes_played, goals, assists, tackles, pass_accuracy, data, created_at, updated_at) 
        VALUES (?, '2023-2024', ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        $playerId,
        $performanceData['stats_saison_precedente']['matchs_joues'],
        $performanceData['stats_saison_precedente']['minutes_jouees'],
        $performanceData['stats_saison_precedente']['buts'],
        $performanceData['stats_saison_precedente']['passes_decisives'],
        $performanceData['stats_saison_precedente']['tacles_reussis'],
        $performanceData['stats_saison_precedente']['pourcentage_passes_reussies'],
        json_encode($performanceData)
    ]);
    
    // Mettre à jour le score de forme dans players
    $formPercentage = min(100, max(0, $performanceData['stats_saison_precedente']['pourcentage_passes_reussies']));
    $stmt = $db->prepare("UPDATE players SET form_percentage = ? WHERE id = ?");
    $stmt->execute([$formPercentage, $playerId]);
}

function importSDOHData($db, $playerId, $sdohData) {
    // Importer dans la table player_sdoh_data
    $stmt = $db->prepare("
        INSERT INTO player_sdoh_data (player_id, profil_narratif, scores_quantifies, facteurs_risque, facteurs_protecteurs, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        $playerId,
        $sdohData['profil_narratif'],
        json_encode($sdohData['scores_quantifies']),
        json_encode($sdohData['facteurs_risque'] ?? []),
        json_encode($sdohData['facteurs_protecteurs'] ?? [])
    ]);
}

function importMarketValueData($db, $playerId, $marketValueData) {
    // Mettre à jour les informations de contrat
    $stmt = $db->prepare("
        UPDATE players SET 
        contract_valid_until = ?, 
        release_clause_eur = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        date('Y-m-d', strtotime('+' . $marketValueData['duree_contrat_restante'] . ' years')),
        $marketValueData['valeur_marchande'] * 1.8,
        $playerId
    ]);
}

function importAdherenceData($db, $playerId, $adherenceData) {
    // Mettre à jour la disponibilité et le moral dans players
    $availability = $adherenceData['disponibilite_generale'] > 80 ? 'available' : 'limited';
    $morale = $adherenceData['taux_presence_entrainements'] > 90 ? 95 : ($adherenceData['taux_presence_entrainements'] > 80 ? 85 : 75);
    
    $stmt = $db->prepare("
        UPDATE players SET 
        availability = ?, 
        morale_percentage = ?, 
        last_availability_update = datetime('now') 
        WHERE id = ?
    ");
    $stmt->execute([$availability, $morale, $playerId]);
    
    // Importer dans la table training_sessions
    $stmt = $db->prepare("
        INSERT INTO training_sessions (player_id, session_date, attendance_percentage, adherence_score, notes, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        $playerId,
        date('Y-m-d'),
        $adherenceData['taux_presence_entrainements'],
        $adherenceData['score_adherence_protocole'],
        'Import automatique depuis le dataset'
    ]);
}
