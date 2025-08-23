<?php
echo "🔍 Diagnostic des middlewares d'authentification\n";
echo "===============================================\n\n";

// 1. Vérifier le fichier Kernel.php
$kernelFile = 'app/Http/Kernel.php';
if (file_exists($kernelFile)) {
    $content = file_get_contents($kernelFile);
    
    // Chercher le groupe de middlewares 'web'
    if (preg_match('/protected \$middlewareGroups = \[(.*?)\];/s', $content, $matches)) {
        echo "📋 Middlewares du groupe 'web' :\n";
        if (preg_match('/\'web\' => \[(.*?)\]/s', $matches[1], $webMatches)) {
            $webMiddlewares = $webMatches[1];
            $webMiddlewares = preg_replace('/\s+/', ' ', $webMiddlewares);
            echo $webMiddlewares . "\n\n";
            
            // Vérifier s'il y a des middlewares d'auth
            if (strpos($webMiddlewares, 'auth') !== false) {
                echo "⚠️  Middleware d'authentification détecté dans le groupe 'web'\n";
                echo "   Cela peut causer la redirection vers /login\n\n";
            }
        }
    }
} else {
    echo "❌ Fichier Kernel.php non trouvé\n";
}

// 2. Vérifier le fichier de routes web.php
echo "🔍 Vérification des routes web.php :\n";
$webFile = 'routes/web.php';
if (file_exists($webFile)) {
    $content = file_get_contents($webFile);
    
    // Chercher la route /pcma/voice-fallback
    if (preg_match('/Route::get\(\'\/pcma\/voice-fallback\'.*?\);/s', $content, $routeMatches)) {
        echo "✅ Route /pcma/voice-fallback trouvée :\n";
        echo trim($routeMatches[0]) . "\n\n";
    } else {
        echo "❌ Route /pcma/voice-fallback non trouvée\n";
    }
}

// 3. Solution recommandée
echo "�� Solution recommandée :\n";
echo "   Créer un groupe de routes sans authentification\n";
echo "   ou exclure cette route spécifique de l'auth\n\n";
