<?php

/**
 * Script de test d'intégration du Dataset - 50 Joueurs Tunisie 2024-2025
 * Teste la compatibilité avec la plateforme FIT
 */

echo "🧪 TEST D'INTÉGRATION DU DATASET - 50 JOUEURS TUNISIE 2024-2025\n";
echo "================================================================\n\n";

// Charger le dataset
$dataset = json_decode(file_get_contents('dataset-50-joueurs-tunisie-2024-2025.json'), true);

if (!$dataset) {
    echo "❌ ERREUR : Impossible de charger le fichier JSON\n";
    exit(1);
}

echo "✅ Dataset chargé : " . count($dataset['joueurs']) . " joueurs\n\n";

// Test 1: Vérification de la structure des données
echo "🔍 TEST 1: VÉRIFICATION DE LA STRUCTURE\n";
echo "----------------------------------------\n";

$structureOK = true;
$requiredFields = [
    'id', 'nom', 'prenom', 'age', 'nationalite', 'poste', 'club',
    'pillar_1_health', 'pillar_2_performance', 'pillar_3_sdoh',
    'pillar_4_market_value', 'pillar_5_adherence_availability',
    'fit_score_calcul'
];

foreach ($dataset['joueurs'] as $index => $joueur) {
    foreach ($requiredFields as $field) {
        if (!isset($joueur[$field])) {
            echo "❌ Joueur " . ($index + 1) . " : Champ manquant '{$field}'\n";
            $structureOK = false;
        }
    }
}

if ($structureOK) {
    echo "✅ Structure des données valide\n";
} else {
    echo "❌ Structure des données invalide\n";
}

echo "\n";

// Test 2: Vérification des FIT Scores
echo "🎯 TEST 2: VÉRIFICATION DES FIT SCORES\n";
echo "---------------------------------------\n";

$fitScoresOK = true;
$fitScores = [];

foreach ($dataset['joueurs'] as $index => $joueur) {
    $fitScore = $joueur['fit_score_calcul']['fit_score_final'];
    $fitScores[] = $fitScore;
    
    // Vérifier que le score est dans une plage raisonnable
    if ($fitScore < 0 || $fitScore > 100) {
        echo "❌ Joueur " . ($index + 1) . " : FIT Score invalide ({$fitScore})\n";
        $fitScoresOK = false;
    }
    
    // Vérifier la cohérence avec la catégorie
    $categorie = $joueur['fit_score_calcul']['categorie'];
    $categorieOK = false;
    
    if ($fitScore >= 90 && $categorie === 'Excellent') $categorieOK = true;
    elseif ($fitScore >= 80 && $categorie === 'Très bon') $categorieOK = true;
    elseif ($fitScore >= 70 && $categorie === 'Bon') $categorieOK = true;
    elseif ($fitScore >= 60 && $categorie === 'Moyen') $categorieOK = true;
    elseif ($fitScore < 60 && $categorie === 'Faible') $categorieOK = true;
    
    if (!$categorieOK) {
        echo "❌ Joueur " . ($index + 1) . " : Catégorie incohérente (Score: {$fitScore}, Catégorie: {$categorie})\n";
        $fitScoresOK = false;
    }
}

if ($fitScoresOK) {
    echo "✅ FIT Scores valides et cohérents\n";
    echo "   Plage : " . min($fitScores) . " - " . max($fitScores) . "\n";
    echo "   Moyenne : " . round(array_sum($fitScores) / count($fitScores), 2) . "\n";
} else {
    echo "❌ FIT Scores invalides ou incohérents\n";
}

echo "\n";

// Test 3: Vérification des données de santé
echo "🏥 TEST 3: VÉRIFICATION DES DONNÉES DE SANTÉ\n";
echo "---------------------------------------------\n";

$healthOK = true;
$blessuresTotal = 0;
$pcmaOK = 0;

