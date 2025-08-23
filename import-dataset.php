<?php

/**
 * Script d'import du Dataset - 50 Joueurs Ligue Professionnelle 1 Tunisie 2024-2025
 * Importe les données dans la base de données de la plateforme FIT
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

// Vérifier que les tables existent
$tables = ['players', 'clubs', 'associations', 'health_data', 'performance_data', 'sdoh_data', 'market_value_data', 'adherence_data'];
$tablesExistent = true;

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "❌ ERREUR : Table '{$table}' n'existe pas\n";
        $tablesExistent = false;
    }
}

if (!$tablesExistent) {
    echo "❌ Certaines tables sont manquantes. Vérifiez la structure de la base de données.\n";
    exit(1);
}

echo "✅ Toutes les tables nécessaires existent\n\n";

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
            // Importer les données des 5 piliers
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

// Fonctions d'import
function importPlayer($joueur) {
    // Vérifier si le club existe, sinon le créer
    $clubId = getOrCreateClub($joueur['club']);
    
    // Vérifier si l'association existe, sinon la créer
    $associationId = getOrCreateAssociation($joueur['nationalite']);
    
    // Créer le joueur
    $playerId = DB::table('players')->insertGetId([
        'first_name' => $joueur['prenom'],
        'last_name' => $joueur['nom'],
        'age' => $joueur['age'],
        'nationality' => $joueur['nationalite'],
        'position' => $joueur['poste'],
        'club_id' => $clubId,
        'association_id' => $associationId,
        'height' => $joueur['taille'],
        'weight' => $joueur['poids'],
        'preferred_foot' => $joueur['pied_fort'],
        'date_of_birth' => $joueur['date_naissance'],
        'email' => $joueur['email'],
        'phone' => $joueur['telephone'],
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
    // Importer l'historique des blessures
    foreach ($healthData['historique_blessures'] as $blessure) {
        DB::table('health_data')->insert([
            'player_id' => $playerId,
            'type' => 'blessure',
            'description' => $blessure['type'] . ' - ' . $blessure['localisation'],
            'start_date' => $blessure['date_debut'],
            'end_date' => $blessure['date_fin'],
            'duration_days' => $blessure['duree_indisponibilite'],
            'severity' => $blessure['gravite'],
            'data' => json_encode($blessure),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    // Importer les données de laboratoire
    DB::table('health_data')->insert([
        'player_id' => $playerId,
        'type' => 'laboratoire',
        'description' => 'Données de laboratoire',
        'data' => json_encode($healthData['donnees_laboratoire']),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Importer les tests fonctionnels
    DB::table('health_data')->insert([
        'player_id' => $playerId,
        'type' => 'tests_fonctionnels',
        'description' => 'Tests fonctionnels',
        'data' => json_encode($healthData['tests_fonctionnels']),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Importer le statut PCMA
    DB::table('health_data')->insert([
        'player_id' => $playerId,
        'type' => 'pcma',
        'description' => 'Statut PCMA',
        'data' => json_encode([
            'statut' => $healthData['statut_pcma'],
            'score' => $healthData['score_pcma']
        ]),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function importPerformanceData($playerId, $performanceData) {
    // Importer les statistiques de la saison précédente
    DB::table('performance_data')->insert([
        'player_id' => $playerId,
        'type' => 'stats_saison',
        'description' => 'Statistiques saison précédente',
        'data' => json_encode($performanceData['stats_saison_precedente']),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Importer les données physiques
    DB::table('performance_data')->insert([
        'player_id' => $playerId,
        'type' => 'donnees_physiques',
        'description' => 'Données physiques',
        'data' => json_encode($performanceData['donnees_physiques']),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function importSDOHData($playerId, $sdohData) {
    DB::table('sdoh_data')->insert([
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
    DB::table('market_value_data')->insert([
        'player_id' => $playerId,
        'valeur_marchande' => $marketValueData['valeur_marchande'],
        'duree_contrat_restante' => $marketValueData['duree_contrat_restante'],
        'salaire_mensuel' => $marketValueData['salaire_mensuel'],
        'data' => json_encode($marketValueData),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

function importAdherenceData($playerId, $adherenceData) {
    DB::table('adherence_data')->insert([
        'player_id' => $playerId,
        'taux_presence_entrainements' => $adherenceData['taux_presence_entrainements'],
        'disponibilite_generale' => $adherenceData['disponibilite_generale'],
        'score_adherence_protocole' => $adherenceData['score_adherence_protocole'],
        'data' => json_encode($adherenceData),
        'created_at' => now(),
        'updated_at' => now()
    ]);
}







