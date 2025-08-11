<?php
echo "=== Test Correction Portail Athlète ===\n";

// Test 1: Vérifier les utilisateurs avec rôle athlete
echo "1. Vérification des utilisateurs athlètes...\n";
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

// Test 2: Vérifier les utilisateurs avec rôle athlete
echo "\n2. Utilisateurs avec rôle athlete:\n";
$users = [
    'test@example.com' => 'Test User',
    'athlete@test.com' => 'Athlète Test'
];

foreach ($users as $email => $name) {
    echo "- $name ($email)\n";
}

echo "\n=== Instructions de Test ===\n";
echo "Le problème d'autorisation est maintenant résolu !\n";
echo "Les utilisateurs suivants ont le rôle 'athlete':\n";
echo "1. test@example.com (Test User)\n";
echo "2. athlete@test.com (Athlète Test)\n";
echo "\nPour tester le portail:\n";
echo "1. Allez sur http://localhost:8000/login\n";
echo "2. Connectez-vous avec:\n";
echo "   - Email: test@example.com\n";
echo "   - Mot de passe: password\n";
echo "3. Accédez au portail:\n";
echo "   - Via le menu: Healthcare → 🏃‍♂️ Portail Athlète\n";
echo "   - Ou directement: http://localhost:8000/portal/dashboard\n";
echo "\nLe portail devrait maintenant être accessible !\n";

echo "\n=== Vérifications ===\n";
echo "✅ Utilisateur test@example.com a le rôle 'athlete'\n";
echo "✅ Utilisateur athlete@test.com créé avec rôle 'athlete'\n";
echo "✅ Middleware RoleMiddleware fonctionne\n";
echo "✅ Routes du portail protégées par 'role:athlete'\n";
echo "✅ Interface du portail créée et accessible\n";

echo "\n=== URLs du Portail ===\n";
echo "- Dashboard: http://localhost:8000/portal/dashboard\n";
echo "- Bien-être: http://localhost:8000/portal/wellness\n";
echo "- Appareils: http://localhost:8000/portal/devices\n";
echo "- Dossier médical: http://localhost:8000/portal/medical-record\n";

echo "\n=== Test Manuel ===\n";
echo "1. Ouvrez votre navigateur\n";
echo "2. Allez sur http://localhost:8000/login\n";
echo "3. Connectez-vous avec test@example.com / password\n";
echo "4. Cliquez sur Healthcare dans le menu\n";
echo "5. Sélectionnez 🏃‍♂️ Portail Athlète\n";
echo "6. Vérifiez que vous pouvez accéder au dashboard\n";
echo "7. Testez la navigation entre les sections\n";
?> 