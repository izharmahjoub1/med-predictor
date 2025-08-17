<?php

/**
 * Script de validation du Dataset - 50 Joueurs Ligue Professionnelle 1 Tunisie 2024-2025
 * Vérifie la cohérence et la qualité des données générées
 */

echo "🔍 VALIDATION DU DATASET - 50 JOUEURS TUNISIE 2024-2025\n";
echo "=====================================================\n\n";

// Charger le dataset
$dataset = json_decode(file_get_contents('dataset-50-joueurs-tunisie-2024-2025.json'), true);

if (!$dataset) {
    echo "❌ ERREUR : Impossible de charger le fichier JSON\n";
    exit(1);
}

echo "✅ Fichier JSON chargé avec succès\n\n";

// Validation des métadonnées
echo "📊 VALIDATION DES MÉTADONNÉES\n";
echo "-------------------------------\n";
echo "Championnat : " . $dataset['metadata']['championnat'] . "\n";
echo "Saison : " . $dataset['metadata']['saison'] . "\n";
echo "Nombre de joueurs : " . $dataset['metadata']['nombre_joueurs'] . "\n";
echo "Date de création : " . $dataset['metadata']['date_creation'] . "\n\n";

// Validation du nombre de joueurs
$nbJoueurs = count($dataset['joueurs']);
if ($nbJoueurs === 50) {
    echo "✅ Nombre de joueurs correct : {$nbJoueurs}/50\n\n";
} else {
    echo "❌ ERREUR : Nombre de joueurs incorrect : {$nbJoueurs}/50\n\n";
}

// Statistiques des nationalités
echo "🌍 RÉPARTITION DES NATIONALITÉS\n";
echo "--------------------------------\n";
$nationalites = [];
foreach ($dataset['joueurs'] as $joueur) {
    $nat = $joueur['nationalite'];
    $nationalites[$nat] = ($nationalites[$nat] ?? 0) + 1;
}

foreach ($nationalites as $nat => $count) {
    $pourcentage = round(($count / $nbJoueurs) * 100, 1);
    echo "{$nat} : {$count} joueurs ({$pourcentage}%)\n";
}
echo "\n";

// Statistiques des postes
echo "⚽ RÉPARTITION DES POSTES\n";
echo "-------------------------\n";
$postes = [];
foreach ($dataset['joueurs'] as $joueur) {
    $poste = $joueur['poste'];
    $postes[$poste] = ($postes[$poste] ?? 0) + 1;
}

foreach ($postes as $poste => $count) {
    $pourcentage = round(($count / $nbJoueurs) * 100, 1);
    echo "{$poste} : {$count} joueurs ({$pourcentage}%)\n";
}
echo "\n";

// Statistiques des clubs
echo "🏟️ RÉPARTITION DES CLUBS\n";
echo "-------------------------\n";
$clubs = [];
foreach ($dataset['joueurs'] as $joueur) {
    $club = $joueur['club'];
    $clubs[$club] = ($clubs[$club] ?? 0) + 1;
}

foreach ($clubs as $club => $count) {
    $pourcentage = round(($count / $nbJoueurs) * 100, 1);
    echo "{$club} : {$count} joueurs ({$pourcentage}%)\n";
}
echo "\n";

// Statistiques des âges
echo "📅 RÉPARTITION DES ÂGES\n";
echo "------------------------\n";
$ages = [];
foreach ($dataset['joueurs'] as $joueur) {
    $age = $joueur['age'];
    $ages[$age] = ($ages[$age] ?? 0) + 1;
}

ksort($ages);
foreach ($ages as $age => $count) {
    $pourcentage = round(($count / $nbJoueurs) * 100, 1);
    echo "{$age} ans : {$count} joueurs ({$pourcentage}%)\n";
}
echo "\n";

// Validation des FIT Scores
echo "🎯 VALIDATION DES FIT SCORES\n";
echo "-----------------------------\n";
$fitScores = [];
$categories = [];
$healthScores = [];
$performanceScores = [];
$sdohScores = [];
$marketValueScores = [];
$adherenceScores = [];

foreach ($dataset['joueurs'] as $joueur) {
    $fitScore = $joueur['fit_score_calcul']['fit_score_final'];
    $categorie = $joueur['fit_score_calcul']['categorie'];
    
    $fitScores[] = $fitScore;
    $categories[$categorie] = ($categories[$categorie] ?? 0) + 1;
    
    $healthScores[] = $joueur['fit_score_calcul']['health_score'];
    $performanceScores[] = $joueur['fit_score_calcul']['performance_score'];
    $sdohScores[] = $joueur['fit_score_calcul']['sdoh_score'];
    $marketValueScores[] = $joueur['fit_score_calcul']['market_value_score'];
    $adherenceScores[] = $joueur['fit_score_calcul']['adherence_score'];
}

echo "FIT Score moyen : " . round(array_sum($fitScores) / count($fitScores), 2) . "\n";
echo "FIT Score min : " . min($fitScores) . "\n";
echo "FIT Score max : " . max($fitScores) . "\n\n";

echo "Répartition des catégories :\n";
foreach ($categories as $categorie => $count) {
    $pourcentage = round(($count / $nbJoueurs) * 100, 1);
    echo "  {$categorie} : {$count} joueurs ({$pourcentage}%)\n";
}
echo "\n";

