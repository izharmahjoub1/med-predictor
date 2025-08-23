<?php

// Générateur de Dataset - 50 Joueurs Ligue Professionnelle 1 Tunisie 2024-2025
// FIT Score - 5 Piliers : Health, Performance, SDOH, Market Value, Adherence

$clubs = [
    'Espérance de Tunis',
    'Club Africain', 
    'Étoile du Sahel',
    'CS Sfaxien',
    'US Monastir',
    'CA Bizertin',
    'Stade Tunisien',
    'AS Gabès',
    'JS Kairouan',
    'Olympique Béja'
];

$postes = [
    'Gardien',
    'Défenseur central',
    'Latéral droit',
    'Latéral gauche',
    'Milieu défensif',
    'Milieu central',
    'Milieu offensif',
    'Ailier droit',
    'Ailier gauche',
    'Attaquant'
];

$nationalites = [
    'Tunisie' => 0.5,      // 50%
    'Algérie' => 0.15,     // 15%
    'Maroc' => 0.1,        // 10%
    'Mali' => 0.08,        // 8%
    'Sénégal' => 0.06,     // 6%
    'Côte d\'Ivoire' => 0.05, // 5%
    'Nigeria' => 0.03,     // 3%
    'Cameroun' => 0.02,    // 2%
    'Brésil' => 0.01       // 1%
];

$noms = [
    'Tunisie' => ['Ben Romdhane', 'Bouguerra', 'Trabelsi', 'Jebali', 'Hamdi', 'Mansouri', 'Ben Amor', 'Ghannouchi'],
    'Algérie' => ['Bentoumi', 'Zerrouki', 'Boudaoud', 'Benrahma', 'Mahrez'],
    'Maroc' => ['Bennani', 'El Ahmadi', 'Amrabat', 'Ziyech', 'Hakimi'],
    'Mali' => ['Diallo', 'Keita', 'Traoré', 'Coulibaly', 'Koné'],
    'Sénégal' => ['Ndiaye', 'Diop', 'Gueye', 'Mane', 'Sarr'],
    'Côte d\'Ivoire' => ['Kouassi', 'Bamba', 'Zaha', 'Yaya', 'Kalou'],
    'Nigeria' => ['Osimhen', 'Chukwueze', 'Iwobi', 'Ndidi', 'Iheanacho'],
    'Cameroun' => ['Aboubakar', 'Anguissa', 'Choupo-Moting', 'Ondoua'],
    'Brésil' => ['Silva', 'Santos', 'Oliveira', 'Rodrigues', 'Pereira']
];

$prenoms = [
    'Tunisie' => ['Hamza', 'Youssef', 'Ahmed', 'Mohamed', 'Ali', 'Karim', 'Samir', 'Tarek'],
    'Algérie' => ['Riyad', 'Ismael', 'Said', 'Adel', 'Nabil'],
    'Maroc' => ['Hakim', 'Younes', 'Sofyan', 'Achraf', 'Noussair'],
    'Mali' => ['Mamadou', 'Seydou', 'Moussa', 'Bakary', 'Samba'],
    'Sénégal' => ['Sadio', 'Idrissa', 'Pape', 'Kalidou', 'Moussa'],
    'Côte d\'Ivoire' => ['Wilfried', 'Yaya', 'Gervinho', 'Salomon', 'Didier'],
    'Nigeria' => ['Victor', 'Samuel', 'Alex', 'Wilfred', 'Kelechi'],
    'Cameroun' => ['Vincent', 'André', 'Eric', 'Jean-Charles', 'Georges'],
    'Brésil' => ['Gabriel', 'Lucas', 'Pedro', 'Rafael', 'Bruno']
];

function getRandomNationalite() {
    global $nationalites;
    $rand = mt_rand() / mt_getrandmax();
    $cumulative = 0;
    
    foreach ($nationalites as $nationalite => $probability) {
        $cumulative += $probability;
        if ($rand <= $cumulative) {
            return $nationalite;
        }
    }
    
    return 'Tunisie';
}

