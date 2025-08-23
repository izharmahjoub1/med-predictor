<?php
/**
 * Script de Débogage - Section Vocale PCMA
 * Diagnostique pourquoi la section vocale n'est pas visible
 */

echo "=== Débogage Section Vocale PCMA ===\n\n";

// Test de la page de création PCMA
$createUrl = 'http://localhost:8080/pcma/create';
echo "1. Test de la page de création PCMA :\n";
echo "   URL : $createUrl\n";

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
    
    // Vérifier la présence des éléments vocaux
    echo "\n2. Analyse détaillée des éléments vocaux :\n";
    
    // Vérifier la section vocale
    if (strpos($response, 'id="voice-section"') !== false) {
        echo "   ✅ Section vocale trouvée dans le HTML\n";
        
        // Compter les occurrences
        $voiceSectionCount = substr_count($response, 'id="voice-section"');
        echo "   📊 Nombre d'occurrences de voice-section : $voiceSectionCount\n";
        
        if ($voiceSectionCount > 1) {
            echo "   ⚠️  ATTENTION : Plusieurs sections vocales détectées !\n";
        }
        
        // Vérifier la classe hidden
        if (strpos($response, 'class="input-section hidden"') !== false) {
            echo "   ✅ Classe 'hidden' détectée (section cachée par défaut)\n";
        } else {
            echo "   ❌ Classe 'hidden' non trouvée\n";
        }
        
    } else {
        echo "   ❌ Section vocale NON trouvée dans le HTML\n";
    }
    
    // Vérifier les onglets
    if (strpos($response, 'id="voice-tab"') !== false) {
        echo "   ✅ Onglet vocal trouvé\n";
    } else {
        echo "   ❌ Onglet vocal NON trouvé\n";
    }
    
    // Vérifier les boutons
    if (strpos($response, 'id="start-voice-btn"') !== false) {
        echo "   ✅ Bouton de démarrage vocal trouvé\n";
    } else {
        echo "   ❌ Bouton de démarrage vocal NON trouvé\n";
    }
    
    if (strpos($response, 'id="stop-voice-btn"') !== false) {
        echo "   ✅ Bouton d'arrêt vocal trouvé\n";
    } else {
        echo "   ❌ Bouton d'arrêt vocal NON trouvé\n";
    }
    
    // Vérifier le JavaScript
    echo "\n3. Analyse du JavaScript :\n";
    
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
    
    if (strpos($response, 'webkitSpeechRecognition') !== false) {
        echo "   ✅ Support reconnaissance vocale trouvé\n";
    } else {
        echo "   ❌ Support reconnaissance vocale NON trouvé\n";
    }
    
    // Vérifier les instructions vocales
    echo "\n4. Analyse des instructions vocales :\n";
    
    $instructions = [
        'commencer l\'examen PCMA' => 'Instruction de démarrage',
        'Il s\'appelle' => 'Instruction pour le nom',
        'Il a' => 'Instruction pour l\'âge',
        'Il joue' => 'Instruction pour la position',
        'attaquant' => 'Position attaquant',
        'défenseur' => 'Position défenseur',
        'milieu' => 'Position milieu',
        'gardien' => 'Position gardien'
    ];
    
    foreach ($instructions as $instruction => $description) {
        if (strpos($response, $instruction) !== false) {
            echo "   ✅ $description trouvé\n";
        } else {
            echo "   ❌ $description manquant\n";
        }
    }
    
    // Vérifier la structure des onglets
    echo "\n5. Analyse de la structure des onglets :\n";
    
    if (strpos($response, 'input-method-tab') !== false) {
        echo "   ✅ Classe input-method-tab trouvée\n";
        
        // Compter les onglets
        $tabCount = substr_count($response, 'input-method-tab');
        echo "   📊 Nombre d'onglets détectés : $tabCount\n";
        
        // Vérifier les IDs des onglets
        $tabIds = ['manual-tab', 'voice-tab', 'fhir-tab', 'scan-tab'];
        foreach ($tabIds as $tabId) {
            if (strpos($response, "id=\"$tabId\"") !== false) {
                echo "   ✅ Onglet $tabId trouvé\n";
            } else {
                echo "   ❌ Onglet $tabId NON trouvé\n";
            }
        }
    } else {
        echo "   ❌ Classe input-method-tab NON trouvée\n";
    }
    
    // Vérifier les sections
    echo "\n6. Analyse des sections :\n";
    
    $sections = ['manual-section', 'voice-section', 'fhir-section', 'scan-section'];
    foreach ($sections as $section) {
        if (strpos($response, "id=\"$section\"") !== false) {
            echo "   ✅ Section $section trouvée\n";
        } else {
            echo "   ❌ Section $section NON trouvée\n";
        }
    }
    
} else {
    echo "   ❌ Page inaccessible (HTTP $httpCode)\n";
    echo "   📍 Redirection vers : $finalUrl\n";
}

echo "\n=== Résumé du Débogage ===\n";
echo "🔍 Problèmes potentiels identifiés :\n";
echo "1. Vérifier que la section vocale est bien présente\n";
echo "2. Vérifier que le JavaScript est correctement chargé\n";
echo "3. Vérifier que les onglets fonctionnent\n";
echo "4. Vérifier que la classe 'hidden' est gérée\n";
echo "\n📋 Prochaines étapes :\n";
echo "1. Vérifier la console du navigateur pour les erreurs JavaScript\n";
echo "2. Tester le clic sur l'onglet vocal\n";
echo "3. Vérifier que la section vocale devient visible\n";
echo "\n🎯 Objectif : Rendre la section vocale visible et fonctionnelle !\n";
?>

