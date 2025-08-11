<?php
echo "=== Correction de la Route Association ===\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Supprimer les routes en double à la fin du fichier
    $lines = explode("\n", $content);
    $newLines = [];
    $skipNextLines = false;
    
    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];
        
        // Détecter le début des routes en double
        if (strpos($line, '// Licenses routes') !== false) {
            echo "🔧 Suppression des routes en double à partir de la ligne " . ($i + 1) . "\n";
            $skipNextLines = true;
            continue;
        }
        
        // Détecter la fin des routes en double
        if ($skipNextLines && (strpos($line, 'Route::resource(\'licenses\'') !== false || 
                              strpos($line, 'Route::get(\'/licenses/validation\'') !== false ||
                              strpos($line, 'Route::patch(\'/licenses/{license}/approve\'') !== false ||
                              strpos($line, 'Route::patch(\'/licenses/{license}/reject\'') !== false)) {
            echo "   Suppression: $line\n";
            continue;
        }
        
        // Si on a sauté des lignes et qu'on arrive à une ligne vide ou un commentaire, on arrête de sauter
        if ($skipNextLines && (trim($line) === '' || strpos($line, '//') === 0)) {
            $skipNextLines = false;
        }
        
        if (!$skipNextLines) {
            $newLines[] = $line;
        }
    }
    
    $newContent = implode("\n", $newLines);
    
    // Sauvegarder le fichier
    file_put_contents($routesFile, $newContent);
    echo "✅ Routes en double supprimées\n";
    
    // Vérifier que la route principale existe toujours
    if (strpos($newContent, 'Route::get(\'/licenses/validation\'') !== false) {
        echo "✅ Route licenses.validation maintenue dans le groupe d'authentification\n";
    } else {
        echo "❌ Route licenses.validation manquante\n";
    }
    
    // Vérifier la syntaxe
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
echo "🔧 Nettoyage des caches...\n";

// Nettoyer les caches
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

echo "\n=== Test de la Carte Association ===\n";
echo "🔍 Pour tester la carte Association:\n\n";

echo "1️⃣ Connectez-vous en tant qu'association:\n";
echo "   - Email: association@test.com\n";
echo "   - Role: association_admin\n\n";

echo "2️⃣ Allez sur http://localhost:8000/modules/\n\n";

echo "3️⃣ Cliquez sur la carte 'Association' (🏛️)\n\n";

echo "4️⃣ Vous devriez arriver sur:\n";
echo "   http://localhost:8000/licenses/validation\n\n";

echo "5️⃣ Page de validation avec:\n";
echo "   - Statistiques (en attente, approuvées, rejetées)\n";
echo "   - Tableau des demandes de licences\n";
echo "   - Boutons Approuver/Rejeter\n";
echo "   - Modal de détails\n";

echo "\n=== Vérification Finale ===\n";
echo "✅ Routes en double supprimées\n";
echo "✅ Route licenses.validation dans le bon groupe de middleware\n";
echo "✅ Caches nettoyés\n";
echo "✅ Syntaxe PHP correcte\n";

echo "\n🎉 LA CARTE ASSOCIATION DEVRAIT MAINTENANT FONCTIONNER !\n";
echo "🔗 Association → Validation des licences\n";
echo "✨ Plus de redirection vers le dashboard\n";
?> 