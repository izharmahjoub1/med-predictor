<?php
echo "🔧 Correction de la syntaxe Vue.js vs Blade\n";
echo "==========================================\n\n";

$viewFile = 'resources/views/pcma/voice-fallback.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Remplacer la syntaxe Blade incorrecte par la syntaxe Vue.js correcte
    $oldSyntax = '{{ submissionResult.success ? \'✅\' : \'❌\' }}';
    $newSyntax = '{{ submissionResult.success ? "✅" : "❌" }}';
    
    if (strpos($content, $oldSyntax) !== false) {
        $content = str_replace($oldSyntax, $newSyntax, $content);
        echo "✅ Syntaxe 1 corrigée\n";
    }
    
    $oldSyntax2 = '{{ submissionResult.success ? \'Formulaire soumis avec succès !\' : \'Erreur lors de la soumission\' }}';
    $newSyntax2 = '{{ submissionResult.success ? "Formulaire soumis avec succès !" : "Erreur lors de la soumission" }}';
    
    if (strpos($content, $oldSyntax2) !== false) {
        $content = str_replace($oldSyntax2, $newSyntax2, $content);
        echo "✅ Syntaxe 2 corrigée\n";
    }
    
    $oldSyntax3 = '{{ submissionResult.message }}';
    $newSyntax3 = '{{ submissionResult.message }}';
    
    if (strpos($content, $oldSyntax3) !== false) {
        $content = str_replace($oldSyntax3, $newSyntax3, $content);
        echo "✅ Syntaxe 3 corrigée\n";
    }
    
    $oldSyntax4 = '{{ submissionResult.reference }}';
    $newSyntax4 = '{{ submissionResult.reference }}';
    
    if (strpos($content, $oldSyntax4) !== false) {
        $content = str_replace($oldSyntax4, $newSyntax4, $content);
        echo "✅ Syntaxe 4 corrigée\n";
    }
    
    // Sauvegarder le fichier
    if (file_put_contents($viewFile, $content)) {
        echo "✅ Fichier sauvegardé avec succès\n";
    } else {
        echo "❌ Erreur lors de la sauvegarde\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}
