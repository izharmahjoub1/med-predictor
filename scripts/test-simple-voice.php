<?php
/**
 * Test Simple - Vérification des Éléments Vocaux
 * Teste si les éléments vocaux sont présents dans la page
 */

echo "=== Test Simple - Éléments Vocaux ===\n\n";

// Test de la page de création PCMA
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
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Page accessible (HTTP $httpCode)\n";
    
    // Test simple - chercher des mots-clés
    echo "\n2. Recherche de mots-clés :\n";
    
    $keywords = [
        'Google Assistant PCMA' => 'Titre de la carte Google Assistant',
        'Commencer l\'examen PCMA' => 'Bouton de démarrage',
        'Whisper - Transcription Vocale' => 'Titre de la carte Whisper',
        'voice-section' => 'ID de la section vocale',
        'start-voice-btn' => 'ID du bouton de démarrage',
        'UPDATED 2025-08-20' => 'Commentaire de mise à jour'
    ];
    
    foreach ($keywords as $keyword => $description) {
        if (strpos($response, $keyword) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Test de la structure des onglets
    echo "\n3. Structure des onglets :\n";
    
    if (strpos($response, 'input-method-tab') !== false) {
        echo "   ✅ Classe input-method-tab trouvée\n";
        
        // Compter les onglets
        $tabCount = substr_count($response, 'input-method-tab');
        echo "   📊 Nombre d'onglets détectés : $tabCount\n";
        
    } else {
        echo "   ❌ Classe input-method-tab NON trouvée\n";
    }
    
    // Test des sections
    echo "\n4. Sections de la page :\n";
    
    $sections = ['manual-section', 'voice-section', 'fhir-section', 'scan-section'];
    foreach ($sections as $section) {
        if (strpos($response, "id=\"$section\"") !== false) {
            echo "   ✅ Section $section trouvée\n";
        } else {
            echo "   ❌ Section $section NON trouvée\n";
        }
    }
    
    // Test du JavaScript
    echo "\n5. JavaScript vocal :\n";
    
    if (strpos($response, 'initSpeechRecognition') !== false) {
        echo "   ✅ Fonction initSpeechRecognition trouvée\n";
    } else {
        echo "   ❌ Fonction initSpeechRecognition NON trouvée\n";
    }
    
    if (strpos($response, 'processVoiceInput') !== false) {
        echo "   ✅ Fonction processVoiceInput trouvée\n";
    } else {
        echo "   ❌ Fonction processVoiceInput NON trouvée\n";
    }
    
    // Test de la longueur de la réponse
    echo "\n6. Informations générales :\n";
    echo "   📏 Taille de la réponse : " . strlen($response) . " caractères\n";
    
    // Chercher des indices de problème
    if (strpos($response, 'error') !== false) {
        echo "   ⚠️  Mot 'error' trouvé dans la réponse\n";
    }
    
    if (strpos($response, 'exception') !== false) {
        echo "   ⚠️  Mot 'exception' trouvé dans la réponse\n";
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé ===\n";
echo "🔍 Si les éléments vocaux ne sont pas trouvés :\n";
echo "1. La page peut être mise en cache\n";
echo "2. Le serveur peut ne pas avoir rechargé les modifications\n";
echo "3. Il peut y avoir une erreur dans la structure HTML\n";
echo "\n📋 Solutions à essayer :\n";
echo "1. Vider le cache du navigateur (Ctrl+F5)\n";
echo "2. Redémarrer le serveur Laravel\n";
echo "3. Vérifier la console du navigateur pour les erreurs\n";
echo "\n🎯 Objectif : Identifier pourquoi les éléments vocaux ne sont pas visibles !\n";
?>

