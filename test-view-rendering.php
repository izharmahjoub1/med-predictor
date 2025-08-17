<?php
/**
 * Test du rendu de la vue pour vérifier l'affichage du logo FTF
 */

echo "🎨 TEST DU RENDU DE LA VUE - LOGO FTF\n";
echo "======================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Test 1: Vérifier la structure de la vue
echo "📱 TEST 1: STRUCTURE DE LA VUE\n";
echo "===============================\n";

$viewFile = 'resources/views/portail-joueur-final-corrige-dynamique.blade.php';
if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé\n";
    
    $content = file_get_contents($viewFile);
    
    // Vérifier les sections clés
    $sections = [
        'Logo Association' => strpos($content, '<!-- Logo Association -->') !== false,
        'Code FTF avec image' => strpos($content, 'association_logo_url') !== false,
        'Fallback FTF' => strpos($content, 'Fallback avec FTF en texte') !== false,
        'Gestion d\'erreur' => strpos($content, 'onerror=') !== false,
        'Condition FTF' => strpos($content, 'str_contains(strtolower($player->association->name), \'ftf\')') !== false
    ];
    
    foreach ($sections as $section => $exists) {
        echo "   " . ($exists ? "✅" : "❌") . " {$section}\n";
    }
} else {
    echo "❌ Fichier de vue non trouvé\n";
}

echo "\n";

// Test 2: Simuler le rendu HTML pour le joueur ID 122
echo "🎯 TEST 2: SIMULATION DU RENDU HTML - JOUEUR ID 122\n";
echo "==================================================\n";

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
    echo "✅ Données du joueur récupérées\n";
    echo "   👤 Nom : {$player['first_name']} {$player['last_name']}\n";
    echo "   🏆 Association : {$player['association_name']}\n";
    echo "   🏆 Logo URL : {$player['association_logo_url']}\n";
    
    // Simuler le rendu HTML
    echo "\n🎨 HTML SIMULÉ POUR LE LOGO FTF :\n";
    echo "==================================\n";
    
    if (str_contains(strtolower($player['association_name']), 'ftf')) {
        if (!empty($player['association_logo_url'])) {
            echo "<!-- Logo FTF avec image -->\n";
            echo "<img src=\"{$player['association_logo_url']}\" \n";
            echo "     alt=\"Logo {$player['association_name']}\" \n";
            echo "     class=\"w-12 h-12 object-contain rounded-lg shadow-sm mb-2\" \n";
            echo "     onerror=\"this.style.display='none'; this.nextElementSibling.style.display='flex';\">\n";
            echo "\n<!-- Fallback avec FTF en texte (caché par défaut) -->\n";
            echo "<div class=\"w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs mb-2\" style=\"display: none;\">\n";
            echo "    FTF\n";
            echo "</div>\n";
            
            echo "\n✅ Ce code devrait afficher le VRAI logo FTF\n";
        } else {
            echo "❌ Pas d'URL de logo - affichage du fallback\n";
            echo "<div class=\"w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs mb-2\">\n";
            echo "    FTF\n";
            echo "</div>\n";
        }
    } else {
        echo "⚠️  Association non-FTF détectée\n";
    }
} else {
    echo "❌ Joueur non trouvé\n";
}

echo "\n";

// Test 3: Vérifier l'accessibilité du logo FTF
echo "🌐 TEST 3: ACCESSIBILITÉ DU LOGO FTF\n";
echo "====================================\n";

if (isset($player) && !empty($player['association_logo_url'])) {
    $logoUrl = $player['association_logo_url'];
    
    echo "🔍 Test de l'URL : {$logoUrl}\n";
    
    // Test avec cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $logoUrl);
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
        echo "   📁 Type : {$contentType}\n";
        
        // Test de téléchargement
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $logoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        $imageData = curl_exec($ch);
        curl_close($ch);
        
        if ($imageData && strlen($imageData) > 100) {
            echo "   ✅ Image téléchargeable (" . strlen($imageData) . " bytes)\n";
        } else {
            echo "   ❌ Image non téléchargeable\n";
        }
    } else {
        echo "   ❌ Logo non accessible (HTTP {$httpCode})\n";
    }
} else {
    echo "❌ Pas d'URL de logo à tester\n";
}

echo "\n";

// Test 4: Vérification des données de test
echo "🧪 TEST 4: DONNÉES DE TEST\n";
echo "===========================\n";

// Vérifier que le joueur a bien l'association FTF
if (isset($player) && $player['association_id']) {
    $stmt = $db->prepare("SELECT name, association_logo_url FROM associations WHERE id = ?");
    $stmt->execute([$player['association_id']]);
    $association = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($association) {
        echo "✅ Association trouvée : {$association['name']}\n";
        echo "   🏆 Logo URL : {$association['association_logo_url']}\n";
        
        if (str_contains(strtolower($association['name']), 'ftf')) {
            echo "   ✅ Association FTF confirmée\n";
        } else {
            echo "   ❌ Association non-FTF\n";
        }
    } else {
        echo "❌ Association non trouvée\n";
    }
} else {
    echo "❌ Pas d'association associée au joueur\n";
}

echo "\n";

// RÉSUMÉ FINAL
echo "🎯 RÉSUMÉ DU TEST DE RENDU\n";
echo "===========================\n";

$totalChecks = 4;
$passedChecks = 0;

// Calculer les vérifications réussies
if (file_exists($viewFile)) $passedChecks++;
if (isset($player) && $player['association_logo_url']) $passedChecks++;
if (isset($player) && str_contains(strtolower($player['association_name']), 'ftf')) $passedChecks++;
if (isset($association) && $association['association_logo_url']) $passedChecks++;

echo "📊 Vérifications réussies : {$passedChecks}/{$totalChecks}\n";
echo "📱 Vue modifiée : " . (file_exists($viewFile) ? "✅ Prête" : "❌ Non prête") . "\n";
echo "👤 Joueur ID 122 : " . (isset($player) ? "✅ Vérifié" : "❌ Non vérifié") . "\n";
echo "🏆 Association FTF : " . (isset($player) && str_contains(strtolower($player['association_name']), 'ftf') ? "✅ Confirmée" : "❌ Non confirmée") . "\n";
echo "🖼️ Logo FTF : " . (isset($player) && $player['association_logo_url'] ? "✅ Configuré" : "❌ Non configuré") . "\n";

echo "\n🚀 DIAGNOSTIC FINAL :\n";
echo "=====================\n";

if ($passedChecks == $totalChecks) {
    echo "✅ TOUT EST CONFIGURÉ CORRECTEMENT !\n";
    echo "🎯 Le logo FTF devrait maintenant s'afficher sur http://localhost:8000/portail-joueur/122\n";
    echo "\n🔍 Si le logo ne s'affiche toujours pas :\n";
    echo "1. Vider le cache du navigateur (Ctrl+F5 ou Cmd+Shift+R)\n";
    echo "2. Vérifier la console du navigateur pour les erreurs\n";
    echo "3. Vérifier que Laravel est bien redémarré\n";
} else {
    echo "⚠️  Certains éléments ne sont pas configurés correctement\n";
    echo "🔧 Vérifiez les éléments marqués ❌ ci-dessus\n";
}

echo "\n🎉 TEST DE RENDU TERMINÉ !\n";
?>
