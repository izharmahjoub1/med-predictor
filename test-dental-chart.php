<?php

echo "=== Test Diagramme Dentaire et Clarifications Upload ===\n";

if (file_exists('resources/views/health-records/create.blade.php')) {
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 1: Vérifier le diagramme dentaire interactif
    $dentalChartFeatures = [
        'dental-chart.svg' => 'Diagramme dentaire SVG',
        'Diagramme Dentaire Interactif' => 'Titre du diagramme',
        'save-dental-chart-btn' => 'Bouton sauvegarder',
        'normal-count' => 'Compteur dents normales',
        'cavity-count' => 'Compteur caries',
        'filling-count' => 'Compteur obturations',
        'crown-count' => 'Compteur couronnes',
        'missing-count' => 'Compteur dents manquantes',
        'implant-count' => 'Compteur implants'
    ];
    
    echo "=== Diagramme Dentaire Interactif ===\n";
    foreach ($dentalChartFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 2: Vérifier les instructions d'upload
    $uploadInstructions = [
        'Instructions d\'Upload' => 'Titre instructions upload',
        'DICOM' => 'Format DICOM mentionné',
        'JPG/PNG' => 'Formats JPG/PNG mentionnés',
        'Taille max' => 'Limite de taille mentionnée',
        'Qualité' => 'Recommandations qualité',
        'Résolution minimale' => 'Spécifications résolution'
    ];
    
    echo "\n=== Instructions d'Upload ===\n";
    foreach ($uploadInstructions as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 3: Vérifier les instructions d'analyse IA
    $aiInstructions = [
        'Processus d\'Analyse IA' => 'Titre processus IA',
        'Upload' => 'Étape upload',
        'Analyse' => 'Étape analyse',
        'Résultats' => 'Étape résultats',
        'Diagnostic' => 'Étape diagnostic',
        'CDA' => 'Étape CDA',
        'Med Gemini' => 'Référence Med Gemini'
    ];
    
    echo "\n=== Instructions d'Analyse IA ===\n";
    foreach ($aiInstructions as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 4: Vérifier le viewer d'images
    $viewerFeatures = [
        'Viewer d\'Images Médicales' => 'Titre viewer',
        'Zoom' => 'Fonctionnalité zoom',
        'Pan' => 'Fonctionnalité pan',
        'Mesures' => 'Outils de mesure',
        'Annotations' => 'Fonctionnalité annotations',
        'Contraste' => 'Ajustement contraste',
        'Multi-plan' => 'Support multi-plan'
    ];
    
    echo "\n=== Viewer d'Images Médicales ===\n";
    foreach ($viewerFeatures as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description manquant\n";
        }
    }
    
    // Test 5: Vérifier les fichiers SVG
    $svgFiles = [
        'dental-chart.svg' => 'Diagramme dentaire interactif',
        'dental-panoramic.svg' => 'Panoramique dentaire',
        'dental-bitewing.svg' => 'Bitewing',
        'dental-periapical.svg' => 'Périapicale'
    ];
    
    echo "\n=== Fichiers SVG ===\n";
    foreach ($svgFiles as $file => $description) {
        if (file_exists("public/images/$file")) {
            echo "✅ $description ($file)\n";
        } else {
            echo "❌ $description ($file) manquant\n";
        }
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Allez sur http://localhost:8000/test-tabs\n";
echo "2. Testez l'onglet '🦷 Dossier Dentaire' :\n";
echo "   - Vérifiez le diagramme dentaire interactif\n";
echo "   - Testez les sélecteurs d'état pour chaque dent\n";
echo "   - Vérifiez le résumé des états dentaires\n";
echo "3. Testez l'onglet '📷 Imagerie Médicale' :\n";
echo "   - Vérifiez les instructions d'upload claires\n";
echo "   - Testez l'upload de fichiers (DICOM, JPG, PNG)\n";
echo "   - Vérifiez les instructions d'analyse IA\n";
echo "   - Testez le bouton '🔍 Analyser avec l'IA'\n";
echo "   - Vérifiez les informations sur le viewer\n";
echo "4. Vérifiez que tous les SVG s'affichent correctement\n"; 