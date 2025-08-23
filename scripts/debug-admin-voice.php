<?php
/**
 * Debug Admin Voice - Problème persistant même en tant qu'admin
 */

echo "=== Debug Admin Voice - Problème Persistant ===\n\n";

echo "🔐 **STATUT** : Vous êtes connecté en tant que system admin\n";
echo "❌ **PROBLÈME** : La page vocale ne fonctionne toujours pas\n\n";

echo "🔍 **ANALYSE APPROFONDIE** :\n\n";

// Test 1 : Vérifier la page de base
echo "1. Test de la page de base :\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/pcma/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    echo "   📏 Taille de la réponse : " . strlen($response) . " caractères\n";
    
    // Analyser le contenu
    if (strpos($response, 'Nouveau PCMA') !== false) {
        echo "   ✅ Page PCMA détectée\n";
    } else {
        echo "   ❌ Page PCMA NON détectée\n";
    }
    
    if (strpos($response, 'Google Assistant PCMA') !== false) {
        echo "   ✅ Éléments vocaux détectés\n";
    } else {
        echo "   ❌ Éléments vocaux NON détectés\n";
    }
    
    // Vérifier la structure
    if (strpos($response, 'voice-section') !== false) {
        echo "   ✅ Section vocale trouvée\n";
    } else {
        echo "   ❌ Section vocale NON trouvée\n";
    }
    
    if (strpos($response, 'start-voice-btn') !== false) {
        echo "   ✅ Bouton vocal trouvé\n";
    } else {
        echo "   ❌ Bouton vocal NON trouvé\n";
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
    echo "   📍 Redirection vers : $finalUrl\n";
}

// Test 2 : Vérifier les routes
echo "\n2. Vérification des routes :\n";

$routes = [
    'http://localhost:8080/pcma/create' => 'Création PCMA',
    'http://localhost:8080/pcma' => 'Dashboard PCMA',
    'http://localhost:8080/login' => 'Page de login',
    'http://localhost:8080/' => 'Page d\'accueil'
];

foreach ($routes as $url => $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   $description ($url) : HTTP $httpCode\n";
}

// Test 3 : Vérifier les logs Laravel
echo "\n3. Vérification des logs Laravel :\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "   ✅ Fichier de log trouvé\n";
    
    // Lire les dernières lignes
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -10);
    
    echo "   📋 10 dernières lignes du log :\n";
    foreach ($recentLines as $line) {
        if (trim($line) !== '') {
            echo "      " . trim($line) . "\n";
        }
    }
} else {
    echo "   ❌ Fichier de log non trouvé\n";
}

// Test 4 : Vérifier la configuration
echo "\n4. Vérification de la configuration :\n";

$configFile = 'config/app.php';
if (file_exists($configFile)) {
    echo "   ✅ Fichier de config trouvé\n";
    
    $configContent = file_get_contents($configFile);
    if (strpos($configContent, 'debug') !== false) {
        echo "   🔧 Mode debug détecté dans la config\n";
    }
} else {
    echo "   ❌ Fichier de config non trouvé\n";
}

// Test 5 : Vérifier les vues
echo "\n5. Vérification des vues :\n";

$viewFile = 'resources/views/pcma/create.blade.php';
if (file_exists($viewFile)) {
    echo "   ✅ Fichier de vue trouvé\n";
    
    $viewContent = file_get_contents($viewFile);
    $viewSize = strlen($viewContent);
    echo "   📏 Taille du fichier de vue : $viewSize caractères\n";
    
    // Vérifier la présence de nos éléments
    $elements = [
        'Google Assistant PCMA' => 'Titre Google Assistant',
        'Commencer l\'examen PCMA' => 'Bouton de démarrage',
        'voice-section' => 'Section vocale',
        'start-voice-btn' => 'Bouton vocal',
        'initSpeechRecognition' => 'Fonction JS'
    ];
    
    echo "   🔍 Éléments dans le fichier de vue :\n";
    foreach ($elements as $element => $description) {
        if (strpos($viewContent, $element) !== false) {
            echo "      ✅ $description : TROUVÉ\n";
        } else {
            echo "      ❌ $description : NON TROUVÉ\n";
        }
    }
    
} else {
    echo "   ❌ Fichier de vue non trouvé\n";
}

echo "\n=== DIAGNOSTIC ===\n";
echo "🔍 **Problèmes potentiels identifiés** :\n";
echo "1. La vue peut ne pas être compilée correctement\n";
echo "2. Il peut y avoir une erreur dans le contrôleur\n";
echo "3. Il peut y avoir un problème de cache des vues\n";
echo "4. Il peut y avoir une erreur JavaScript qui bloque le rendu\n";
echo "5. Il peut y avoir un conflit avec d'autres composants\n\n";

echo "📋 **Solutions à essayer** :\n";
echo "1. Vider le cache des vues : php artisan view:clear\n";
echo "2. Vider le cache de l'application : php artisan cache:clear\n";
echo "3. Vérifier la console du navigateur (F12) pour les erreurs JS\n";
echo "4. Vérifier les logs Laravel pour les erreurs PHP\n";
echo "5. Tester avec un navigateur en mode incognito\n";
echo "6. Vérifier que le contrôleur PCMA fonctionne correctement\n\n";

echo "🎯 **Prochaines étapes** :\n";
echo "1. Exécuter : php artisan view:clear\n";
echo "2. Exécuter : php artisan cache:clear\n";
echo "3. Redémarrer le serveur\n";
echo "4. Tester à nouveau la page\n";
echo "5. Vérifier la console du navigateur\n\n";

echo "🚨 **Si le problème persiste** :\n";
echo "- Vérifier la console du navigateur (F12)\n";
echo "- Vérifier les logs Laravel\n";
echo "- Tester avec un autre navigateur\n";
echo "- Vérifier les permissions des fichiers\n\n";

echo "🎉 **Objectif** : Rendre l'assistant vocal PCMA fonctionnel !\n";
?>

