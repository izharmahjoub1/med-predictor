<?php

echo "=== Test Onglet Dossier Dentaire ===\n";

if (file_exists('resources/views/health-records/create.blade.php')) {
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 1: Vérifier que l'onglet est dans la configuration
    if (strpos($content, "'dental-record'") !== false) {
        echo "✅ Onglet 'dental-record' dans la configuration\n";
    } else {
        echo "❌ Onglet 'dental-record' manquant dans la configuration\n";
    }
    
    // Test 2: Vérifier que l'onglet est dans la navigation
    if (strpos($content, "Dossier Dentaire") !== false) {
        echo "✅ Onglet 'Dossier Dentaire' dans la navigation\n";
    } else {
        echo "❌ Onglet 'Dossier Dentaire' manquant dans la navigation\n";
    }
    
    // Test 3: Vérifier que l'icône est présente
    if (strpos($content, "🦷") !== false) {
        echo "✅ Icône dentaire (🦷) présente\n";
    } else {
        echo "❌ Icône dentaire manquante\n";
    }
    
    // Test 4: Vérifier que le contenu de l'onglet est présent
    if (strpos($content, "activeTab === 'dental-record'") !== false) {
        echo "✅ Contenu de l'onglet dentaire présent\n";
    } else {
        echo "❌ Contenu de l'onglet dentaire manquant\n";
    }
    
    // Test 5: Vérifier les champs du formulaire dentaire
    $dentalFields = [
        'dental_patient_name' => 'Nom du patient',
        'dental_patient_birthdate' => 'Date de naissance',
        'dental_patient_gender' => 'Sexe',
        'dental_patient_phone' => 'Téléphone',
        'dental_patient_email' => 'Email',
        'dental_patient_address' => 'Adresse',
        'dental_appointment_date' => 'Date du rendez-vous',
        'dental_appointment_time' => 'Heure du rendez-vous',
        'dental_appointment_reason' => 'Motif du rendez-vous',
        'dental_notes' => 'Notes générales',
        'dental_treatment_plan' => 'Plan de traitement'
    ];
    
    echo "\n=== Champs du Formulaire Dentaire ===\n";
    foreach ($dentalFields as $field => $description) {
        if (strpos($content, $field) !== false) {
            echo "✅ $description ($field)\n";
        } else {
            echo "❌ $description ($field) manquant\n";
        }
    }
    
    // Test 6: Vérifier le nombre total d'onglets
    $tabCount = substr_count($content, "activeTab === '");
    echo "\n✅ Nombre total d'onglets: $tabCount\n";
    
    // Test 7: Lister tous les onglets
    echo "\n=== Liste des Onglets ===\n";
    $tabs = [
        'general' => 'Informations Générales',
        'ai-assistant' => 'Assistant IA',
        'medical-categories' => 'Catégories Médicales',
        'dental-record' => 'Dossier Dentaire',
        'doping-control' => 'Contrôle Anti-Dopage',
        'physical-assessments' => 'Évaluations Physiques',
        'postural-assessment' => 'Évaluation Posturale',
        'vaccinations' => 'Vaccinations',
        'medical-imaging' => 'Imagerie Médicale',
        'notes-observations' => 'Notes et Observations'
    ];
    
    foreach ($tabs as $tabId => $tabName) {
        if (strpos($content, "activeTab === '$tabId'") !== false) {
            echo "✅ $tabName ($tabId)\n";
        } else {
            echo "❌ $tabName ($tabId) manquant\n";
        }
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Allez sur http://localhost:8000/test-tabs\n";
echo "2. Cliquez sur l'onglet '🦷 Dossier Dentaire'\n";
echo "3. Vous devriez voir le formulaire complet du dossier dentaire\n";
echo "4. Tous les champs devraient être fonctionnels\n"; 