function generateJoueur($id) {
    global $clubs, $postes, $noms, $prenoms;
    
    $nationalite = getRandomNationalite();
    $poste = $postes[array_rand($postes)];
    $age = rand(18, 32);
    $club = $clubs[array_rand($clubs)];
    
    $nom = $noms[$nationalite][array_rand($noms[$nationalite])];
    $prenom = $prenoms[$nationalite][array_rand($prenoms[$nationalite])];
    
    return [
        'id' => $id,
        'nom' => $nom,
        'prenom' => $prenom,
        'age' => $age,
        'nationalite' => $nationalite,
        'poste' => $poste,
        'club' => $club,
        'taille' => rand(170, 195),
        'poids' => rand(65, 85),
        'pied_fort' => rand(0, 1) ? 'Droit' : 'Gauche',
        'date_naissance' => date('Y-m-d', strtotime("-{$age} years")),
        'email' => strtolower($prenom) . '.' . strtolower($nom) . '@' . strtolower(str_replace(' ', '', $club)) . '.tn',
        'telephone' => '+216 22 ' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
        
        'pillar_1_health' => generateHealthData($age, $poste),
        'pillar_2_performance' => generatePerformanceData($age, $poste),
        'pillar_3_sdoh' => generateSDOHData($nationalite, $age),
        'pillar_4_market_value' => generateMarketValueData($age, $poste, $club),
        'pillar_5_adherence_availability' => generateAdherenceData($age),
        
        'fit_score_calcul' => calculateFITScore($id)
    ];
}

function generateHealthData($age, $poste) {
    $hasInjuries = rand(1, 100) <= 60;
    $blessures = [];
    
    if ($hasInjuries) {
        $nbBlessures = rand(1, 3);
        for ($i = 0; $i < $nbBlessures; $i++) {
            $blessures[] = generateBlessure();
        }
    }
    
    return [
        'historique_blessures' => $blessures,
        'donnees_laboratoire' => [
            'vitamine_d' => rand(20, 45),
            'ck_creatine_kinase' => rand(80, 250),
            'hs_crp' => rand(0.5, 5.0) / 10,
            'hemoglobine' => rand(135, 175) / 10,
            'ferritine' => rand(60, 120)
        ],
        'tests_fonctionnels' => [
            'y_balance_test' => rand(70, 95),
            'test_force_isocinetique' => rand(70, 95),
            'test_agilite_t' => rand(75, 120) / 10,
            'test_saut_vertical' => rand(50, 80)
        ],
        'statut_pcma' => rand(1, 100) <= 90 ? 'Clearance OK' : 'En attente',
        'score_pcma' => rand(70, 98)
    ];
}

function generateBlessure() {
    $types = ['Blessure musculaire', 'Blessure ligamentaire', 'Contusion', 'Entorse'];
    $type = $types[array_rand($types)];
    
    $dateDebut = date('Y-m-d', strtotime('-' . rand(30, 365) . ' days'));
    $duree = rand(7, 180);
    $dateFin = date('Y-m-d', strtotime($dateDebut . ' +' . $duree . ' days'));
    
    return [
        'type' => $type,
        'localisation' => 'Zone affectée',
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin,
        'duree_indisponibilite' => $duree,
        'gravite' => $duree > 90 ? 'Grave' : ($duree > 30 ? 'Modérée' : 'Légère')
    ];
}

function generatePerformanceData($age, $poste) {
    $minutesBase = $age < 25 ? rand(2000, 2800) : rand(1800, 2500);
    $matchsJoues = round($minutesBase / 90);
    
    return [
        'stats_saison_precedente' => [
            'minutes_jouees' => $minutesBase,
            'matchs_joues' => $matchsJoues,
            'buts' => rand(0, 20),
            'passes_decisives' => rand(0, 15),
            'tacles_reussis' => rand(0, 120),
            'pourcentage_passes_reussies' => rand(70, 90)
        ],
        'donnees_physiques' => [
            'vitesse_maximale' => rand(28, 35),
            'distance_moyenne_match' => rand(9, 12),
            'sprints_match' => rand(15, 25)
        ]
    ];
}

