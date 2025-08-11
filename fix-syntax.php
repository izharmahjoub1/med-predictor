<?php
echo "=== Correction Complète de la Syntaxe ===\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Compter les accolades
    $openBraces = substr_count($content, '{');
    $closeBraces = substr_count($content, '}');
    
    echo "Accolades ouvertes: $openBraces\n";
    echo "Accolades fermées: $closeBraces\n";
    
    if ($openBraces !== $closeBraces) {
        echo "🔧 Correction nécessaire...\n";
        
        // Remplacer les commentaires problématiques
        $content = str_replace(
            "// Route::get('register', function () {\n    //     return view('auth.register');",
            "// Route::get('register', function () {\n    //     return view('auth.register');\n    // });",
            $content
        );
        
        $content = str_replace(
            "// Route::get('forgot-password', function () {\n    //     return view('auth.forgot-password');",
            "// Route::get('forgot-password', function () {\n    //     return view('auth.forgot-password');\n    // });",
            $content
        );
        
        $content = str_replace(
            "// Route::get('/profile', function () {\n    //     //return view('profile.edit');",
            "// Route::get('/profile', function () {\n    //     return view('profile.edit');\n    // });",
            $content
        );
        
        $content = str_replace(
            "// Route::get('/profile/settings', function () {\n        //return view('profile.settings');",
            "// Route::get('/profile/settings', function () {\n    //     return view('profile.settings');\n    // });",
            $content
        );
        
        // Ajouter les accolades manquantes si nécessaire
        $newOpenBraces = substr_count($content, '{');
        $newCloseBraces = substr_count($content, '}');
        
        if ($newOpenBraces > $newCloseBraces) {
            $missingBraces = $newOpenBraces - $newCloseBraces;
            echo "Ajout de $missingBraces accolade(s) fermante(s)\n";
            $content .= str_repeat('}', $missingBraces);
        }
        
        file_put_contents($routesFile, $content);
        echo "✅ Syntaxe corrigée\n";
        
        // Vérifier le résultat
        $finalOpenBraces = substr_count($content, '{');
        $finalCloseBraces = substr_count($content, '}');
        echo "Après correction - Ouvertes: $finalOpenBraces, Fermées: $finalCloseBraces\n";
    } else {
        echo "✅ Syntaxe déjà correcte\n";
    }
    
    // Vérifier la syntaxe PHP
    $output = shell_exec('php -l routes/web.php 2>&1');
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✅ Syntaxe PHP correcte\n";
    } else {
        echo "❌ Erreur de syntaxe PHP:\n";
        echo $output;
    }
} else {
    echo "❌ Fichier routes/web.php non trouvé\n";
}

echo "\n=== Nettoyage du Cache ===\n";
shell_exec('php artisan route:clear');
shell_exec('php artisan config:clear');
shell_exec('php artisan cache:clear');
echo "✅ Caches nettoyés\n";

echo "\n=== Test de la Route ===\n";
$output = shell_exec('php artisan route:list | grep licenses.validation 2>&1');
if (strpos($output, 'licenses.validation') !== false) {
    echo "✅ Route licenses.validation trouvée:\n";
    echo $output;
} else {
    echo "❌ Route licenses.validation non trouvée\n";
}

echo "\n🎉 CORRECTION TERMINÉE !\n";
?> 