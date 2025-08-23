<?php
/**
 * Script de résolution rapide de l'erreur 404 PCMA
 */

echo "🚀 Résolution rapide de l'erreur 404 PCMA\n";
echo "=========================================\n\n";

// 1. Nettoyer tous les caches Laravel
echo "🧹 1. Nettoyage des caches Laravel\n";
$commands = [
    'php artisan route:clear',
    'php artisan config:clear', 
    'php artisan view:clear',
    'php artisan cache:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "   🔄 $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "   ✅ " . trim($output) . "\n";
}

// 2. Vérifier que la route est bien enregistrée
echo "\n📋 2. Vérification des routes\n";
$routeList = shell_exec('php artisan route:list 2>&1');
if (strpos($routeList, 'pcma/voice-fallback') !== false) {
    echo "   ✅ Route PCMA trouvée dans la liste des routes\n";
} else {
    echo "   ❌ Route PCMA non trouvée - problème de configuration\n";
}

// 3. Créer un fichier de test simple
echo "\n📄 3. Création d'un fichier de test simple\n";
$testView = 'resources/views/pcma/test-simple.blade.php';
$simpleContent = '<!DOCTYPE html>
<html>
<head>
    <title>Test PCMA</title>
</head>
<body>
    <h1>Test PCMA - Page simple</h1>
    <p>Si vous voyez cette page, Laravel fonctionne !</p>
    <p>Date: ' . date('Y-m-d H:i:s') . '</p>
</body>
</html>';

file_put_contents($testView, $simpleContent);
echo "   ✅ Fichier de test créé: $testView\n";

// 4. Ajouter une route de test
echo "\n🔗 4. Ajout d'une route de test\n";
$testRoute = "\n// Route de test PCMA\nRoute::get('/pcma/test', function () {\n    return view('pcma.test-simple');\n})->name('pcma.test');\n";

// Vérifier si la route existe déjà
$webContent = file_get_contents('routes/web.php');
if (strpos($webContent, '/pcma/test') === false) {
    file_put_contents('routes/web.php', $testRoute, FILE_APPEND);
    echo "   ✅ Route de test ajoutée\n";
} else {
    echo "   ✅ Route de test existe déjà\n";
}

// 5. Nettoyer à nouveau
echo "\n🧹 5. Nettoyage final\n";
shell_exec('php artisan route:clear');
shell_exec('php artisan view:clear');

// 6. Instructions finales
echo "\n💡 6. Instructions de test\n";
echo "   🔄 Redémarrez le serveur Laravel:\n";
echo "      pkill -f 'php artisan serve'\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n\n";
echo "   🧪 Testez les routes:\n";
echo "      Test simple: http://localhost:8000/pcma/test\n";
echo "      Interface complète: http://localhost:8000/pcma/voice-fallback\n\n";
echo "   📊 Si le test simple fonctionne mais pas l'interface complète:\n";
echo "      Le problème vient du fichier de vue complexe\n";
echo "      Si aucun ne fonctionne: problème de configuration des routes\n\n";

echo "🎯 Résolution terminée !\n";
echo "Suivez les instructions ci-dessus pour tester.\n";
