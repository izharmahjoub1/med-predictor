<?php
/**
 * Debug Page Content - Analyse le contenu exact de la page
 */

echo "=== Debug Page Content ===\n\n";

// Test de la page
$createUrl = 'http://localhost:8080/pcma/create';
echo "1. Test de la page : $createUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $createUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    echo "   📏 Taille de la réponse : " . strlen($response) . " caractères\n";
    
    // Analyser le contenu
    echo "\n2. Analyse du contenu :\n";
    
    // Chercher des indices de problème
    if (strpos($response, 'error') !== false) {
        echo "   ⚠️  Mot 'error' trouvé\n";
    }
    
    if (strpos($response, 'exception') !== false) {
        echo "   ⚠️  Mot 'exception' trouvé\n";
    }
    
    if (strpos($response, 'not found') !== false) {
        echo "   ⚠️  Mot 'not found' trouvé\n";
    }
    
    // Chercher des éléments HTML de base
    if (strpos($response, '<html') !== false) {
        echo "   ✅ Balise HTML trouvée\n";
    } else {
        echo "   ❌ Balise HTML NON trouvée\n";
    }
    
    if (strpos($response, '<head') !== false) {
        echo "   ✅ Balise HEAD trouvée\n";
    } else {
        echo "   ❌ Balise HEAD NON trouvée\n";
    }
    
    if (strpos($response, '<body') !== false) {
        echo "   ✅ Balise BODY trouvée\n";
    } else {
        echo "   ❌ Balise BODY NON trouvée\n";
    }
    
    // Chercher des éléments spécifiques
    echo "\n3. Éléments spécifiques :\n";
    
    $elements = [
        'Nouveau PCMA' => 'Titre de la page',
        'Méthode de saisie' => 'Section des onglets',
        'Saisie manuelle' => 'Onglet manuel',
        'Enregistrement vocal' => 'Onglet vocal',
        'Assistant Vocal' => 'Onglet vocal (nouveau)',
        'form' => 'Formulaire',
        'input' => 'Champs de saisie'
    ];
    
    foreach ($elements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Afficher le début et la fin de la réponse
    echo "\n4. Début de la réponse (200 premiers caractères) :\n";
    echo "   " . substr($response, 0, 200) . "\n";
    
    echo "\n5. Fin de la réponse (200 derniers caractères) :\n";
    echo "   " . substr($response, -200) . "\n";
    
    // Chercher des patterns de redirection
    if (strpos($response, 'redirect') !== false) {
        echo "\n6. ⚠️  Redirection détectée dans le contenu\n";
    }
    
    if (strpos($response, 'login') !== false) {
        echo "\n6. ⚠️  Page de login détectée\n";
    }
    
    // Vérifier si c'est une page d'erreur
    if (strpos($response, '404') !== false) {
        echo "\n6. ❌ Page d'erreur 404 détectée\n";
    }
    
    if (strpos($response, '500') !== false) {
        echo "\n6. ❌ Page d'erreur 500 détectée\n";
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
    echo "   📍 Redirection vers : $finalUrl\n";
}

echo "\n=== Résumé ===\n";
echo "🔍 Problèmes potentiels :\n";
echo "1. La page peut être une redirection\n";
echo "2. La page peut être une erreur\n";
echo "3. La page peut être mise en cache\n";
echo "4. Il peut y avoir un problème d'authentification\n";
echo "\n📋 Solutions à essayer :\n";
echo "1. Vérifier l'URL finale après redirection\n";
echo "2. Vérifier les logs Laravel\n";
echo "3. Vérifier la console du navigateur\n";
echo "4. Tester avec un navigateur en mode incognito\n";
echo "\n🎯 Objectif : Comprendre pourquoi la page est si courte !\n";
?>

