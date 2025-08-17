<?php
/**
 * Test de la Hero Zone Enrichie FIFA Portal
 */

echo "🔍 TEST HERO ZONE ENRICHIE FIFA PORTAL\n";
echo "=======================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Vérifier que la page FIFA Portal est accessible
echo "1️⃣ TEST ACCÈS FIFA PORTAL\n";
echo "-------------------------\n";

$testUrl = "$baseUrl/fifa-portal";
echo "🎯 URL testée: $testUrl\n";

$response = file_get_contents($testUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès\n";
} else {
    echo "✅ Page accessible\n";
    
    // Vérifier les nouveaux éléments de la hero zone
    $newElements = [
        'hero-height',
        'hero-weight', 
        'hero-preferred-foot',
        'hero-goals-scored',
        'hero-assists',
        'hero-matches-played',
        'hero-injury-risk',
        'hero-morale',
        'hero-medications-count',
        'hero-season-progress',
        'hero-matches-completed',
        'hero-matches-remaining',
        'hero-recent-results'
    ];
    
    echo "\n🔍 Vérification des nouveaux éléments:\n";
    foreach ($newElements as $element) {
        if (strpos($response, $element) !== false) {
            echo "  ✅ $element: présent\n";
        } else {
            echo "  ❌ $element: NON présent\n";
        }
    }
    
    // Vérifier les nouvelles sections
    $newSections = [
        'fifa-player-physical',
        'fifa-hero-advanced',
        'fifa-performance-stats',
        'fifa-health-indicators',
        'fifa-season-progress',
        'fifa-recent-performance'
    ];
    
    echo "\n🔍 Vérification des nouvelles sections:\n";
    foreach ($newSections as $section) {
        if (strpos($response, $section) !== false) {
            echo "  ✅ $section: présente\n";
        } else {
            echo "  ❌ $section: NON présente\n";
        }
    }
    
    // Vérifier les nouvelles fonctions JavaScript
    $newFunctions = [
        'updateHeroProgressBar',
        'updateHeroRecentResults',
        'generateRecentResults'
    ];
    
    echo "\n🔍 Vérification des nouvelles fonctions:\n";
    foreach ($newFunctions as $function) {
        if (strpos($response, $function) !== false) {
            echo "  ✅ $function: présente\n";
        } else {
            echo "  ❌ $function: NON présente\n";
        }
    }
}

echo "\n2️⃣ RÉSUMÉ ET RECOMMANDATIONS\n";
echo "-----------------------------\n";

echo "📋 État de la Hero Zone Enrichie:\n";
echo "  • Nouveaux éléments HTML: " . count(array_filter($newElements, function($el) use ($response) { return strpos($response, $el) !== false; })) . "/" . count($newElements) . "\n";
echo "  • Nouvelles sections CSS: " . count(array_filter($newSections, function($sec) use ($response) { return strpos($response, $sec) !== false; })) . "/" . count($newSections) . "\n";
echo "  • Nouvelles fonctions JS: " . count(array_filter($newFunctions, function($func) use ($response) { return strpos($response, $func) !== false; })) . "/" . count($newFunctions) . "\n";

echo "\n🚀 PROCHAINES ÉTAPES:\n";
echo "1. Ouvrir $testUrl dans le navigateur\n";
echo "2. Vérifier que la hero zone affiche les nouvelles données\n";
echo "3. Tester avec un joueur spécifique: $testUrl?player_id=8\n";
echo "4. Vérifier la console pour les logs de débogage\n";

echo "\n✅ Test terminé !\n";

