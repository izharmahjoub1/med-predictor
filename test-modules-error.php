<?php
echo "=== Diagnostic Erreur 500 /modules/ ===\n";

// Test 1: Vérifier l'accès au serveur
echo "1. Test d'accès au serveur...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Serveur Laravel accessible (HTTP $httpCode)\n";
} else {
    echo "❌ Serveur Laravel non accessible (HTTP $httpCode)\n";
    return;
}

// Test 2: Vérifier la route modules.index
echo "\n2. Vérification de la route modules.index...\n";
$routesContent = file_get_contents('routes/web.php');
if (strpos($routesContent, 'modules.index') !== false) {
    echo "✅ Route modules.index trouvée\n";
} else {
    echo "❌ Route modules.index manquante\n";
}

// Test 3: Vérifier la vue modules.index
echo "\n3. Vérification de la vue modules.index...\n";
if (file_exists('resources/views/modules/index.blade.php')) {
    $size = filesize('resources/views/modules/index.blade.php');
    echo "✅ Vue modules.index existe ($size bytes)\n";
    
    // Vérifier si elle utilise $footballType
    $content = file_get_contents('resources/views/modules/index.blade.php');
    if (strpos($content, '$footballType') !== false) {
        echo "❌ Vue utilise \$footballType (PROBLÈME)\n";
    } else {
        echo "✅ Vue n'utilise pas \$footballType\n";
    }
} else {
    echo "❌ Vue modules.index manquante\n";
}

// Test 4: Vérifier le layout app
echo "\n4. Vérification du layout app...\n";
if (file_exists('resources/views/layouts/app.blade.php')) {
    $size = filesize('resources/views/layouts/app.blade.php');
    echo "✅ Layout app existe ($size bytes)\n";
    
    // Vérifier si il utilise $footballType
    $content = file_get_contents('resources/views/layouts/app.blade.php');
    if (strpos($content, '$footballType') !== false) {
        echo "❌ Layout utilise \$footballType (PROBLÈME)\n";
    } else {
        echo "✅ Layout n'utilise pas \$footballType\n";
    }
} else {
    echo "❌ Layout app manquant\n";
}

// Test 5: Vérifier les vues qui utilisent $footballType
echo "\n5. Vérification des vues utilisant \$footballType...\n";
$viewsWithFootballType = [
    'resources/views/modules/licenses/index.blade.php' => 'Licenses',
    'resources/views/modules/medical/index.blade.php' => 'Medical',
    'resources/views/modules/healthcare/index.blade.php' => 'Healthcare'
];

foreach ($viewsWithFootballType as $file => $description) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, '$footballType') !== false) {
            echo "✅ $description: Utilise \$footballType\n";
        } else {
            echo "❌ $description: N'utilise pas \$footballType\n";
        }
    } else {
        echo "❌ $description: Fichier manquant\n";
    }
}

// Test 6: Vérifier les routes qui passent $footballType
echo "\n6. Vérification des routes passant \$footballType...\n";
$routesWithFootballType = [
    'modules.licenses.index' => 'Licenses',
    'modules.healthcare.index' => 'Healthcare',
    'modules.medical.index' => 'Medical'
];

foreach ($routesWithFootballType as $route => $description) {
    if (strpos($routesContent, $route) !== false) {
        echo "✅ $description: Route définie\n";
        
        // Vérifier si la route passe $footballType
        if (strpos($routesContent, "'footballType' =>") !== false || strpos($routesContent, 'compact(\'footballType\')') !== false) {
            echo "   ✅ Passe \$footballType\n";
        } else {
            echo "   ❌ Ne passe pas \$footballType\n";
        }
    } else {
        echo "❌ $description: Route manquante\n";
    }
}

// Test 7: Vérifier les logs d'erreur récents
echo "\n7. Vérification des logs d'erreur...\n";
if (file_exists('storage/logs/laravel.log')) {
    $logContent = file_get_contents('storage/logs/laravel.log');
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -50);
    
    $errorFound = false;
    foreach ($recentLines as $line) {
        if (strpos($line, 'footballType') !== false || strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
            echo "⚠️  Log d'erreur trouvé: " . trim($line) . "\n";
            $errorFound = true;
        }
    }
    
    if (!$errorFound) {
        echo "✅ Aucune erreur récente trouvée dans les logs\n";
    }
} else {
    echo "❌ Fichier de log manquant\n";
}

echo "\n=== Résumé du Diagnostic ===\n";
echo "🔍 Problème identifié:\n";
echo "   - Erreur 500 sur /modules/\n";
echo "   - Variable \$footballType non définie\n";
echo "   - Vue compilée avec erreur\n";

echo "\n✅ Solutions possibles:\n";
echo "   1. Vérifier que la route modules.index passe \$footballType\n";
echo "   2. Vérifier que toutes les vues utilisant \$footballType reçoivent la variable\n";
echo "   3. Nettoyer le cache des vues\n";
echo "   4. Vérifier les redirections automatiques\n";

echo "\n=== Instructions de Correction ===\n";
echo "🔧 Pour corriger l'erreur:\n";
echo "1. Vérifiez que la route modules.index passe \$footballType\n";
echo "2. Exécutez: php artisan view:clear\n";
echo "3. Exécutez: php artisan cache:clear\n";
echo "4. Testez: http://localhost:8000/modules/\n";

echo "\n🎯 Le problème semble être que la page /modules/ utilise une vue\n";
echo "   qui nécessite \$footballType mais ne la reçoit pas.\n";
?> 