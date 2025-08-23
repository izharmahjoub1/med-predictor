<?php
/**
 * Test Simple Route - Test de la route /test-pcma-simple
 */

echo "=== Test Simple Route - Route Simple PCMA ===\n\n";

echo "🎯 **Test de la route /test-pcma-simple (sans authentification)**\n\n";

// Test de la route simple
$testUrl = 'http://localhost:8080/test-pcma-simple';
echo "1. Test de la route : $testUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Route accessible (HTTP $httpCode)\n";
    echo "   📏 Taille de la réponse : " . strlen($response) . " caractères\n";
    
    // Analyser le contenu
    echo "\n2. Analyse du contenu :\n";
    
    // Vérifier les éléments de base
    if (strpos($response, 'Nouveau PCMA') !== false) {
        echo "   ✅ Titre PCMA trouvé\n";
    } else {
        echo "   ❌ Titre PCMA NON trouvé\n";
    }
    
    if (strpos($response, 'Méthode de saisie') !== false) {
        echo "   ✅ Section méthode de saisie trouvée\n";
    } else {
        echo "   ❌ Section méthode de saisie NON trouvée\n";
    }
    
    // Vérifier nos éléments vocaux
    echo "\n3. Éléments vocaux :\n";
    
    $voiceElements = [
        'Google Assistant PCMA' => 'Titre Google Assistant',
        'Commencer l\'examen PCMA' => 'Bouton de démarrage',
        'Whisper - Transcription Vocale' => 'Titre Whisper',
        'voice-section' => 'Section vocale',
        'start-voice-btn' => 'Bouton vocal',
        'initSpeechRecognition' => 'Fonction JS'
    ];
    
    foreach ($voiceElements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : NON TROUVÉ\n";
        }
    }
    
    // Vérifier la structure des onglets
    echo "\n4. Structure des onglets :\n";
    
    if (strpos($response, 'input-method-tab') !== false) {
        echo "   ✅ Classe input-method-tab trouvée\n";
        
        // Compter les onglets
        $tabCount = substr_count($response, 'input-method-tab');
        echo "   📊 Nombre d'onglets détectés : $tabCount\n";
        
    } else {
        echo "   ❌ Classe input-method-tab NON trouvée\n";
    }
    
    // Vérifier les sections
    echo "\n5. Sections de la page :\n";
    
    $sections = ['manual-section', 'voice-section', 'fhir-section', 'scan-section'];
    foreach ($sections as $section) {
        if (strpos($response, "id=\"$section\"") !== false) {
            echo "   ✅ Section $section trouvée\n";
        } else {
            echo "   ❌ Section $section NON trouvée\n";
        }
    }
    
    // Afficher le début de la réponse pour debug
    echo "\n6. Début de la réponse (500 premiers caractères) :\n";
    echo "   " . substr($response, 0, 500) . "\n";
    
    // Chercher des indices de problème
    if (strpos($response, 'error') !== false) {
        echo "\n7. ⚠️  Mot 'error' trouvé dans la réponse\n";
    }
    
    if (strpos($response, 'exception') !== false) {
        echo "\n7. ⚠️  Mot 'exception' trouvé dans la réponse\n";
    }
    
} else {
    echo "   ❌ Route inaccessible (HTTP $httpCode)\n";
}

echo "\n=== Résumé ===\n";
echo "🎯 **Objectif** : Vérifier que la route simple fonctionne\n";
echo "🔍 **Résultat** : Si les éléments vocaux sont trouvés ici, le problème est l'authentification\n";
echo "📋 **Prochaines étapes** :\n";
echo "1. Si ça marche → Problème d'authentification sur /pcma/create\n";
echo "2. Si ça ne marche pas → Problème dans le code de la vue\n";
echo "\n🎉 **Test de la route simple terminé !**\n";
?>