foreach ($dataset['joueurs'] as $index => $joueur) {
    $health = $joueur['pillar_1_health'];
    
    // Vérifier les blessures
    if (isset($health['historique_blessures'])) {
        $blessuresTotal += count($health['historique_blessures']);
        
        foreach ($health['historique_blessures'] as $blessure) {
            if (!isset($blessure['type']) || !isset($blessure['duree_indisponibilite'])) {
                echo "❌ Joueur " . ($index + 1) . " : Blessure mal formatée\n";
                $healthOK = false;
            }
        }
    }
    
    // Vérifier le PCMA
    if (isset($health['statut_pcma'])) {
        if ($health['statut_pcma'] === 'Clearance OK') {
            $pcmaOK++;
        }
    } else {
        echo "❌ Joueur " . ($index + 1) . " : Statut PCMA manquant\n";
        $healthOK = false;
    }
}

if ($healthOK) {
    echo "✅ Données de santé valides\n";
    echo "   Blessures totales : {$blessuresTotal}\n";
    echo "   PCMA OK : {$pcmaOK}/" . count($dataset['joueurs']) . "\n";
} else {
    echo "❌ Données de santé invalides\n";
}

echo "\n";

// Test 4: Vérification des performances
echo "⚽ TEST 4: VÉRIFICATION DES PERFORMANCES\n";
echo "-----------------------------------------\n";

$performanceOK = true;

foreach ($dataset['joueurs'] as $index => $joueur) {
    $performance = $joueur['pillar_2_performance'];
    
    if (!isset($performance['stats_saison_precedente']['minutes_jouees']) ||
        !isset($performance['donnees_physiques']['vitesse_maximale'])) {
        echo "❌ Joueur " . ($index + 1) . " : Données de performance manquantes\n";
        $performanceOK = false;
    }
}

if ($performanceOK) {
    echo "✅ Données de performance valides\n";
} else {
    echo "❌ Données de performance invalides\n";
}

echo "\n";

// Test 5: Vérification des valeurs marchandes
echo "💰 TEST 5: VÉRIFICATION DES VALEURS MARCHANDES\n";
echo "----------------------------------------------\n";

$marketValueOK = true;
$valeurs = [];

foreach ($dataset['joueurs'] as $index => $joueur) {
    $marketValue = $joueur['pillar_4_market_value'];
    
    if (!isset($marketValue['valeur_marchande']) || $marketValue['valeur_marchande'] <= 0) {
        echo "❌ Joueur " . ($index + 1) . " : Valeur marchande invalide\n";
        $marketValueOK = false;
    } else {
        $valeurs[] = $marketValue['valeur_marchande'];
    }
}

if ($marketValueOK) {
    echo "✅ Valeurs marchandes valides\n";
    echo "   Plage : " . number_format(min($valeurs), 0, ',', ' ') . " € - " . number_format(max($valeurs), 0, ',', ' ') . " €\n";
    echo "   Moyenne : " . number_format(array_sum($valeurs) / count($valeurs), 0, ',', ' ') . " €\n";
} else {
    echo "❌ Valeurs marchandes invalides\n";
}

echo "\n";

// Test 6: Vérification de l'adhérence
echo "✅ TEST 6: VÉRIFICATION DE L'ADHÉRENCE\n";
echo "--------------------------------------\n";

$adherenceOK = true;
$presenceTotal = 0;

foreach ($dataset['joueurs'] as $index => $joueur) {
    $adherence = $joueur['pillar_5_adherence_availability'];
    
    if (!isset($adherence['taux_presence_entrainements']) ||
        !isset($adherence['disponibilite_generale'])) {
        echo "❌ Joueur " . ($index + 1) . " : Données d'adhérence manquantes\n";
        $adherenceOK = false;
    } else {
        $presenceTotal += $adherence['taux_presence_entrainements'];
    }
}

