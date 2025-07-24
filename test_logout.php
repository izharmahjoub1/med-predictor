<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Test des credentials des utilisateurs de test
echo "=== TEST DES CREDENTIALS ===\n";

$testUsers = [
    'john.doe@testfc.com' => 'password123',
    'admin@testfc.com' => 'password123',
    'manager@testfc.com' => 'password123',
    'medical@testfc.com' => 'password123'
];

foreach ($testUsers as $email => $password) {
    $user = User::where('email', $email)->first();
    
    if ($user) {
        $isValid = Hash::check($password, $user->password);
        echo "✅ {$email}: " . ($isValid ? "VALID" : "INVALID") . " (Role: {$user->role})\n";
    } else {
        echo "❌ {$email}: USER NOT FOUND\n";
    }
}

echo "\n=== TEST DE LA ROUTE LOGOUT ===\n";

// Test de la route logout
$logoutRoute = route('logout');
echo "Route logout: {$logoutRoute}\n";

// Vérifier si la route existe
$routes = app('router')->getRoutes();
$logoutExists = false;

foreach ($routes as $route) {
    if ($route->getName() === 'logout') {
        $logoutExists = true;
        echo "✅ Route logout trouvée: " . $route->methods()[0] . " " . $route->uri() . "\n";
        break;
    }
}

if (!$logoutExists) {
    echo "❌ Route logout non trouvée\n";
}

echo "\n=== INSTRUCTIONS POUR TESTER ===\n";
echo "1. Allez sur http://localhost:8000/login\n";
echo "2. Connectez-vous avec: admin@testfc.com / password123\n";
echo "3. Une fois connecté, cliquez sur le menu utilisateur (en haut à droite)\n";
echo "4. Cliquez sur 'Logout' ou 'Déconnexion'\n";
echo "5. Vous devriez être redirigé vers la page d'accueil\n";

echo "\n=== CREDENTIALS DE TEST ===\n";
echo "Player: john.doe@testfc.com / password123\n";
echo "Admin: admin@testfc.com / password123\n";
echo "Manager: manager@testfc.com / password123\n";
echo "Medical: medical@testfc.com / password123\n"; 