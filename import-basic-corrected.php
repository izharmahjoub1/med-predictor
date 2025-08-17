<?php

/**
 * Script d'import corrigé du Dataset - 50 Joueurs Tunisie 2024-2025
 * Association FTF pour tous, nationalités individuelles conservées
 */

echo "🚀 IMPORT CORRIGÉ DU DATASET - 50 JOUEURS TUNISIE 2024-2025\n";
echo "============================================================\n\n";

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
echo "📥 DÉBUT DE L'IMPORT CORRIGÉ...\n";
echo "--------------------------------\n";

$successCount = 0;
$errorCount = 0;

foreach ($dataset['joueurs'] as $joueur) {
    try {
        // Importer le joueur principal avec toutes les données FIT Score
        $playerId = importPlayer($db, $joueur);
        
        if ($playerId) {
            $successCount++;
            echo "✅ Joueur {$joueur['prenom']} {$joueur['nom']} importé avec succès (ID: {$playerId})\n";
            echo "   Nationalité: {$joueur['nationalite']}, Association: FTF\n";
        } else {
            $errorCount++;
            echo "❌ ERREUR : Impossible d'importer le joueur {$joueur['prenom']} {$joueur['nom']}\n";
        }
        
    } catch (Exception $e) {
        $errorCount++;
        echo "❌ ERREUR lors de l'import du joueur {$joueur['prenom']} {$joueur['nom']} : " . $e->getMessage() . "\n";
    }
}

echo "\n📊 RÉSUMÉ DE L'IMPORT CORRIGÉ\n";
echo "-------------------------------\n";
echo "✅ Importés avec succès : {$successCount} joueurs\n";
echo "❌ Erreurs : {$errorCount} joueurs\n";
echo "📈 Taux de succès : " . round(($successCount / count($dataset['joueurs'])) * 100, 1) . "%\n\n";

if ($successCount === count($dataset['joueurs'])) {
    echo "🎉 IMPORT CORRIGÉ TERMINÉ AVEC SUCCÈS !\n";
    echo "Tous les joueurs ont été importés avec l'association FTF.\n";
    echo "Chaque joueur conserve sa nationalité individuelle.\n";
    echo "Vous pouvez maintenant accéder à http://localhost:8000/players pour voir les données.\n";
} else {
    echo "⚠️ IMPORT PARTIEL\n";
    echo "Certains joueurs n'ont pas pu être importés.\n";
}

// Fonction d'import corrigée
function importPlayer($db, $joueur) {
    // Vérifier si le club existe, sinon le créer
    $clubId = getOrCreateClub($db, $joueur['club']);
    
    // Créer ou récupérer l'association FTF (Fédération Tunisienne de Football)
    $ftfId = getOrCreateFTF($db);
    
    // Calculer le FIT Score global (GHS Overall Score)
    $fitScore = $joueur['fit_score_calcul']['fit_score_final'];
    
    // Créer le joueur avec la structure existante et toutes les données FIT Score
    $stmt = $db->prepare("
        INSERT INTO players (
            first_name, last_name, name, age, nationality, position, 
            club_id, association_id, height, weight, preferred_foot, 
            date_of_birth, birth_date, ghs_overall_score, ghs_physical_score, 
            ghs_mental_score, ghs_civic_score, ghs_sleep_score, market_value, 
            value_eur, wage_eur, availability, form_percentage, fitness_score, 
            status, fifa_connect_id, created_at, updated_at,
            injury_risk_level, injury_risk_score, injury_risk_reason,
            morale_percentage, contract_valid_until, release_clause_eur
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'),
            ?, ?, ?, ?, ?, ?
        )
    ");
    
    // Calculer le niveau de risque de blessure
    $injuryRisk = count($joueur['pillar_1_health']['historique_blessures']) > 2 ? 'high' : (count($joueur['pillar_1_health']['historique_blessures']) > 1 ? 'medium' : 'low');
    $injuryScore = count($joueur['pillar_1_health']['historique_blessures']) * 20;
    $injuryReason = 'Historique: ' . count($joueur['pillar_1_health']['historique_blessures']) . ' blessure(s)';
    
    // Calculer la disponibilité et le moral
    $availability = $joueur['pillar_5_adherence_availability']['disponibilite_generale'] > 80 ? 'available' : 'limited';
    $morale = $joueur['pillar_5_adherence_availability']['taux_presence_entrainements'] > 90 ? 95 : ($joueur['pillar_5_adherence_availability']['taux_presence_entrainements'] > 80 ? 85 : 75);
    
    // Calculer la date de fin de contrat
    $contractEnd = date('Y-m-d', strtotime('+' . $joueur['pillar_4_market_value']['duree_contrat_restante'] . ' years'));
    $releaseClause = $joueur['pillar_4_market_value']['valeur_marchande'] * 1.8;
    
    $stmt->execute([
        $joueur['prenom'],
        $joueur['nom'],
        $joueur['prenom'] . ' ' . $joueur['nom'],
        $joueur['age'],
        $joueur['nationalite'],  // Nationalité individuelle conservée
        $joueur['poste'],
        $clubId,
        $ftfId,                  // Association FTF pour tous
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
        $availability,
        $joueur['pillar_5_adherence_availability']['taux_presence_entrainements'],
        $joueur['fit_score_calcul']['health_score'],
        'active',
        'TUN_' . str_pad($joueur['id'], 3, '0', STR_PAD_LEFT),
        $injuryRisk,
        $injuryScore,
        $injuryReason,
        $morale,
        $contractEnd,
        $releaseClause
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

function getOrCreateFTF($db) {
    // Vérifier si FTF existe déjà
    $stmt = $db->prepare("SELECT id FROM associations WHERE name = 'FTF' OR name LIKE '%Fédération Tunisienne de Football%'");
    $stmt->execute();
    $ftf = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ftf) {
        return $ftf['id'];
    }
    
    // Créer l'association FTF
    $stmt = $db->prepare("INSERT INTO associations (name, country, created_at, updated_at) VALUES (?, 'Tunisie', datetime('now'), datetime('now'))");
            $stmt->execute(['FTF']);
    
    return $db->lastInsertId();
}
