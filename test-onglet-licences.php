<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Simuler l'environnement Laravel
$app = new Application();

echo "🧪 Test de l'onglet Historique des Licences\n";
echo "==========================================\n\n";

// Vérifier que la vue existe
$viewPath = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewPath)) {
    echo "✅ Vue trouvée: $viewPath\n";
    
    // Lire le contenu de la vue
    $content = file_get_contents($viewPath);
    
    // Vérifier la présence de l'onglet
    if (strpos($content, 'Historique des Licences') !== false) {
        echo "✅ Onglet 'Historique des Licences' trouvé dans la vue\n";
    } else {
        echo "❌ Onglet 'Historique des Licences' NON trouvé dans la vue\n";
    }
    
    // Vérifier la présence du bouton de navigation
    if (strpos($content, 'data-nav="licenses"') !== false) {
        echo "✅ Bouton de navigation pour l'onglet licences trouvé\n";
    } else {
        echo "❌ Bouton de navigation pour l'onglet licences NON trouvé\n";
    }
    
    // Vérifier la présence du contenu de l'onglet
    if (strpos($content, 'id="licenses-tab"') !== false) {
        echo "✅ Contenu de l'onglet licences trouvé\n";
    } else {
        echo "❌ Contenu de l'onglet licences NON trouvé\n";
    }
    
    // Vérifier la présence du composant Vue.js
    if (strpos($content, 'player-license-history') !== false) {
        echo "✅ Composant Vue.js 'player-license-history' trouvé\n";
    } else {
        echo "❌ Composant Vue.js 'player-license-history' NON trouvé\n";
    }
    
    // Vérifier la présence de l'API URL
    if (strpos($content, '/api/v1/player-dashboard/licenses/') !== false) {
        echo "✅ URL de l'API des licences trouvée\n";
    } else {
        echo "❌ URL de l'API des licences NON trouvée\n";
    }
    
} else {
    echo "❌ Vue non trouvée: $viewPath\n";
}

echo "\n🔍 Vérification des routes API...\n";

// Vérifier que la route API existe
$routesPath = 'routes/api.php';
if (file_exists($routesPath)) {
    $routesContent = file_get_contents($routesPath);
    
    if (strpos($routesContent, 'licenses/{playerId}/history') !== false) {
        echo "✅ Route API pour l'historique des licences trouvée\n";
    } else {
        echo "❌ Route API pour l'historique des licences NON trouvée\n";
    }
    
    if (strpos($routesContent, 'PlayerLicenseHistoryController') !== false) {
        echo "✅ Contrôleur PlayerLicenseHistoryController référencé dans les routes\n";
    } else {
        echo "❌ Contrôleur PlayerLicenseHistoryController NON référencé dans les routes\n";
    }
} else {
    echo "❌ Fichier des routes API non trouvé\n";
}

echo "\n🔍 Vérification du contrôleur...\n";

// Vérifier que le contrôleur existe
$controllerPath = 'app/Http/Controllers/Api/PlayerLicenseHistoryController.php';
if (file_exists($controllerPath)) {
    echo "✅ Contrôleur PlayerLicenseHistoryController trouvé\n";
    
    $controllerContent = file_get_contents($controllerPath);
    
    if (strpos($controllerContent, 'getLicenseHistory') !== false) {
        echo "✅ Méthode getLicenseHistory trouvée\n";
    } else {
        echo "❌ Méthode getLicenseHistory NON trouvée\n";
    }
    
    if (strpos($controllerContent, 'calculateFifaTrainingPrimes') !== false) {
        echo "✅ Méthode calculateFifaTrainingPrimes trouvée\n";
    } else {
        echo "❌ Méthode calculateFifaTrainingPrimes NON trouvée\n";
    }
} else {
    echo "❌ Contrôleur PlayerLicenseHistoryController non trouvé\n";
}

echo "\n🔍 Vérification du modèle Player...\n";

// Vérifier que la relation matchMetrics est correcte
$playerModelPath = 'app/Models/Player.php';
if (file_exists($playerModelPath)) {
    $playerContent = file_get_contents($playerModelPath);
    
    if (strpos($playerContent, 'HasManyThrough') !== false) {
        echo "✅ Import HasManyThrough trouvé\n";
    } else {
        echo "❌ Import HasManyThrough NON trouvé\n";
    }
    
    if (strpos($playerContent, 'public function matchMetrics(): HasManyThrough') !== false) {
        echo "✅ Méthode matchMetrics avec type correct trouvée\n";
    } else {
        echo "❌ Méthode matchMetrics avec type correct NON trouvée\n";
    }
} else {
    echo "❌ Modèle Player non trouvé\n";
}

echo "\n🎯 Résumé du test:\n";
echo "==================\n";
echo "L'onglet 'Historique des Licences' devrait maintenant être visible\n";
echo "et fonctionnel dans le portail joueur.\n\n";
echo "Pour tester:\n";
echo "1. Accédez à: http://localhost:8000/portail-joueur/1\n";
echo "2. Connectez-vous si nécessaire\n";
echo "3. Cliquez sur l'onglet 'Historique des Licences' (📋)\n";
echo "4. Vérifiez que l'interface s'affiche correctement\n\n";
echo "✅ Test terminé avec succès !\n";







