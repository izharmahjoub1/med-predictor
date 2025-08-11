<?php

echo "=== Test Onglet Imagerie Médicale ===\n";

if (file_exists('resources/views/health-records/create.blade.php')) {
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 1: Vérifier que l'onglet est dans la configuration
    if (strpos($content, "'medical-imaging'") !== false) {
        echo "✅ Onglet 'medical-imaging' dans la configuration\n";
    } else {
        echo "❌ Onglet 'medical-imaging' manquant dans la configuration\n";
    }
    
    // Test 2: Vérifier que l'onglet est dans la navigation
    if (strpos($content, "Imagerie Médicale") !== false) {
        echo "✅ Onglet 'Imagerie Médicale' dans la navigation\n";
    } else {
        echo "❌ Onglet 'Imagerie Médicale' manquant dans la navigation\n";
    }
    
    // Test 3: Vérifier que l'icône est présente
    if (strpos($content, "📷") !== false) {
        echo "✅ Icône imagerie (📷) présente\n";
    } else {
        echo "❌ Icône imagerie manquante\n";
    }
    
    // Test 4: Vérifier que le contenu de l'onglet est présent
    if (strpos($content, "activeTab === 'medical-imaging'") !== false) {
        echo "✅ Contenu de l'onglet imagerie présent\n";
    } else {
        echo "❌ Contenu de l'onglet imagerie manquant\n";
    }
    
    // Test 5: Vérifier les types d'examens d'imagerie
    $imagingTypes = [
        'xray_chest' => 'Radiographie thoracique',
        'xray_spine' => 'Radiographie rachis',
        'xray_limb' => 'Radiographie membre',
        'xray_skull' => 'Radiographie crâne',
        'ct_head' => 'Scanner cérébral',
        'ct_chest' => 'Scanner thoracique',
        'ct_abdomen' => 'Scanner abdominal',
        'ct_spine' => 'Scanner rachis',
        'mri_brain' => 'IRM cérébrale',
        'mri_spine' => 'IRM rachis',
        'mri_knee' => 'IRM genou',
        'mri_shoulder' => 'IRM épaule',
        'us_abdomen' => 'Échographie abdominale',
        'us_heart' => 'Échographie cardiaque',
        'us_vascular' => 'Échographie vasculaire',
        'us_musculoskeletal' => 'Échographie musculo-squelettique'
    ];
    
    echo "\n=== Types d'Examens d'Imagerie ===\n";
    foreach ($imagingTypes as $type => $description) {
        if (strpos($content, $type) !== false) {
            echo "✅ $description ($type)\n";
        } else {
            echo "❌ $description ($type) manquant\n";
        }
    }
    
    // Test 6: Vérifier les fonctionnalités d'upload
    $uploadFeatures = [
        'imaging_file' => 'Champ upload fichier',
        'file-upload-area' => 'Zone de drop',
        'file-preview' => 'Aperçu fichier',
        'remove-file' => 'Bouton supprimer fichier',
        'accept=".dcm,.dicom,.jpg,.jpeg,.png,.tiff,.tif"' => 'Types de fichiers acceptés'
    ];
    
    echo "\n=== Fonctionnalités Upload ===\n";
    foreach ($uploadFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 7: Vérifier l'analyse IA
    $aiFeatures = [
        'analyze-imaging-btn' => 'Bouton analyse IA',
        'generate-cda-btn' => 'Bouton génération CDA',
        'ai-analysis-result' => 'Résultat analyse IA',
        'Med Gemini' => 'Référence Med Gemini',
        'generateAIAnalysis' => 'Fonction analyse IA',
        'generateCDAReport' => 'Fonction rapport CDA'
    ];
    
    echo "\n=== Fonctionnalités IA ===\n";
    foreach ($aiFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 8: Vérifier les champs du formulaire
    $formFields = [
        'imaging_type' => 'Type d\'examen',
        'imaging_date' => 'Date d\'examen',
        'imaging_facility' => 'Établissement',
        'imaging_radiologist' => 'Radiologue',
        'imaging_indication' => 'Indication',
        'imaging_technique' => 'Technique',
        'imaging_findings' => 'Résultats',
        'imaging_conclusion' => 'Conclusion'
    ];
    
    echo "\n=== Champs du Formulaire ===\n";
    foreach ($formFields as $field => $description) {
        if (strpos($content, $field) !== false) {
            echo "✅ $description ($field)\n";
        } else {
            echo "❌ $description ($field) manquant\n";
        }
    }
    
    // Test 9: Vérifier le résumé imagerie
    $summaryFeatures = [
        'imaging-count' => 'Compteur d\'examens',
        'imaging-types' => 'Types d\'examens',
        'imaging-abnormal' => 'Résultats anormaux',
        'Résumé Imagerie' => 'Titre résumé'
    ];
    
    echo "\n=== Résumé Imagerie ===\n";
    foreach ($summaryFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 10: Vérifier les fonctions JavaScript
    $jsFunctions = [
        'initializeImagingManagement' => 'Initialisation imagerie',
        'saveImagingRecord' => 'Sauvegarde enregistrement',
        'clearImagingForm' => 'Nettoyage formulaire',
        'updateImagingList' => 'Mise à jour liste',
        'editImagingRecord' => 'Édition enregistrement',
        'deleteImagingRecord' => 'Suppression enregistrement',
        'analyzeImagingWithAI' => 'Analyse IA',
        'generateCDAXML' => 'Génération XML CDA'
    ];
    
    echo "\n=== Fonctions JavaScript ===\n";
    foreach ($jsFunctions as $function => $description) {
        if (strpos($content, $function) !== false) {
            echo "✅ $description ($function)\n";
        } else {
            echo "❌ $description ($function) manquant\n";
        }
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Allez sur http://localhost:8000/test-tabs\n";
echo "2. Cliquez sur l'onglet '📷 Imagerie Médicale'\n";
echo "3. Testez les fonctionnalités :\n";
echo "   - Bouton '➕ Ajouter un examen'\n";
echo "   - Upload de fichier (DICOM, JPG, PNG)\n";
echo "   - Sélection des types d'examens\n";
echo "   - Bouton '🔍 Analyser avec l'IA'\n";
echo "   - Bouton '📄 Générer Rapport CDA'\n";
echo "   - Sauvegarde et édition des examens\n";
echo "4. Vérifiez le résumé en bas de page\n"; 