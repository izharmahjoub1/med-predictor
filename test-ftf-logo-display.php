<?php
/**
 * Test de l'affichage du logo FTF
 */

echo "🏆 TEST D'AFFICHAGE DU LOGO FTF\n";
echo "================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier l'association FTF
echo "🏆 TEST 1: ASSOCIATION FTF\n";
echo "===========================\n";

$stmt = $db->prepare("SELECT id, name, association_logo_url, logo_image FROM associations WHERE name LIKE '%FTF%'");
$stmt->execute();
$ftf = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ftf) {
    echo "✅ Association FTF trouvée : {$ftf['name']} (ID: {$ftf['id']})\n";
    echo "   Logo URL : {$ftf['association_logo_url']}\n";
    echo "   Logo Image : {$ftf['logo_image']}\n";
    
    // Test d'accessibilité du logo
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ftf['association_logo_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ Logo accessible (HTTP {$httpCode})\n";
        echo "   📁 Type de contenu : {$contentType}\n";
    } else {
        echo "   ❌ Logo non accessible (HTTP {$httpCode})\n";
    }
} else {
    echo "❌ Association FTF non trouvée\n";
}

echo "\n";

// Test 2: Vérifier les joueurs avec association FTF
echo "👥 TEST 2: JOUEURS AVEC ASSOCIATION FTF\n";
echo "=======================================\n";

$stmt = $db->prepare("
    SELECT p.id, p.first_name, p.last_name, p.nationality, 
           a.name as association_name, a.association_logo_url
    FROM players p 
    LEFT JOIN associations a ON p.association_id = a.id 
    WHERE a.name LIKE '%FTF%'
    ORDER BY p.id
    LIMIT 5
");
$stmt->execute();
$ftfPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($ftfPlayers) > 0) {
    echo "✅ Joueurs avec association FTF trouvés : " . count($ftfPlayers) . "\n\n";
    
    foreach ($ftfPlayers as $player) {
        echo "👤 {$player['first_name']} {$player['last_name']} (ID: {$player['id']})\n";
        echo "   🌍 Nationalité : {$player['nationality']}\n";
        echo "   🏆 Association : {$player['association_name']}\n";
        echo "   🏆 Logo URL : {$player['association_logo_url']}\n";
        echo "\n";
    }
} else {
    echo "❌ Aucun joueur avec association FTF trouvé\n";
}

echo "\n";

// Test 3: Vérifier la vue modifiée
echo "📱 TEST 3: VÉRIFICATION DE LA VUE\n";
echo "==================================\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé\n";
    
    $content = file_get_contents($viewFile);
    
    // Vérifications spécifiques au logo FTF
    $checks = [
        'Logo FTF avec image' => strpos($content, 'association_logo_url') !== false,
        'Fallback FTF en texte' => strpos($content, 'Fallback avec FTF en texte') !== false,
        'Gestion d\'erreur des images' => strpos($content, 'onerror=') !== false,
        'Affichage conditionnel FTF' => strpos($content, 'str_contains(strtolower($player->association->name), \'ftf\')') !== false
    ];
    
    foreach ($checks as $check => $result) {
        echo "   " . ($result ? "✅" : "❌") . " {$check}\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}

echo "\n";

// Test 4: Simulation de l'affichage HTML
echo "🎨 TEST 4: SIMULATION DE L'AFFICHAGE HTML\n";
echo "=========================================\n";

if (isset($ftf) && $ftf['association_logo_url']) {
    echo "🏆 Logo FTF simulé :\n";
    echo "   <img src=\"{$ftf['association_logo_url']}\" alt=\"Logo FTF\" class=\"w-12 h-12 object-contain rounded-lg shadow-sm mb-2\">\n";
    echo "   ✅ Ce code HTML devrait afficher le logo FTF\n";
} else {
    echo "❌ Impossible de simuler l'affichage - logo FTF manquant\n";
}

echo "\n";

// Test 5: Vérification des données pour le joueur ID 122
echo "🎯 TEST 5: VÉRIFICATION SPÉCIFIQUE JOUEUR ID 122\n";
echo "=================================================\n";

$playerId = 122;
$stmt = $db->prepare("
    SELECT p.*, c.name as club_name, c.logo_url, 
           a.name as association_name, a.association_logo_url, a.country as association_country
    FROM players p 
    LEFT JOIN clubs c ON p.club_id = c.id 
    LEFT JOIN associations a ON p.association_id = a.id 
    WHERE p.id = ?
");
$stmt->execute([$playerId]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

if ($player) {
    echo "✅ Joueur trouvé : {$player['first_name']} {$player['last_name']}\n";
    echo "   🌍 Nationalité : {$player['nationality']}\n";
    echo "   🏟️ Club : {$player['club_name']} (ID: {$player['club_id']})\n";
    echo "   🏟️ Logo Club : {$player['logo_url']}\n";
    echo "   🏆 Association : {$player['association_name']} (ID: {$player['association_id']})\n";
    echo "   🏆 Logo Association : {$player['association_logo_url']}\n";
    echo "   🏆 Pays Association : {$player['association_country']}\n";
    
    // Vérifier que toutes les données nécessaires sont présentes
    $missingData = [];
    if (empty($player['association_name'])) $missingData[] = 'Nom de l\'association';
    if (empty($player['association_logo_url'])) $missingData[] = 'Logo de l\'association';
    if (empty($player['association_country'])) $missingData[] = 'Pays de l\'association';
    
    if (empty($missingData)) {
        echo "   ✅ Toutes les données nécessaires sont présentes\n";
    } else {
        echo "   ❌ Données manquantes : " . implode(', ', $missingData) . "\n";
    }
} else {
    echo "❌ Joueur ID {$playerId} non trouvé\n";
}

echo "\n";

// RÉSUMÉ FINAL
echo "🎯 RÉSUMÉ DU TEST FTF\n";
echo "======================\n";

$totalChecks = 5;
$passedChecks = 0;

// Calculer les vérifications réussies
if (isset($ftf) && $ftf['association_logo_url']) $passedChecks++;
if (count($ftfPlayers) > 0) $passedChecks++;
if (file_exists($viewFile)) $passedChecks++;
if (isset($ftf) && $ftf['association_logo_url']) $passedChecks++;
if (isset($player) && $player['association_logo_url']) $passedChecks++;

echo "📊 Vérifications réussies : {$passedChecks}/{$totalChecks}\n";
echo "🏆 Logo FTF : " . (isset($ftf) && $ftf['association_logo_url'] ? "✅ Configuré" : "❌ Non configuré") . "\n";
echo "👥 Joueurs FTF : " . count($ftfPlayers) . " trouvés\n";
echo "📱 Vue modifiée : " . (file_exists($viewFile) ? "✅ Prête" : "❌ Non prête") . "\n";
echo "🎯 Joueur ID 122 : " . (isset($player) ? "✅ Vérifié" : "❌ Non vérifié") . "\n";

echo "\n🚀 PROCHAINES ÉTAPES :\n";
echo "1. Accéder à http://localhost:8000/portail-joueur/122\n";
echo "2. Vérifier que le logo FTF s'affiche (pas juste le texte 'FTF')\n";
echo "3. Si le problème persiste, vérifier la console du navigateur\n";

echo "\n🎉 TEST TERMINÉ !\n";
?>







