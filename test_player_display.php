<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Player;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST AFFICHAGE INFORMATIONS JOUEUR ===\n\n";

// 1. Vérifier l'utilisateur de test
echo "1. Vérification de l'utilisateur de test...\n";
$user = User::find(5);
if ($user) {
    echo "✅ Utilisateur trouvé: {$user->name}\n";
} else {
    echo "❌ Utilisateur de test non trouvé\n";
    exit;
}

// 2. Vérifier les joueurs disponibles
echo "\n2. Vérification des joueurs disponibles...\n";
$players = Player::with('club')->get();
if ($players->count() > 0) {
    echo "✅ {$players->count()} joueur(s) trouvé(s)\n";
    foreach ($players as $player) {
        echo "   - {$player->full_name} (Club: " . ($player->club ? $player->club->name : 'N/A') . ")\n";
    }
} else {
    echo "❌ Aucun joueur trouvé\n";
    exit;
}

// 3. Tester l'API de récupération des données joueur
echo "\n3. Test de l'API de récupération des données joueur...\n";
$firstPlayer = $players->first();
$apiUrl = "http://localhost:8001/api/players/{$firstPlayer->id}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $playerData = json_decode($response, true);
    echo "✅ API fonctionne correctement\n";
    echo "   Données reçues:\n";
    echo "   - Nom: " . ($playerData['full_name'] ?? 'N/A') . "\n";
    echo "   - Date de naissance: " . ($playerData['date_of_birth'] ?? 'N/A') . "\n";
    echo "   - Club: " . (isset($playerData['club']) ? $playerData['club']['name'] : 'N/A') . "\n";
    echo "   - Position: " . ($playerData['position'] ?? 'N/A') . "\n";
    echo "   - Âge: " . ($playerData['age'] ?? 'N/A') . "\n";
    echo "   - Nationalité: " . ($playerData['nationality'] ?? 'N/A') . "\n";
} else {
    echo "❌ Erreur API: HTTP {$httpCode}\n";
    echo "   Réponse: {$response}\n";
}

// 4. Vérifier la vue de création
echo "\n4. Vérification de la vue de création...\n";
$viewPath = 'resources/views/health-records/create.blade.php';
if (file_exists($viewPath)) {
    echo "✅ Vue de création trouvée\n";
    
    // Vérifier la présence des éléments nécessaires
    $viewContent = file_get_contents($viewPath);
    
    $checks = [
        'player_id' => strpos($viewContent, 'player_id') !== false,
        'player-info' => strpos($viewContent, 'player-info') !== false,
        'player-full-name' => strpos($viewContent, 'player-full-name') !== false,
        'fetch' => strpos($viewContent, 'fetch') !== false,
        '/api/players/' => strpos($viewContent, '/api/players/') !== false
    ];
    
    foreach ($checks as $element => $found) {
        echo "   - {$element}: " . ($found ? "✅" : "❌") . "\n";
    }
} else {
    echo "❌ Vue de création non trouvée\n";
}

echo "\n=== FIN DU TEST ===\n";
echo "\nInstructions pour tester manuellement:\n";
echo "1. Allez sur http://localhost:8001/health-records/create\n";
echo "2. Connectez-vous avec test@example.com / password\n";
echo "3. Sélectionnez un joueur dans le dropdown\n";
echo "4. Vérifiez que les informations s'affichent dans la section bleue\n";
echo "5. Ouvrez la console du navigateur (F12) pour voir les erreurs JavaScript\n";
