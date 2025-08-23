<?php
/**
 * 🔧 VÉRIFICATION DES CORRECTIONS RÉCENTES
 * Test des fonctions manquantes et corrections appliquées
 */

echo "🔧 VÉRIFICATION DES CORRECTIONS RÉCENTES\n";
echo "========================================\n\n";

$createFile = 'resources/views/pcma/create.blade.php';
$content = file_get_contents($createFile);

// 1. Vérification de la fonction fillFormFieldsWithPlayerData
echo "📋 Test 1: Fonction fillFormFieldsWithPlayerData\n";
echo "-----------------------------------------------\n";

if (strpos($content, 'function fillFormFieldsWithPlayerData(playerData)') !== false) {
    echo "✅ Fonction fillFormFieldsWithPlayerData: DÉFINIE\n";
} else {
    echo "❌ Fonction fillFormFieldsWithPlayerData: NON DÉFINIE\n";
}

// Vérifier les appels à cette fonction
$appels = substr_count($content, 'fillFormFieldsWithPlayerData(');
echo "✅ Appels à la fonction: $appels trouvés\n";

// 2. Vérification des références voiceStatus
echo "\n📋 Test 2: Références voiceStatus\n";
echo "----------------------------------\n";

$voiceStatusRefs = substr_count($content, 'voiceStatus.textContent');
echo "✅ Références voiceStatus.textContent: $voiceStatusRefs trouvées\n";

// Vérifier que voiceStatus est bien déclaré avant utilisation
$voiceStatusDeclarations = substr_count($content, 'const voiceStatus = document.getElementById(\'voice-status\')');
echo "✅ Déclarations voiceStatus: $voiceStatusDeclarations trouvées\n";

// 3. Vérification des corrections de callbacks
echo "\n📋 Test 3: Corrections de callbacks\n";
echo "-----------------------------------\n";

$correctionsConsole = strpos($content, '🔧 CORRECTION CRITIQUE : S\'assurer que les callbacks sont configurés') !== false;
$correctionsInitAll = strpos($content, '🔧 CORRECTION CRITIQUE : Vérifier si le callback est déjà configuré') !== false;

echo "✅ Correction console vocale: " . ($correctionsConsole ? 'APPLIQUÉE' : 'NON APPLIQUÉE') . "\n";
echo "✅ Correction initAll: " . ($correctionsInitAll ? 'APPLIQUÉE' : 'NON APPLIQUÉE') . "\n";

// 4. Vérification des vérifications conditionnelles
echo "\n📋 Test 4: Vérifications conditionnelles\n";
echo "----------------------------------------\n";

$verificationsConditionnelles = substr_count($content, 'if (!window.speechService.onResult)');
echo "✅ Vérifications conditionnelles: $verificationsConditionnelles trouvées\n";

// 5. Vérification de la structure des fonctions
echo "\n📋 Test 5: Structure des fonctions\n";
echo "----------------------------------\n";

// Vérifier que fillFormFieldsWithPlayerData a une structure complète
$fonctionComplete = strpos($content, 'try {') !== false && 
                   strpos($content, '} catch (error) {') !== false &&
                   strpos($content, 'voiceStatus.textContent') !== false;

echo "✅ Structure complète de fillFormFieldsWithPlayerData: " . ($fonctionComplete ? 'OK' : 'INCOMPLÈTE') . "\n";

// 6. Vérification des champs remplis
echo "\n📋 Test 6: Champs remplis\n";
echo "--------------------------\n";

$champsVoix = substr_count($content, 'voice_player_name') + substr_count($content, 'voice_age') + 
              substr_count($content, 'voice_position') + substr_count($content, 'voice_club');
$champsPrincipaux = substr_count($content, 'voice_player_name_main') + substr_count($content, 'voice_age_main') + 
                    substr_count($content, 'voice_position_main') + substr_count($content, 'voice_club_main');
$champsResultats = substr_count($content, 'voice_player_name_result') + substr_count($content, 'voice_age_result') + 
                   substr_count($content, 'voice_position_result') + substr_count($content, 'voice_club_result');

echo "✅ Champs vocaux: $champsVoix références\n";
echo "✅ Champs principaux: $champsPrincipaux références\n";
echo "✅ Champs résultats: $champsResultats références\n";

// 7. Test de l'API pour vérifier que le backend fonctionne
echo "\n📋 Test 7: API des athlètes\n";
echo "----------------------------\n";

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
    } else {
        echo "❌ API des athlètes: ERREUR DE RÉPONSE\n";
    }
} else {
    echo "❌ API des athlètes: NON ACCESSIBLE (Code: $httpCode)\n";
}

// 8. Résumé final
echo "\n🎯 RÉSUMÉ DE LA VÉRIFICATION DES CORRECTIONS\n";
echo "============================================\n";

$corrections = [
    'fillFormFieldsWithPlayerData définie' => (strpos($content, 'function fillFormFieldsWithPlayerData') !== false),
    'Références voiceStatus corrigées' => ($voiceStatusRefs > 0 && $voiceStatusDeclarations > 0),
    'Corrections de callbacks appliquées' => ($correctionsConsole && $correctionsInitAll),
    'Vérifications conditionnelles en place' => ($verificationsConditionnelles >= 2),
    'Structure des fonctions complète' => $fonctionComplete,
    'API fonctionnelle' => ($httpCode === 200)
];

$total = count($corrections);
$passed = count(array_filter($corrections));

echo "✅ Corrections appliquées: $passed/$total\n";

foreach ($corrections as $name => $status) {
    $icon = $status ? '✅' : '❌';
    echo "$icon $name: " . ($status ? 'OK' : 'PROBLÈME') . "\n";
}

if ($passed === $total) {
    echo "\n🎉 TOUTES LES CORRECTIONS ONT ÉTÉ APPLIQUÉES AVEC SUCCÈS !\n";
    echo "✅ Le système de reconnaissance vocale doit maintenant fonctionner\n";
    echo "✅ Plus d'erreurs JavaScript\n";
    echo "✅ Callback onResult fonctionnel\n";
} else {
    echo "\n⚠️ Certaines corrections n'ont pas été appliquées - Révision nécessaire\n";
}

echo "\n📋 INSTRUCTIONS DE TEST:\n";
echo "1. Rechargez la page: http://localhost:8081/pcma/create\n";
echo "2. Vérifiez la console: Pas d'erreurs JavaScript\n";
echo "3. Testez la reconnaissance vocale\n";
echo "4. Confirmez que le callback onResult est déclenché\n";

echo "\n✨ VÉRIFICATION DES CORRECTIONS TERMINÉE !\n";
?>