function generateSDOHData($nationalite, $age) {
    $isLocal = $nationalite === 'Tunisie';
    $baseScore = $isLocal ? 0.8 : 0.6;
    
    return [
        'profil_narratif' => "Joueur {$nationalite} avec profil adapté au contexte tunisien.",
        'scores_quantifies' => [
            'support_social_familial' => $baseScore + (rand(-10, 10) / 100),
            'stabilite_logement_nutrition' => $baseScore + (rand(-10, 10) / 100),
            'education_adaptation_culturelle' => $baseScore + (rand(-10, 10) / 100)
        ]
    ];
}

function generateMarketValueData($age, $poste, $club) {
    $baseValue = rand(500000, 3000000);
    $ageMultiplier = $age <= 23 ? 1.2 : ($age <= 26 ? 1.0 : 0.8);
    
    return [
        'valeur_marchande' => round($baseValue * $ageMultiplier),
        'duree_contrat_restante' => rand(1, 4),
        'salaire_mensuel' => rand(15000, 60000)
    ];
}

function generateAdherenceData($age) {
    $baseAdherence = $age < 25 ? 0.92 : 0.88;
    
    return [
        'taux_presence_entrainements' => round(($baseAdherence + (rand(-5, 5) / 100)) * 100, 1),
        'disponibilite_generale' => round(($baseAdherence + (rand(-8, 8) / 100)) * 100, 1),
        'score_adherence_protocole' => ['Excellent', 'Très bon', 'Bon', 'Moyen'][array_rand(['Excellent', 'Très bon', 'Bon', 'Moyen'])]
    ];
}

function calculateFITScore($id) {
    $health = rand(70, 95);
    $performance = rand(75, 92);
    $sdoh = rand(55, 90);
    $marketValue = rand(65, 88);
    $adherence = rand(80, 96);
    
    $fitScore = round(($health + $performance + $sdoh + $marketValue + $adherence) / 5, 1);
    
    return [
        'health_score' => $health,
        'performance_score' => $performance,
        'sdoh_score' => $sdoh,
        'market_value_score' => $marketValue,
        'adherence_score' => $adherence,
        'fit_score_final' => $fitScore,
        'categorie' => $fitScore >= 90 ? 'Excellent' : ($fitScore >= 80 ? 'Très bon' : ($fitScore >= 70 ? 'Bon' : 'Moyen'))
    ];
}

// Génération du dataset
$dataset = [
    'metadata' => [
        'championnat' => 'Ligue Professionnelle 1, Tunisie',
        'saison' => '2024-2025',
        'nombre_joueurs' => 50,
        'date_creation' => date('Y-m-d'),
        'description' => 'Dataset complet pour le calcul du FIT Score - 5 piliers : Health, Performance, SDOH, Market Value, Adherence'
    ],
    'joueurs' => []
];

for ($i = 1; $i <= 50; $i++) {
    $dataset['joueurs'][] = generateJoueur($i);
}

// Sauvegarde en JSON
$jsonOutput = json_encode($dataset, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents('dataset-50-joueurs-tunisie-2024-2025.json', $jsonOutput);

echo "✅ Dataset généré avec succès !\n";
echo "📁 Fichier : dataset-50-joueurs-tunisie-2024-2025.json\n";
echo "👥 Nombre de joueurs : " . count($dataset['joueurs']) . "\n";
echo "🏆 Championnat : " . $dataset['metadata']['championnat'] . "\n";
echo "📅 Saison : " . $dataset['metadata']['saison'] . "\n";

// Affichage d'un exemple
echo "\n📋 Exemple de joueur généré :\n";
$exemple = $dataset['joueurs'][0];
echo "Nom : " . $exemple['prenom'] . " " . $exemple['nom'] . "\n";
echo "Poste : " . $exemple['poste'] . "\n";
echo "Club : " . $exemple['club'] . "\n";
echo "FIT Score : " . $exemple['fit_score_calcul']['fit_score_final'] . " (" . $exemple['fit_score_calcul']['categorie'] . ")\n";







