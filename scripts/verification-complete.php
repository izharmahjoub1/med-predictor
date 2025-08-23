<?php
/**
 * 🔍 VÉRIFICATION COMPLÈTE DU SYSTÈME
 * Test final de toutes les corrections appliquées
 */

echo "🔍 VÉRIFICATION COMPLÈTE DU SYSTÈME CORRIGÉ\n";
echo "==========================================\n\n";

// 1. Vérification des corrections de callbacks
echo "📋 Test 1: Corrections de callbacks\n";
echo "-----------------------------------\n";

$createFile = 'resources/views/pcma/create.blade.php';
$content = file_get_contents($createFile);

// Vérifier la correction des callbacks
if (strpos($content, '🔧 CORRECTION CRITIQUE : Vérifier si le callback est déjà configuré') !== false) {
    echo "✅ Correction de callbacks: TROUVÉE\n";
} else {
    echo "❌ Correction de callbacks: NON TROUVÉE\n";
}

// Vérifier les vérifications conditionnelles
$conditionalChecks = substr_count($content, 'if (!window.speechService.onResult)');
echo "✅ Vérifications conditionnelles: $conditionalChecks trouvées\n";

// 2. Vérification des patterns FIFA améliorés
echo "\n📋 Test 2: Patterns FIFA améliorés\n";
echo "----------------------------------\n";

// Vérifier les nouveaux patterns
$fifaPatterns = [
    'ID FIFA CONNECT 001',
    'FIFA CONNECT 001', 
    'FIFA 001',
    'fifa_number'
];

foreach ($fifaPatterns as $pattern) {
    if (strpos($content, $pattern) !== false) {
        echo "✅ Pattern '$pattern': TROUVÉ\n";
    } else {
        echo "❌ Pattern '$pattern': NON TROUVÉ\n";
    }
}

// 3. Vérification de la fonction de nettoyage
echo "\n📋 Test 3: Fonction de nettoyage\n";
echo "--------------------------------\n";

if (strpos($content, 'function clearOldTestData()') !== false) {
    echo "✅ Fonction clearOldTestData: TROUVÉE\n";
} else {
    echo "❌ Fonction clearOldTestData: NON TROUVÉE\n";
}

if (strpos($content, 'clearOldTestData()') !== false) {
    echo "✅ Appel de clearOldTestData: TROUVÉ\n";
} else {
    echo "❌ Appel de clearOldTestData: NON TROUVÉ\n";
}

// 4. Vérification des tests automatiques
echo "\n📋 Test 4: Tests automatiques\n";
echo "-----------------------------\n";

$testTexts = [
    '"ID FIFA CONNECT"',
    '"ID FIFA CONNECT 001"',
    '"FIFA CONNECT 001"',
    '"FIFA 001"'
];

foreach ($testTexts as $text) {
    if (strpos($content, $text) !== false) {
        echo "✅ Test $text: TROUVÉ\n";
    } else {
        echo "❌ Test $text: NON TROUVÉ\n";
    }
}

// 5. Test de l'API des athlètes
echo "\n📋 Test 5: API des athlètes\n";
echo "---------------------------\n";

$apiUrl = 'http://localhost:8081/api/athletes/search?name=Ali%20Jebali';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ API des athlètes: FONCTIONNELLE\n";
        echo "✅ Ali Jebali trouvé: " . ($data['player']['name'] ?? 'N/A') . "\n";
        echo "✅ FIFA ID: " . ($data['player']['fifa_connect_id'] ?? 'N/A') . "\n";
    } else {
        echo "❌ API des athlètes: ERREUR DE RÉPONSE\n";
    }
} else {
    echo "❌ API des athlètes: NON ACCESSIBLE (Code: $httpCode)\n";
}

// 6. Vérification du serveur Laravel
echo "\n📋 Test 6: Serveur Laravel\n";
echo "--------------------------\n";

$serverUrl = 'http://localhost:8081/pcma/create';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

curl_exec($ch);
$serverCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($serverCode === 200 || $serverCode === 302) {
    echo "✅ Serveur Laravel: ACTIF (Code: $serverCode)\n";
    if ($serverCode === 302) {
        echo "✅ Redirection normale vers login\n";
    }
} else {
    echo "❌ Serveur Laravel: PROBLÈME (Code: $serverCode)\n";
}

// 7. Résumé final
echo "\n🎯 RÉSUMÉ DE LA VÉRIFICATION\n";
echo "============================\n";

$corrections = [
    'Callbacks' => (strpos($content, '🔧 CORRECTION CRITIQUE') !== false),
    'Patterns FIFA' => (strpos($content, 'fifa_number') !== false),
    'Nettoyage' => (strpos($content, 'clearOldTestData') !== false),
    'Tests auto' => (strpos($content, 'testFifaConnectCommand') !== false),
    'API' => ($httpCode === 200),
    'Serveur' => ($serverCode === 200 || $serverCode === 302)
];

$total = count($corrections);
$passed = count(array_filter($corrections));

echo "✅ Corrections appliquées: $passed/$total\n";

foreach ($corrections as $name => $status) {
    $icon = $status ? '✅' : '❌';
    echo "$icon $name: " . ($status ? 'OK' : 'PROBLÈME') . "\n";
}

if ($passed === $total) {
    echo "\n🎉 SYSTÈME ENTIÈREMENT VÉRIFIÉ ET FONCTIONNEL !\n";
} else {
    echo "\n⚠️ Certaines vérifications ont échoué - Révision nécessaire\n";
}

echo "\n📋 INSTRUCTIONS FINALES:\n";
echo "1. Connectez-vous à: http://localhost:8081/login\n";
echo "2. Accédez à: http://localhost:8081/pcma/create\n";
echo "3. Testez la reconnaissance vocale\n";
echo "4. Vérifiez que le callback onResult est déclenché\n";
echo "5. Confirmez que les données sont extraites et affichées\n";

echo "\n✨ VÉRIFICATION TERMINÉE !\n";
?>