if ($adherenceOK) {
    echo "✅ Données d'adhérence valides\n";
    echo "   Présence moyenne : " . round($presenceTotal / count($dataset['joueurs']), 1) . "%\n";
} else {
    echo "❌ Données d'adhérence invalides\n";
}

echo "\n";

// Test 7: Vérification de la cohérence globale
echo "🔍 TEST 7: VÉRIFICATION DE LA COHÉRENCE GLOBALE\n";
echo "-----------------------------------------------\n";

$coherenceOK = true;

foreach ($dataset['joueurs'] as $index => $joueur) {
    // Vérifier que l'âge est cohérent avec la date de naissance
    $ageCalcule = date('Y') - date('Y', strtotime($joueur['date_naissance']));
    if (abs($ageCalcule - $joueur['age']) > 1) {
        echo "❌ Joueur " . ($index + 1) . " : Âge incohérent (calculé: {$ageCalcule}, déclaré: {$joueur['age']})\n";
        $coherenceOK = false;
    }
    
    // Vérifier que le FIT Score est cohérent avec les sous-scores
    $fitScoreCalcule = round(($joueur['fit_score_calcul']['health_score'] + 
                              $joueur['fit_score_calcul']['performance_score'] + 
                              $joueur['fit_score_calcul']['sdoh_score'] + 
                              $joueur['fit_score_calcul']['market_value_score'] + 
                              $joueur['fit_score_calcul']['adherence_score']) / 5, 1);
    
    if (abs($fitScoreCalcule - $joueur['fit_score_calcul']['fit_score_final']) > 0.1) {
        echo "❌ Joueur " . ($index + 1) . " : FIT Score incohérent (calculé: {$fitScoreCalcule}, déclaré: {$joueur['fit_score_calcul']['fit_score_final']})\n";
        $coherenceOK = false;
    }
}

if ($coherenceOK) {
    echo "✅ Cohérence globale validée\n";
} else {
    echo "❌ Incohérences détectées\n";
}

echo "\n";

// Résumé final
echo "📊 RÉSUMÉ DES TESTS\n";
echo "===================\n";

$tests = [
    'Structure des données' => $structureOK,
    'FIT Scores' => $fitScoresOK,
    'Données de santé' => $healthOK,
    'Performances' => $performanceOK,
    'Valeurs marchandes' => $marketValueOK,
    'Adhérence' => $adherenceOK,
    'Cohérence globale' => $coherenceOK
];

$testsReussis = 0;
foreach ($tests as $test => $resultat) {
    $status = $resultat ? "✅" : "❌";
    echo "{$status} {$test}\n";
    if ($resultat) $testsReussis++;
}

echo "\n";

if ($testsReussis === count($tests)) {
    echo "🎉 TOUS LES TESTS SONT PASSÉS !\n";
    echo "Le dataset est prêt pour l'intégration avec la plateforme FIT.\n";
} else {
    echo "⚠️ {$testsReussis}/" . count($tests) . " tests sont passés.\n";
    echo "Vérifiez les erreurs avant l'intégration.\n";
}

echo "\n";
echo "📈 STATISTIQUES FINALES\n";
echo "------------------------\n";
echo "Nombre total de joueurs : " . count($dataset['joueurs']) . "\n";
echo "FIT Score moyen : " . round(array_sum($fitScores) / count($fitScores), 2) . "\n";
echo "FIT Score min : " . min($fitScores) . "\n";
echo "FIT Score max : " . max($fitScores) . "\n";
echo "Blessures totales : {$blessuresTotal}\n";
echo "PCMA OK : {$pcmaOK}/" . count($dataset['joueurs']) . " (" . round(($pcmaOK / count($dataset['joueurs'])) * 100, 1) . "%)\n";
echo "Valeur marchande moyenne : " . number_format(array_sum($valeurs) / count($valeurs), 0, ',', ' ') . " €\n";
echo "Présence moyenne : " . round($presenceTotal / count($dataset['joueurs']), 1) . "%\n";




