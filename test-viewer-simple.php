<?php
echo "=== Test Simple Viewer PCMA ===\n";

echo "1. Vérification du fichier de vue PCMA...\n";
$pcmaFile = 'resources/views/pcma/create.blade.php';

if (file_exists($pcmaFile)) {
    $content = file_get_contents($pcmaFile);
    $size = filesize($pcmaFile);
    echo "✅ Fichier PCMA existe (taille: " . number_format($size) . " bytes)\n";
    
    // Vérifier les éléments essentiels
    $essentialElements = [
        'dicom-canvas' => 'Canvas du viewer',
        'dicom-viewer-container' => 'Container du viewer',
        'class DicomViewer' => 'Classe JavaScript',
        'showViewer()' => 'Fonction d\'affichage',
        'loadImage(' => 'Fonction de chargement',
        '🔍 Initializing DICOM viewer' => 'Logs de debug'
    ];
    
    echo "\n2. Éléments essentiels dans le fichier:\n";
    foreach ($essentialElements as $element => $description) {
        $count = substr_count($content, $element);
        if ($count > 0) {
            echo "✅ $element trouvé ($count fois) - $description\n";
        } else {
            echo "❌ $element manquant - $description\n";
        }
    }
    
    // Vérifier les inputs de fichiers
    $fileInputs = [
        'ecg_file' => 'Input ECG',
        'mri_file' => 'Input IRM',
        'xray_file' => 'Input Radiographie',
        'ct_scan_file' => 'Input Scanner',
        'ultrasound_file' => 'Input Échographie'
    ];
    
    echo "\n3. Inputs de fichiers:\n";
    foreach ($fileInputs as $input => $description) {
        $count = substr_count($content, $input);
        if ($count > 0) {
            echo "✅ $input trouvé ($count fois) - $description\n";
        } else {
            echo "❌ $input manquant - $description\n";
        }
    }
    
    // Vérifier les boutons de contrôle
    $controlButtons = [
        'viewer-zoom-in' => 'Zoom +',
        'viewer-zoom-out' => 'Zoom -',
        'viewer-reset' => 'Reset',
        'viewer-fullscreen' => 'Plein écran',
        'viewer-measure' => 'Mesure',
        'viewer-annotate' => 'Annotation',
        'viewer-screenshot' => 'Screenshot'
    ];
    
    echo "\n4. Boutons de contrôle:\n";
    foreach ($controlButtons as $button => $description) {
        $count = substr_count($content, $button);
        if ($count > 0) {
            echo "✅ $button trouvé ($count fois) - $description\n";
        } else {
            echo "❌ $button manquant - $description\n";
        }
    }
    
} else {
    echo "❌ Fichier PCMA manquant\n";
}

echo "\n=== Instructions de Test Manuel ===\n";
echo "Pour tester le viewer PCMA:\n";
echo "1. Démarrez le serveur: php artisan serve\n";
echo "2. Ouvrez votre navigateur\n";
echo "3. Allez sur: http://localhost:8000/login\n";
echo "4. Connectez-vous avec:\n";
echo "   - Email: test@example.com\n";
echo "   - Mot de passe: password\n";
echo "5. Allez sur: http://localhost:8000/pcma/create\n";
echo "6. Ouvrez la console du navigateur (F12)\n";
echo "7. Vérifiez les logs de debug:\n";
echo "   - 🔍 Initializing DICOM viewer...\n";
echo "   - ✅ Canvas trouvé, création du viewer...\n";
echo "   - ✅ DicomViewer créé avec succès\n";
echo "8. Testez le viewer:\n";
echo "   - Sélectionnez un fichier image (JPG, PNG)\n";
echo "   - Choisissez le type dans le select 'Sélectionner un fichier à visualiser'\n";
echo "   - Vérifiez que l'image s'affiche dans le canvas\n";
echo "9. Si l'image ne s'affiche pas:\n";
echo "   - Vérifiez les erreurs dans la console\n";
echo "   - Vérifiez que le canvas n'a plus la classe 'hidden'\n";
echo "   - Vérifiez que la fonction render() est appelée\n";

echo "\n=== Diagnostic des Problèmes ===\n";
echo "Problèmes courants et solutions:\n";
echo "1. Canvas invisible:\n";
echo "   - Vérifiez que showViewer() est appelée\n";
echo "   - Vérifiez que dicomCanvas.classList.remove('hidden') est exécuté\n";
echo "2. Image non chargée:\n";
echo "   - Vérifiez que loadImage() fonctionne\n";
echo "   - Vérifiez que le fichier est bien sélectionné\n";
echo "3. Erreur de rendu:\n";
echo "   - Vérifiez les dimensions du canvas\n";
echo "   - Vérifiez que ctx.drawImage() est exécuté\n";
echo "4. JavaScript non chargé:\n";
echo "   - Vérifiez la console pour les erreurs\n";
echo "   - Vérifiez que tous les scripts sont chargés\n";

echo "\n=== Test Rapide ===\n";
echo "Pour un test rapide:\n";
echo "1. Créez une image test simple (1x1 pixel PNG)\n";
echo "2. Uploadez-la dans le champ ECG\n";
echo "3. Sélectionnez 'ECG - Fichier sélectionné' dans le viewer\n";
echo "4. Vérifiez que l'image s'affiche\n";
echo "5. Testez les contrôles (zoom, pan, etc.)\n";
?> 