<?php
echo "🔧 Correction du contexte Vue.js vs Blade\n";
echo "========================================\n\n";

$viewFile = 'resources/views/pcma/voice-fallback.blade.php';

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    
    // Supprimer temporairement la section problématique
    $problematicSection = '        <!-- Résultat de soumission -->
        <div v-if="submissionResult" class="max-w-2xl mx-auto mt-6">
            <div :class="submissionResult.success ? \'bg-green-100 border-green-500 text-green-700\' : \'bg-red-100 border-red-500 text-red-700\'" 
                 class="p-4 border-l-4 rounded">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">
                        {{ submissionResult.success ? "✅" : "❌" }}
                    </span>
                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ submissionResult.success ? "Formulaire soumis avec succès !" : "Erreur lors de la soumission" }}
                        </h3>
                        <p class="mt-1">{{ submissionResult.message }}</p>
                        <div v-if="submissionResult.reference" class="mt-2 p-2 bg-white rounded border">
                            <strong>Référence :</strong> {{ submissionResult.reference }}
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    
    if (strpos($content, $problematicSection) !== false) {
        $content = str_replace($problematicSection, '        <!-- Résultat de soumission - Temporairement désactivé -->', $content);
        echo "✅ Section problématique supprimée temporairement\n";
    } else {
        echo "ℹ️  Section problématique non trouvée\n";
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
