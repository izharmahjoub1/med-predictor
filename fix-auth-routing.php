<?php
echo "�� Correction du routage pour /pcma/voice-fallback\n";
echo "================================================\n\n";

$webFile = 'routes/web.php';
$content = file_get_contents($webFile);

// 1. Supprimer la route existante
$oldRoute = "Route::get('/pcma/voice-fallback', function () {\n    return view('pcma.voice-fallback');\n})->name('pcma.voice-fallback');";

if (strpos($content, $oldRoute) !== false) {
    echo "✅ Route existante trouvée et supprimée\n";
    $content = str_replace($oldRoute, '', $content);
}

// 2. Ajouter le nouveau groupe de routes sans authentification
$newGroup = "\n// Routes PCMA sans authentification\nRoute::middleware(['web'])->group(function () {\n    Route::get('/pcma/voice-fallback', function () {\n        return view('pcma.voice-fallback');\n    })->name('pcma.voice-fallback');\n});\n";

// Insérer après la ligne "use Illuminate\\Support\\Facades\\Route;"
if (strpos($content, 'use Illuminate\\Support\\Facades\\Route;') !== false) {
    $content = str_replace(
        'use Illuminate\\Support\\Facades\\Route;',
        'use Illuminate\\Support\\Facades\\Route;' . $newGroup,
        $content
    );
    echo "✅ Nouveau groupe de routes ajouté\n";
} else {
    echo "⚠️  Ligne 'use Illuminate\\Support\\Facades\\Route;' non trouvée\n";
}

// 3. Sauvegarder le fichier
if (file_put_contents($webFile, $content)) {
    echo "✅ Fichier routes/web.php mis à jour\n";
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}

echo "\n�� Vérification de la modification :\n";
if (strpos($content, '/pcma/voice-fallback') !== false) {
    echo "✅ Route /pcma/voice-fallback présente dans le fichier\n";
} else {
    echo "❌ Route /pcma/voice-fallback non trouvée\n";
}
