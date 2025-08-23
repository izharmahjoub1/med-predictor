<?php

/**
 * Script d'import du Dataset - 50 Joueurs Tunisie 2024-2025
 * Adapté à la structure existante de la base de données FIT
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

echo "🚀 IMPORT DU DATASET - 50 JOUEURS TUNISIE 2024-2025\n";
echo "==================================================\n\n";

// Charger le dataset
$dataset = json_decode(file_get_contents('dataset-50-joueurs-tunisie-2024-2025.json'), true);

if (!$dataset) {
    echo "❌ ERREUR : Impossible de charger le fichier JSON\n";
    exit(1);
}

echo "✅ Fichier JSON chargé avec succès\n";
echo "👥 Nombre de joueurs à importer : " . count($dataset['joueurs']) . "\n\n";

// Configuration de la base de données
try {
    // Vérifier la connexion à la base de données
    DB::connection()->getPdo();
    echo "✅ Connexion à la base de données établie\n";
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
        $playerId = importPlayer($joueur);
        
        if ($playerId) {
            // Importer les données des 5 piliers dans les tables appropriées
            importHealthData($playerId, $joueur['pillar_1_health']);
            importPerformanceData($playerId, $joueur['pillar_2_performance']);
            importSDOHData($playerId, $joueur['pillar_3_sdoh']);
            importMarketValueData($playerId, $joueur['pillar_4_market_value']);
            importAdherenceData($playerId, $joueur['pillar_5_adherence_availability']);
            
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
function importPlayer($joueur) {
    // Vérifier si le club existe, sinon le créer
    $clubId = getOrCreateClub($joueur['club']);
    
    // Vérifier si l'association existe, sinon la créer
    $associationId = getOrCreateAssociation($joueur['nationalite']);
    
    // Calculer le FIT Score global (GHS Overall Score)
    $fitScore = $joueur['fit_score_calcul']['fit_score_final'];
    
    // Créer le joueur avec la structure existante
    $playerId = DB::table('players')->insertGetId([
        'first_name' => $joueur['prenom'],
        'last_name' => $joueur['nom'],
        'name' => $joueur['prenom'] . ' ' . $joueur['nom'],
        'age' => $joueur['age'],
        'nationality' => $joueur['nationalite'],
        'position' => $joueur['poste'],
        'club_id' => $clubId,
        'association_id' => $associationId,
        'height' => $joueur['taille'],
        'weight' => $joueur['poids'],
        'preferred_foot' => $joueur['pied_fort'],
        'date_of_birth' => $joueur['date_naissance'],
        'birth_date' => $joueur['date_naissance'],
        
        // FIT Score (GHS)
        'ghs_overall_score' => $fitScore,
        'ghs_physical_score' => $joueur['fit_score_calcul']['health_score'],
        'ghs_mental_score' => $joueur['fit_score_calcul']['performance_score'],
        'ghs_civic_score' => $joueur['fit_score_calcul']['sdoh_score'],
        'ghs_sleep_score' => $joueur['fit_score_calcul']['adherence_score'],
        
        // Valeurs marchandes
        'market_value' => $joueur['pillar_4_market_value']['valeur_marchande'],
        'value_eur' => $joueur['pillar_4_market_value']['valeur_marchande'],
        'wage_eur' => $joueur['pillar_4_market_value']['salaire_mensuel'],
        
        // Disponibilité et forme
        'availability' => $joueur['pillar_5_adherence_availability']['disponibilite_generale'] > 80 ? 'available' : 'limited',
        'form_percentage' => $joueur['pillar_5_adherence_availability']['taux_presence_entrainements'],
        'fitness_score' => $joueur['fit_score_calcul']['health_score'],
        
        // Statut et métadonnées
        'status' => 'active',
        'fifa_connect_id' => 'TUN_' . str_pad($joueur['id'], 3, '0', STR_PAD_LEFT),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return $playerId;
}

function getOrCreateClub($clubName) {
    $club = DB::table('clubs')->where('name', $clubName)->first();
    
    if ($club) {
        return $club->id;
    }
    
    // Créer le club
    return DB::table('clubs')->insertGetId([
        'name' => $clubName,
        'country' => 'Tunisie',
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function getOrCreateAssociation($nationality) {
    $association = DB::table('associations')->where('name', $nationality)->first();
    
    if ($association) {
        return $association->id;
    }
    
    // Créer l'association
    return DB::table('associations')->insertGetId([
        'name' => $nationality,
        'country' => $nationality,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function importHealthData($playerId, $healthData) {
    // Importer dans la table health_scores
    DB::table('health_scores')->insert([
        'player_id' => $playerId,
        'score' => $healthData['score_pcma'],
        'category' => $healthData['statut_pcma'],
        'details' => json_encode($healthData),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Importer les blessures dans la table injuries
    foreach ($healthData['historique_blessures'] as $blessure) {
        DB::table('injuries')->insert([
            'player_id' => $playerId,
            'type' => $blessure['type'],
            'severity' => $blessure['gravite'],
            'start_date' => $blessure['date_debut'],
            'end_date' => $blessure['date_fin'],
            'duration_days' => $blessure['duree_indisponibilite'],
            'description' => $blessure['localisation'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    // Mettre à jour le score de risque de blessure dans players
    $injuryRisk = count($healthData['historique_blessures']) > 2 ? 'high' : (count($healthData['historique_blessures']) > 1 ? 'medium' : 'low');
    DB::table('players')->where('id', $playerId)->update([
        'injury_risk_level' => $injuryRisk,
        'injury_risk_score' => count($healthData['historique_blessures']) * 20,
        'injury_risk_reason' => 'Historique de blessures: ' . count($healthData['historique_blessures']) . ' blessure(s)',
        'injury_risk_last_assessed' => now()
    ]);
}

function importPerformanceData($playerId, $performanceData) {
    // Importer dans la table player_performances
    DB::table('player_performances')->insert([
        'player_id' => $playerId,
        'season' => '2023-2024',
        'matches_played' => $performanceData['stats_saison_precedente']['matchs_joues'],
        'minutes_played' => $performanceData['stats_saison_precedente']['minutes_jouees'],
        'goals' => $performanceData['stats_saison_precedente']['buts'],
        'assists' => $performanceData['stats_saison_precedente']['passes_decisives'],
        'tackles' => $performanceData['stats_saison_precedente']['tacles_reussis'],
        'pass_accuracy' => $performanceData['stats_saison_precedente']['pourcentage_passes_reussies'],
        'data' => json_encode($performanceData),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Mettre à jour le score de forme dans players
    $formPercentage = min(100, max(0, $performanceData['stats_saison_precedente']['pourcentage_passes_reussies']));
    DB::table('players')->where('id', $playerId)->update([
        'form_percentage' => $formPercentage
    ]);
}

function importSDOHData($playerId, $sdohData) {
    // Importer dans la table player_sdoh_data
    DB::table('player_sdoh_data')->insert([
        'player_id' => $playerId,
        'profil_narratif' => $sdohData['profil_narratif'],
        'scores_quantifies' => json_encode($sdohData['scores_quantifies']),
        'facteurs_risque' => json_encode($sdohData['facteurs_risque'] ?? []),
        'facteurs_protecteurs' => json_encode($sdohData['facteurs_protecteurs'] ?? []),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function importMarketValueData($playerId, $marketValueData) {
    // Les données sont déjà importées dans la table players
    // Mettre à jour les informations de contrat
    DB::table('players')->where('id', $playerId)->update([
        'contract_valid_until' => date('Y-m-d', strtotime('+' . $marketValueData['duree_contrat_restante'] . ' years')),
        'release_clause_eur' => $marketValueData['valeur_marchande'] * 1.8
    ]);
}

function importAdherenceData($playerId, $adherenceData) {
    // Mettre à jour la disponibilité et le moral dans players
    $availability = $adherenceData['disponibilite_generale'] > 80 ? 'available' : 'limited';
    $morale = $adherenceData['taux_presence_entrainements'] > 90 ? 95 : ($adherenceData['taux_presence_entrainements'] > 80 ? 85 : 75);
    
    DB::table('players')->where('id', $playerId)->update([
        'availability' => $availability,
        'morale_percentage' => $morale,
        'last_availability_update' => now()
    ]);
    
    // Importer dans la table training_sessions
    DB::table('training_sessions')->insert([
        'player_id' => $playerId,
        'session_date' => now()->format('Y-m-d'),
        'attendance_percentage' => $adherenceData['taux_presence_entrainements'],
        'adherence_score' => $adherenceData['score_adherence_protocole'],
        'notes' => 'Import automatique depuis le dataset',
        'created_at' => now(),
        'updated_at' => now()
    ]);
}







