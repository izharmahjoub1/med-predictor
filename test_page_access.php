<?php

echo "=== TEST ACCÈS PAGE CRÉATION ===\n\n";

// Test de la page de création
$url = "http://localhost:8001/health-records/create";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "URL testée: {$url}\n";
echo "Code HTTP: {$httpCode}\n";

if ($httpCode === 302) {
    echo "⚠️  Redirection détectée (probablement vers login)\n";
    echo "Cela signifie que l'authentification est requise.\n";
} elseif ($httpCode === 200) {
    echo "✅ Page accessible\n";
} else {
    echo "❌ Erreur d'accès\n";
}

echo "\nInstructions pour tester:\n";
echo "1. Ouvrez votre navigateur\n";
echo "2. Allez sur http://localhost:8001/health-records/create\n";
echo "3. Connectez-vous avec test@example.com / password\n";
echo "4. Sélectionnez un joueur dans le dropdown\n";
echo "5. Ouvrez la console (F12) et regardez les logs\n";
echo "6. Dites-moi ce que vous voyez dans la console\n";
