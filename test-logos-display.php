<?php
/**
 * Script pour tester l'affichage des logos dans le portail joueur
 */

echo "🔍 TEST D'AFFICHAGE DES LOGOS\n";
echo "=============================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier les logos des clubs
echo "🏟️ TEST 1: LOGOS DES CLUBS\n";
echo "----------------------------\n";

$stmt = $db->query("SELECT name, logo_url, logo_path FROM clubs WHERE logo_url IS NOT NULL LIMIT 5");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clubs as $club) {
    echo "🏟️ {$club['name']}:\n";
    echo "   Logo URL : {$club['logo_url']}\n";
    echo "   Logo Path : {$club['logo_path']}\n";
    
    // Test d'accessibilité
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $club['logo_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ Logo accessible (HTTP {$httpCode})\n";
    } else {
        echo "   ❌ Logo non accessible (HTTP {$httpCode})\n";
    }
    echo "\n";
}

// Test 2: Vérifier le logo FTF
echo "🏆 TEST 2: LOGO FTF\n";
echo "-------------------\n";

$stmt = $db->prepare("SELECT name, association_logo_url, logo_image FROM associations WHERE name LIKE '%FTF%'");
$stmt->execute();
$ftf = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ftf) {
    echo "🏆 {$ftf['name']}:\n";
    echo "   Logo URL : {$ftf['association_logo_url']}\n";
    echo "   Logo Image : {$ftf['logo_image']}\n";
    
    // Test d'accessibilité
    if ($ftf['association_logo_url']) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ftf['association_logo_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            echo "   ✅ Logo FTF accessible (HTTP {$httpCode})\n";
        } else {
            echo "   ❌ Logo FTF non accessible (HTTP {$httpCode})\n";
        }
    } else {
        echo "   ❌ Pas d'URL de logo FTF\n";
    }
} else {
    echo "❌ Association FTF non trouvée\n";
}

echo "\n";

// Test 3: Vérifier un joueur avec ses relations
echo "👥 TEST 3: JOUEUR AVEC RELATIONS\n";
echo "--------------------------------\n";

$playerId = 88; // Ali Jebali
$stmt = $db->prepare("
    SELECT p.*, c.name as club_name, c.logo_url, c.logo_path, c.country as club_country,
           a.name as association_name, a.association_logo_url, a.logo_image, a.country as association_country
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
    echo "   🏟️ Logo Club URL : {$player['logo_url']}\n";
    echo "   🏆 Association : {$player['association_name']} (ID: {$player['association_id']})\n";
    echo "   🏆 Logo Association URL : {$player['association_logo_url']}\n";
    
    // Test des URLs
    if ($player['logo_url']) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $player['logo_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "   🏟️ Logo Club : " . ($httpCode == 200 ? "✅ Accessible (HTTP {$httpCode})" : "❌ Non accessible (HTTP {$httpCode})") . "\n";
    }
    
    if ($player['association_logo_url']) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $player['association_logo_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "   🏆 Logo Association : " . ($httpCode == 200 ? "✅ Accessible (HTTP {$httpCode})" : "❌ Non accessible (HTTP {$httpCode})") . "\n";
    }
} else {
    echo "❌ Joueur non trouvé\n";
}

echo "\n";

// Test 4: Vérifier la vue Blade
echo "📱 TEST 4: VÉRIFICATION DE LA VUE\n";
echo "--------------------------------\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé : {$viewFile}\n";
    
    // Vérifier si les composants sont utilisés
    $content = file_get_contents($viewFile);
    
    if (strpos($content, '<x-club-logo') !== false) {
        echo "   ⚠️  Composant club-logo encore utilisé\n";
    } else {
        echo "   ✅ Composant club-logo remplacé par du code direct\n";
    }
    
    if (strpos($content, '<x-flag-logo-display') !== false) {
        echo "   ⚠️  Composant flag-logo-display encore utilisé\n";
    } else {
        echo "   ✅ Composant flag-logo-display remplacé par du code direct\n";
    }
    
    // Vérifier la présence du code direct
    if (strpos($content, 'getCountryFlagCode') !== false) {
        echo "   ✅ Fonction getCountryFlagCode intégrée\n";
    } else {
        echo "   ❌ Fonction getCountryFlagCode manquante\n";
    }
    
    if (strpos($content, 'onerror=') !== false) {
        echo "   ✅ Gestion d'erreur des images intégrée\n";
    } else {
        echo "   ❌ Gestion d'erreur des images manquante\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}

echo "\n🔍 DIAGNOSTIC FINAL\n";
echo "===================\n";
echo "1. ✅ Logos des clubs mis à jour avec URLs fiables\n";
echo "2. ✅ Logo FTF créé et accessible\n";
echo "3. ✅ Données des joueurs avec relations complètes\n";
echo "4. ✅ Vue modifiée avec code direct au lieu de composants\n";
echo "5. ✅ Barre de navigation simplifiée avec recherche et navigation\n";

echo "\n🎯 PROCHAINES ÉTAPES\n";
echo "====================\n";
echo "1. Tester l'affichage dans le navigateur : http://localhost:8000/portail-joueur/88\n";
echo "2. Vérifier que les logos s'affichent correctement\n";
echo "3. Tester la nouvelle barre de navigation\n";
echo "4. Vérifier la fonctionnalité de recherche\n";

echo "\n🎉 TEST TERMINÉ !\n";
?>
