<?php
echo "🔧 Correction de l'erreur Vue.js submissionResult\n";
echo "================================================\n\n";

$viewFile = 'resources/views/pcma/voice-fallback.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Chercher la section data() du composant Vue
    if (preg_match('/(data\(\)\s*\{\s*return\s*\{)(.*?)(\};)/s', $content, $matches)) {
        $dataSection = $matches[2];
        
        // Vérifier si submissionResult est déjà défini
        if (strpos($dataSection, 'submissionResult') === false) {
            // Ajouter submissionResult: null après sessionId
            if (strpos($dataSection, 'sessionId: null') !== false) {
                $newDataSection = str_replace(
                    'sessionId: null,',
                    "sessionId: null,\n            submissionResult: null,",
                    $dataSection
                );
                
                $newContent = str_replace($matches[2], $newDataSection, $content);
                
                if (file_put_contents($viewFile, $newContent)) {
                    echo "✅ Variable submissionResult ajoutée avec succès\n";
                } else {
                    echo "❌ Erreur lors de la sauvegarde\n";
                }
            } else {
                echo "⚠️  sessionId non trouvé, ajout manuel nécessaire\n";
            }
        } else {
            echo "ℹ️  Variable submissionResult déjà présente\n";
        }
    } else {
        echo "❌ Section data() du composant Vue non trouvée\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}
