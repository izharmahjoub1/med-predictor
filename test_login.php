<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test d'authentification ===\n\n";

// Test 1: Vérifier les utilisateurs admin
echo "1. Utilisateurs system_admin disponibles:\n";

try {
    $adminUsers = User::where('role', 'system_admin')->limit(5)->get();
    
    foreach ($adminUsers as $user) {
        echo "  - {$user->name} ({$user->email})\n";
        
        // Tester le mot de passe
        if (Hash::check('password', $user->password)) {
            echo "    ✓ Mot de passe 'password' valide\n";
        } else {
            echo "    ✗ Mot de passe 'password' invalide\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 2: Vérifier la configuration d'authentification
echo "\n2. Configuration d'authentification:\n";

try {
    $authConfig = config('auth');
    echo "  - Driver par défaut: " . $authConfig['defaults']['guard'] . "\n";
    echo "  - Provider par défaut: " . $authConfig['defaults']['passwords'] . "\n";
    echo "  - Guards disponibles: " . implode(', ', array_keys($authConfig['guards'])) . "\n";
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier la page de connexion
echo "\n3. Test de la page de connexion:\n";

try {
    $response = file_get_contents('http://localhost:8000/login');
    if (strpos($response, 'login') !== false || strpos($response, 'Login') !== false) {
        echo "  ✓ Page de connexion accessible\n";
    } else {
        echo "  ✗ Page de connexion non accessible\n";
    }
} catch (Exception $e) {
    echo "  ✗ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Instructions de connexion ===\n";
echo "Essayez de vous connecter avec l'un de ces comptes:\n\n";

if (isset($adminUsers)) {
    foreach ($adminUsers as $user) {
        echo "Email: {$user->email}\n";
        echo "Mot de passe: password\n";
        echo "Nom: {$user->name}\n";
        echo "Rôle: {$user->role}\n\n";
    }
}

echo "Si la connexion ne fonctionne toujours pas:\n";
echo "1. Vérifiez que le serveur fonctionne: http://localhost:8000\n";
echo "2. Essayez de vider le cache du navigateur\n";
echo "3. Vérifiez la console du navigateur (F12) pour les erreurs\n";
echo "4. Essayez un autre navigateur\n\n";

echo "URLs à tester:\n";
echo "- Page de connexion: http://localhost:8000/login\n";
echo "- Dashboard (après connexion): http://localhost:8000/dashboard\n"; 