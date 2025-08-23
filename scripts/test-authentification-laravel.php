<?php
/**
 * Test de l'authentification Laravel et accès à la page PCMA
 */

echo "🧪 TEST DE L'AUTHENTIFICATION LARAVEL ET ACCÈS PCMA\n";
echo "==================================================\n\n";

$baseUrl = 'http://localhost:8081';

// Test 1: Vérifier que le serveur Laravel fonctionne
echo "📋 Test 1: Serveur Laravel\n";
echo "---------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo $httpCode === 200 ? "✅ Serveur Laravel fonctionnel\n\n" : "❌ Serveur Laravel inaccessible\n\n";

// Test 2: Vérifier l'accès à la page de connexion
echo "🔐 Test 2: Page de connexion\n";
echo "-----------------------------\n";

$loginUrl = "$baseUrl/login";
echo "URL: $loginUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $loginHttpCode\n";

if ($loginHttpCode === 200) {
    echo "✅ Page de connexion accessible\n";
    
    // Vérifier la présence des éléments de connexion
    $loginElements = [
        'Formulaire de connexion' => strpos($loginResponse, 'form') !== false,
        'Champ email' => strpos($loginResponse, 'email') !== false || strpos($loginResponse, 'Email') !== false,
        'Champ mot de passe' => strpos($loginResponse, 'password') !== false || strpos($loginResponse, 'Password') !== false,
        'Bouton de connexion' => strpos($loginResponse, 'submit') !== false || strpos($loginResponse, 'Connexion') !== false
    ];
    
    echo "🔍 Éléments de connexion:\n";
    foreach ($loginElements as $element => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $element\n";
    }
    
} else {
    echo "❌ Page de connexion inaccessible\n";
}
echo "\n";

// Test 3: Vérifier l'accès à la page PCMA (sans authentification)
echo "📄 Test 3: Accès PCMA sans authentification\n";
echo "-------------------------------------------\n";

$pcmaUrl = "$baseUrl/pcma/create";
echo "URL: $pcmaUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pcmaUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$pcmaResponse = curl_exec($ch);
$pcmaHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $pcmaHttpCode\n";
echo "URL finale: $finalUrl\n";

if (strpos($finalUrl, 'login') !== false) {
    echo "🔐 Redirection vers login détectée (normal)\n";
    echo "✅ Protection d'authentification active\n\n";
    
    echo "🎯 DIAGNOSTIC:\n";
    echo "La page PCMA est protégée par l'authentification Laravel.\n";
    echo "C'est NORMAL et SÉCURISÉ.\n\n";
    
} elseif (strpos($finalUrl, 'pcma/create') !== false) {
    echo "⚠️ Page PCMA accessible sans authentification\n";
    echo "❌ Problème de sécurité détecté\n\n";
    
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier les middlewares d'authentification\n";
    echo "2. Vérifier les routes protégées\n";
    echo "3. Activer la protection d'authentification\n\n";
    
} else {
    echo "❌ Comportement inattendu\n";
    echo "URL finale: $finalUrl\n\n";
}

// Test 4: Vérifier les routes d'authentification
echo "🛣️ Test 4: Routes d'authentification\n";
echo "-----------------------------------\n";

$authRoutes = [
    'Login' => "$baseUrl/login",
    'Register' => "$baseUrl/register",
    'Logout' => "$baseUrl/logout",
    'Dashboard' => "$baseUrl/dashboard",
    'Home' => "$baseUrl/home"
];

foreach ($authRoutes as $name => $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');
    
    curl_exec($ch);
    $routeHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $routeHttpCode === 200 ? '✅' : ($routeHttpCode === 404 ? '❌' : '⚠️');
    echo "   $status $name ($routeHttpCode): $url\n";
}

echo "\n";

// Test 5: Vérifier la base de données des utilisateurs
echo "👥 Test 5: Base de données utilisateurs\n";
echo "--------------------------------------\n";

echo "🔍 Vérification de la table users...\n";

// Essayer de vérifier via une commande artisan
$output = shell_exec("cd " . escapeshellarg(__DIR__ . '/..') . " && php artisan tinker --execute='echo User::count();' 2>&1");

if ($output !== null && is_numeric(trim($output))) {
    $userCount = (int)trim($output);
    echo "✅ Table users accessible\n";
    echo "📊 Nombre d'utilisateurs: $userCount\n";
    
    if ($userCount === 0) {
        echo "⚠️ Aucun utilisateur dans la base\n";
        echo "🔧 Créez un utilisateur avec:\n";
        echo "   php artisan tinker\n";
        echo "   User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => Hash::make('password123')]);\n";
    } else {
        echo "✅ Utilisateurs disponibles pour la connexion\n";
    }
    
} else {
    echo "❌ Impossible d'accéder à la table users\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}
echo "\n";

// Résumé final
echo "📊 RÉSUMÉ FINAL\n";
echo "===============\n";

$status = 'unknown';
if ($httpCode === 200 && strpos($finalUrl, 'login') !== false) {
    $status = 'secure';
    echo "🎉 SYSTÈME SÉCURISÉ ET FONCTIONNEL !\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "✅ Page de connexion: OK\n";
    echo "✅ Protection PCMA: OK (redirection vers login)\n";
    echo "✅ Authentification: Active et sécurisée\n\n";
    
    echo "🎯 INSTRUCTIONS POUR ACCÉDER AU MODE VOCAL:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $baseUrl/pcma/create\n";
    echo "4. Testez le Mode Vocal et la reconnaissance vocale\n\n";
    
} elseif ($httpCode === 200 && strpos($finalUrl, 'pcma/create') !== false) {
    $status = 'insecure';
    echo "🚨 PROBLÈME DE SÉCURITÉ DÉTECTÉ !\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "❌ Protection PCMA: DÉSACTIVÉE\n";
    echo "⚠️ La page PCMA est accessible sans authentification\n\n";
    
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Activer l'authentification sur la route PCMA\n";
    echo "2. Vérifier les middlewares d'authentification\n";
    echo "3. Protéger la page PCMA\n\n";
    
} else {
    $status = 'issues';
    echo "❌ PROBLÈMES DÉTECTÉS\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier la configuration de la base de données\n\n";
}

echo "Status: $status\n";
echo "✨ Test terminé !\n";
?>

