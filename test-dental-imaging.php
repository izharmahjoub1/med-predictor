<?php

echo "=== Test Imagerie Dentaire ===\n";

if (file_exists('resources/views/health-records/create.blade.php')) {
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 1: Vérifier les images SVG dentaires
    $svgFiles = [
        'dental-panoramic.svg' => 'Panoramique dentaire',
        'dental-bitewing.svg' => 'Bitewing',
        'dental-periapical.svg' => 'Périapicale'
    ];
    
    echo "=== Images SVG Dentaires ===\n";
    foreach ($svgFiles as $file => $description) {
        if (file_exists("public/images/$file")) {
            echo "✅ $description ($file)\n";
        } else {
            echo "❌ $description ($file) manquant\n";
        }
    }
    
    // Test 2: Vérifier les types d'examens dentaires
    $dentalTypes = [
        'panoramic' => 'Panoramique dentaire',
        'bitewing' => 'Bitewing (interproximale)',
        'periapical' => 'Périapicale',
        'occlusal' => 'Occlusale',
        'cbct' => 'Cone Beam CT (CBCT)',
        'dental_ct' => 'Scanner dentaire',
        'intraoral' => 'Photo intra-orale',
        'extraoral' => 'Photo extra-orale',
        'model' => 'Modèle 3D'
    ];
    
    echo "\n=== Types d'Examens Dentaires ===\n";
    foreach ($dentalTypes as $type => $description) {
        if (strpos($content, $type) !== false) {
            echo "✅ $description ($type)\n";
        } else {
            echo "❌ $description ($type) manquant\n";
        }
    }
    
    // Test 3: Vérifier les fonctionnalités d'upload dentaire
    $dentalUploadFeatures = [
        'dental_imaging_file' => 'Champ upload fichier dentaire',
        'dental-file-upload-area' => 'Zone de drop dentaire',
        'dental-file-preview' => 'Aperçu fichier dentaire',
        'remove-dental-file' => 'Bouton supprimer fichier dentaire',
        'accept=".dcm,.dicom,.jpg,.jpeg,.png,.tiff,.tif,.svg"' => 'Types de fichiers acceptés (incluant SVG)'
    ];
    
    echo "\n=== Fonctionnalités Upload Dentaire ===\n";
    foreach ($dentalUploadFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 4: Vérifier les boutons d'action dentaire
    $dentalActions = [
        'add-dental-imaging-btn' => 'Bouton ajouter examen dentaire',
        'analyze-dental-imaging-btn' => 'Bouton analyse IA dentaire',
        'dental-imaging-list' => 'Liste des examens dentaires'
    ];
    
    echo "\n=== Actions Dentaires ===\n";
    foreach ($dentalActions as $action => $description) {
        if (strpos($content, $action) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 5: Vérifier les fonctions JavaScript dentaires
    $dentalFunctions = [
        'initializeDentalImagingManagement' => 'Initialisation imagerie dentaire',
        'saveDentalImagingRecord' => 'Sauvegarde enregistrement dentaire',
        'updateDentalImagingList' => 'Mise à jour liste dentaire',
        'analyzeDentalImagingWithAI' => 'Analyse IA dentaire',
        'generateDentalAIAnalysis' => 'Génération analyse IA dentaire',
        'getDentalImagingTypeDisplayName' => 'Affichage nom type dentaire'
    ];
    
    echo "\n=== Fonctions JavaScript Dentaires ===\n";
    foreach ($dentalFunctions as $function => $description) {
        if (strpos($content, $function) !== false) {
            echo "✅ $description ($function)\n";
        } else {
            echo "❌ $description ($function) manquant\n";
        }
    }
    
    // Test 6: Vérifier la galerie d'exemples
    $galleryFeatures = [
        'Exemples d\'Imageries Dentaires' => 'Titre galerie',
        'dental-panoramic.svg' => 'Image panoramique',
        'dental-bitewing.svg' => 'Image bitewing',
        'dental-periapical.svg' => 'Image périapicale'
    ];
    
    echo "\n=== Galerie d'Exemples ===\n";
    foreach ($galleryFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 7: Vérifier les champs du formulaire dentaire
    $dentalFormFields = [
        'dental_imaging_type' => 'Type d\'examen dentaire',
        'dental_imaging_date' => 'Date d\'examen dentaire',
        'dental_imaging_notes' => 'Notes examen dentaire'
    ];
    
    echo "\n=== Champs Formulaire Dentaire ===\n";
    foreach ($dentalFormFields as $field => $description) {
        if (strpos($content, $field) !== false) {
            echo "✅ $description ($field)\n";
        } else {
            echo "❌ $description ($field) manquant\n";
        }
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Allez sur http://localhost:8000/test-tabs\n";
echo "2. Cliquez sur l'onglet '🦷 Dossier Dentaire'\n";
echo "3. Testez les fonctionnalités :\n";
echo "   - Sélectionnez un type d'examen dentaire\n";
echo "   - Upload un fichier (DICOM, JPG, PNG, SVG)\n";
echo "   - Vérifiez la galerie d'exemples d'images\n";
echo "   - Cliquez sur '➕ Ajouter l'Examen'\n";
echo "   - Cliquez sur '🔍 Analyser avec l'IA'\n";
echo "4. Vérifiez que les images SVG s'affichent correctement\n"; 