echo "Scores moyens par pilier :\n";
echo "  Health : " . round(array_sum($healthScores) / count($healthScores), 2) . "\n";
echo "  Performance : " . round(array_sum($performanceScores) / count($performanceScores), 2) . "\n";
echo "  SDOH : " . round(array_sum($sdohScores) / count($sdohScores), 2) . "\n";
echo "  Market Value : " . round(array_sum($marketValueScores) / count($marketValueScores), 2) . "\n";
echo "  Adherence : " . round(array_sum($adherenceScores) / count($adherenceScores), 2) . "\n\n";

// Validation des données de santé
echo "🏥 VALIDATION DES DONNÉES DE SANTÉ\n";
echo "----------------------------------\n";
$blessuresTotal = 0;
$pcmaOK = 0;
$pcmaEnAttente = 0;

foreach ($dataset['joueurs'] as $joueur) {
    $blessuresTotal += count($joueur['pillar_1_health']['historique_blessures']);
    
    if ($joueur['pillar_1_health']['statut_pcma'] === 'Clearance OK') {
        $pcmaOK++;
    } else {
        $pcmaEnAttente++;
    }
}

echo "Nombre total de blessures : {$blessuresTotal}\n";
echo "Moyenne de blessures par joueur : " . round($blessuresTotal / $nbJoueurs, 2) . "\n";
echo "Joueurs avec PCMA OK : {$pcmaOK} (" . round(($pcmaOK / $nbJoueurs) * 100, 1) . "%)\n";
echo "Joueurs avec PCMA en attente : {$pcmaEnAttente} (" . round(($pcmaEnAttente / $nbJoueurs) * 100, 1) . "%)\n\n";

// Validation des valeurs marchandes
echo "💰 VALIDATION DES VALEURS MARCHANDES\n";
echo "------------------------------------\n";
$valeurs = [];
foreach ($dataset['joueurs'] as $joueur) {
    $valeurs[] = $joueur['pillar_4_market_value']['valeur_marchande'];
}

echo "Valeur marchande moyenne : " . number_format(array_sum($valeurs) / count($valeurs), 0, ',', ' ') . " €\n";
echo "Valeur marchande min : " . number_format(min($valeurs), 0, ',', ' ') . " €\n";
echo "Valeur marchande max : " . number_format(max($valeurs), 0, ',', ' ') . " €\n\n";

// Validation des données d'adhérence
echo "✅ VALIDATION DES DONNÉES D'ADHÉRENCE\n";
echo "-------------------------------------\n";
$presenceEntrainements = [];
$disponibiliteGenerale = [];

foreach ($dataset['joueurs'] as $joueur) {
    $presenceEntrainements[] = $joueur['pillar_5_adherence_availability']['taux_presence_entrainements'];
    $disponibiliteGenerale[] = $joueur['pillar_5_adherence_availability']['disponibilite_generale'];
}

echo "Taux de présence aux entraînements moyen : " . round(array_sum($presenceEntrainements) / count($presenceEntrainements), 1) . "%\n";
echo "Disponibilité générale moyenne : " . round(array_sum($disponibiliteGenerale) / count($disponibiliteGenerale), 1) . "%\n\n";

// Vérification de la cohérence des données
echo "🔍 VÉRIFICATION DE LA COHÉRENCE\n";
echo "--------------------------------\n";
$erreurs = 0;

foreach ($dataset['joueurs'] as $index => $joueur) {
    $id = $index + 1;
    
    // Vérifier que l'ID correspond
    if ($joueur['id'] !== $id) {
        echo "❌ ERREUR Joueur {$id} : ID incorrect ({$joueur['id']})\n";
        $erreurs++;
    }
    
    // Vérifier que l'âge est cohérent avec la date de naissance
    $ageCalcule = date('Y') - date('Y', strtotime($joueur['date_naissance']));
    if (abs($ageCalcule - $joueur['age']) > 1) {
        echo "❌ ERREUR Joueur {$id} : Âge incohérent (calculé: {$ageCalcule}, déclaré: {$joueur['age']})\n";
        $erreurs++;
    }
    
    // Vérifier que le FIT Score est cohérent
    $fitScoreCalcule = round(($joueur['fit_score_calcul']['health_score'] + 
                              $joueur['fit_score_calcul']['performance_score'] + 
                              $joueur['fit_score_calcul']['sdoh_score'] + 
                              $joueur['fit_score_calcul']['market_value_score'] + 
                              $joueur['fit_score_calcul']['adherence_score']) / 5, 1);
    
    if (abs($fitScoreCalcule - $joueur['fit_score_calcul']['fit_score_final']) > 0.1) {
        echo "❌ ERREUR Joueur {$id} : FIT Score incohérent (calculé: {$fitScoreCalcule}, déclaré: {$joueur['fit_score_calcul']['fit_score_final']})\n";
        $erreurs++;
    }
}

if ($erreurs === 0) {
    echo "✅ Aucune erreur de cohérence détectée\n";
} else {
    echo "❌ {$erreurs} erreur(s) de cohérence détectée(s)\n";
}

echo "\n🎉 VALIDATION TERMINÉE !\n";
echo "Le dataset est prêt à être utilisé pour le calcul du FIT Score.\n";




