<?php
echo "=== Diagnostic Viewer PCMA ===\n";

// Test 1: Vérifier l'existence du fichier PCMA
$pcmaFile = 'resources/views/pcma/create.blade.php';
if (file_exists($pcmaFile)) {
    echo "✅ Fichier PCMA existe\n";
    $content = file_get_contents($pcmaFile);
    
    // Test 2: Vérifier les éléments du viewer
    $viewerElements = [
        'dicom-canvas' => 'Canvas du viewer',
        'dicom-viewer-container' => 'Container du viewer',
        'dicom-viewer-select' => 'Select pour choisir le fichier',
        'dicom-loading' => 'Indicateur de chargement',
        'dicom-error' => 'Indicateur d\'erreur',
        'dicom-placeholder' => 'Placeholder'
    ];
    
    echo "\n2. Éléments du viewer:\n";
    foreach ($viewerElements as $element => $description) {
        if (strpos($content, $element) !== false) {
            echo "✅ $element trouvé - $description\n";
        } else {
            echo "❌ $element manquant - $description\n";
        }
    }
    
    // Test 3: Vérifier les fonctions JavaScript
    $jsFunctions = [
        'showViewer()' => 'Fonction pour afficher le viewer',
        'hideViewer()' => 'Fonction pour masquer le viewer',
        'class DicomViewer' => 'Classe DicomViewer',
        'loadImage(' => 'Fonction de chargement d\'image',
        'render()' => 'Fonction de rendu'
    ];
    
    echo "\n3. Fonctions JavaScript:\n";
    foreach ($jsFunctions as $function => $description) {
        if (strpos($content, $function) !== false) {
            echo "✅ $function trouvée - $description\n";
        } else {
            echo "❌ $function manquante - $description\n";
        }
    }
    
    // Test 4: Vérifier les boutons de contrôle
    $controlButtons = [
        'viewer-zoom-in' => 'Bouton zoom +',
        'viewer-zoom-out' => 'Bouton zoom -',
        'viewer-reset' => 'Bouton reset',
        'viewer-fullscreen' => 'Bouton plein écran',
        'viewer-measure' => 'Bouton mesure',
        'viewer-annotate' => 'Bouton annotation',
        'viewer-screenshot' => 'Bouton screenshot'
    ];
    
    echo "\n4. Boutons de contrôle:\n";
    foreach ($controlButtons as $button => $description) {
        if (strpos($content, $button) !== false) {
            echo "✅ $button trouvé - $description\n";
        } else {
            echo "❌ $button manquant - $description\n";
        }
    }
    
    // Test 5: Vérifier les inputs de fichiers
    $fileInputs = [
        'ecg_file' => 'Input fichier ECG',
        'mri_file' => 'Input fichier IRM',
        'xray_file' => 'Input fichier radiographie',
        'ct_scan_file' => 'Input fichier scanner',
        'ultrasound_file' => 'Input fichier échographie'
    ];
    
    echo "\n5. Inputs de fichiers:\n";
    foreach ($fileInputs as $input => $description) {
        if (strpos($content, $input) !== false) {
            echo "✅ $input trouvé - $description\n";
        } else {
            echo "❌ $input manquant - $description\n";
        }
    }
    
    // Test 6: Vérifier les métadonnées
    $metadataElements = [
        'dicom-metadata' => 'Panel métadonnées',
        'dicom-measurements' => 'Panel mesures',
        'dicom-info' => 'Info fichier',
        'dicom-dimensions' => 'Dimensions',
        'dicom-zoom' => 'Zoom level'
    ];
    
    echo "\n6. Éléments de métadonnées:\n";
    foreach ($metadataElements as $element => $description) {
        if (strpos($content, $element) !== false) {
            echo "✅ $element trouvé - $description\n";
        } else {
            echo "❌ $element manquant - $description\n";
        }
    }
    
} else {
    echo "❌ Fichier PCMA manquant\n";
}

echo "\n=== Instructions de Debug ===\n";
echo "1. Ouvrez la console du navigateur (F12)\n";
echo "2. Allez sur la page PCMA: http://localhost:8000/pcma/create\n";
echo "3. Vérifiez les erreurs JavaScript\n";
echo "4. Testez le chargement d'une image:\n";
echo "   - Sélectionnez un fichier (ECG, IRM, etc.)\n";
echo "   - Choisissez le type dans le select 'Sélectionner un fichier à visualiser'\n";
echo "   - Vérifiez que le canvas devient visible\n";
echo "5. Si le canvas reste caché, vérifiez:\n";
echo "   - Que la fonction showViewer() est appelée\n";
echo "   - Que dicomCanvas.classList.remove('hidden') est exécuté\n";
echo "   - Que l'image est bien chargée dans dicomViewer.image\n";
echo "6. Si l'image ne s'affiche pas, vérifiez:\n";
echo "   - Que la fonction render() est appelée\n";
echo "   - Que les dimensions du canvas sont correctes\n";
echo "   - Que ctx.drawImage() est exécuté sans erreur\n";

echo "\n=== Commandes de Test ===\n";
echo "Pour tester le viewer PCMA:\n";
echo "1. php artisan serve\n";
echo "2. Ouvrir http://localhost:8000/pcma/create\n";
echo "3. Uploader un fichier image\n";
echo "4. Sélectionner le type dans le viewer\n";
echo "5. Vérifier que l'image s'affiche\n";
?> 