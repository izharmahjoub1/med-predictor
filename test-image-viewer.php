<?php

echo "=== Test Viewer d'Images Médicales ===\n";

if (file_exists('resources/views/health-records/create.blade.php')) {
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 1: Vérifier l'inclusion du script du viewer
    $scriptInclusions = [
        'image-viewer.js' => 'Script du viewer inclus',
        'MedicalImageViewer' => 'Classe MedicalImageViewer référencée'
    ];
    
    echo "=== Inclusion des Scripts ===\n";
    foreach ($scriptInclusions as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 2: Vérifier le viewer dentaire
    $dentalViewerFeatures = [
        'dental-image-viewer' => 'Container viewer dentaire',
        'dental-image-upload' => 'Upload d\'images dentaires',
        'load-dental-image-btn' => 'Bouton charger image dentaire',
        'initializeDentalImageViewer' => 'Fonction d\'initialisation viewer dentaire'
    ];
    
    echo "\n=== Viewer Dentaire ===\n";
    foreach ($dentalViewerFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 3: Vérifier le viewer médical
    $medicalViewerFeatures = [
        'medical-image-viewer' => 'Container viewer médical',
        'Charger dans le Viewer' => 'Bouton charger dans le viewer médical',
        'initializeMedicalImageViewer' => 'Fonction d\'initialisation viewer médical'
    ];
    
    echo "\n=== Viewer Médical ===\n";
    foreach ($medicalViewerFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 4: Vérifier les fonctionnalités du viewer
    $viewerFeatures = [
        'Zoom' => 'Fonctionnalité zoom',
        'Pan' => 'Fonctionnalité pan',
        'Mesures' => 'Outils de mesure',
        'Annotations' => 'Fonctionnalité annotations',
        'Contraste' => 'Ajustement contraste',
        'DICOM' => 'Support DICOM',
        'JPG/PNG' => 'Support formats standards',
        'TIFF' => 'Support TIFF',
        'Multi-plan' => 'Support multi-plan'
    ];
    
    echo "\n=== Fonctionnalités du Viewer ===\n";
    foreach ($viewerFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 5: Vérifier le fichier JavaScript du viewer
    if (file_exists('public/js/image-viewer.js')) {
        $viewerContent = file_get_contents('public/js/image-viewer.js');
        
        $jsFeatures = [
            'class MedicalImageViewer' => 'Classe principale du viewer',
            'loadImage' => 'Méthode de chargement d\'image',
            'isDicomFile' => 'Détection DICOM',
            'zoom' => 'Fonctionnalité zoom',
            'renderImage' => 'Rendu d\'image',
            'toggleMeasureTool' => 'Outil de mesure',
            'toggleAnnotateTool' => 'Outil d\'annotation',
            'toggleWindowLevel' => 'Fenêtrage DICOM',
            'exportImage' => 'Export d\'image'
        ];
        
        echo "\n=== Fonctionnalités JavaScript ===\n";
        foreach ($jsFeatures as $feature => $description) {
            if (strpos($viewerContent, $feature) !== false) {
                echo "✅ $description\n";
            } else {
                echo "❌ $description manquant\n";
            }
        }
    } else {
        echo "\n❌ Fichier image-viewer.js manquant\n";
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Allez sur http://localhost:8000/test-tabs\n";
echo "2. Testez l'onglet '🦷 Dossier Dentaire' :\n";
echo "   - Vérifiez la section '👁️ Viewer d\'Images Dentaires'\n";
echo "   - Testez l'upload d'un fichier (DICOM, JPG, PNG)\n";
echo "   - Cliquez sur '🔍 Charger dans le Viewer'\n";
echo "   - Testez les fonctionnalités du viewer (zoom, pan, mesures)\n";
echo "3. Testez l'onglet '📷 Imagerie Médicale' :\n";
echo "   - Vérifiez la section '🔍 Viewer d\'Images Médicales'\n";
echo "   - Upload un fichier et cliquez sur '👁️ Charger dans le Viewer'\n";
echo "   - Testez toutes les fonctionnalités du viewer\n";
echo "4. Fonctionnalités à tester :\n";
echo "   - Zoom in/out et reset view\n";
echo "   - Pan (glisser-déposer)\n";
echo "   - Outil de mesure (cliquer sur 2 points)\n";
echo "   - Outil d'annotation (ajouter des marqueurs)\n";
echo "   - Fenêtrage DICOM (pour les images DICOM)\n";
echo "   - Export d'image\n";
echo "   - Informations DICOM (pour les fichiers DICOM)\n"; 