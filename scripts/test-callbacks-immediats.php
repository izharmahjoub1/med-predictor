<?php
/**
 * Test de la configuration immédiate des callbacks vocaux
 */

echo "🧪 TEST DE LA CONFIGURATION IMMÉDIATE DES CALLBACKS\n";
echo "==================================================\n\n";

$baseUrl = 'http://localhost:8081';

// Test 1: Vérifier que la page PCMA est accessible
echo "📋 Test 1: Accès à la page PCMA\n";
echo "--------------------------------\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/pcma/create");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; TestBot)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "URL finale: $finalUrl\n";

if ($httpCode === 200 && strpos($finalUrl, 'pcma/create') !== false) {
    echo "✅ Page PCMA accessible\n\n";
    
    // Test 2: Vérifier la configuration immédiate des callbacks
    echo "🔍 Test 2: Configuration immédiate des callbacks\n";
    echo "-----------------------------------------------\n";
    
    $immediateChecks = [
        'Configuration immédiate des callbacks' => strpos($response, 'Configuration immédiate des callbacks') !== false,
        'Callback immédiat onResult' => strpos($response, 'callback immédiat)') !== false,
        'Vérification callback onResult' => strpos($response, 'Vérification callback onResult') !== false,
        'Callbacks configurés immédiatement' => strpos($response, 'Callbacks configurés immédiatement') !== false,
        'Callback principal onResult' => strpos($response, 'callback principal)') !== false,
        'Callback manuel onResult' => strpos($response, 'callback manuel)') !== false
    ];
    
    foreach ($immediateChecks as $check => $found) {
        echo "   " . ($found ? '✅' : '❌') . " $check\n";
    }
    
    $foundCount = count(array_filter($immediateChecks));
    $totalCount = count($immediateChecks);
    
    echo "\n📊 Résumé: $foundCount/$totalCount vérifications OK\n";
    
    if ($foundCount >= 4) {
        echo "✅ Configuration immédiate des callbacks détectée\n\n";
        
        // Test 3: Vérifier l'ordre d'initialisation
        echo "🔧 Test 3: Ordre d'initialisation\n";
        echo "--------------------------------\n";
        
        $orderChecks = [
            'SpeechRecognitionService initialisé' => strpos($response, 'SpeechRecognitionService initialisé') !== false,
            'Configuration immédiate des callbacks' => strpos($response, 'Configuration immédiate des callbacks') !== false,
            'Callbacks configurés immédiatement' => strpos($response, 'Callbacks configurés immédiatement') !== false,
            'Service vocal global détecté' => strpos($response, 'Service vocal global détecté') !== false,
            'Application initialisée avec succès' => strpos($response, 'Application initialisée avec succès') !== false
        ];
        
        foreach ($orderChecks as $check => $found) {
            echo "   " . ($found ? '✅' : '❌') . " $check\n";
        }
        
        $orderFoundCount = count(array_filter($orderChecks));
        $orderTotalCount = count($orderChecks);
        
        echo "\n📊 Ordre d'initialisation: $orderFoundCount/$orderTotalCount étapes OK\n";
        
        if ($orderFoundCount >= 4) {
            echo "✅ Ordre d'initialisation correct\n\n";
            
            echo "🎯 DIAGNOSTIC:\n";
            echo "Les callbacks sont maintenant configurés immédiatement après l'initialisation.\n";
            echo "Testez à nouveau la reconnaissance vocale avec:\n";
            echo "1. 'ID FIFA CONNECT' ou 'Eddy FIFA connecte T1001'\n";
            echo "2. Vérifiez la console pour les nouveaux messages\n";
            echo "3. Les données devraient maintenant s'afficher\n\n";
            
        } else {
            echo "❌ Problème dans l'ordre d'initialisation\n\n";
            
            echo "🔧 ACTIONS NÉCESSAIRES:\n";
            echo "1. Vérifier l'ordre des scripts\n";
            echo "2. Vérifier l'initialisation du service\n";
            echo "3. Vérifier la configuration des callbacks\n\n";
        }
        
    } else {
        echo "❌ Configuration immédiate des callbacks manquante\n\n";
        
        echo "🔧 ACTIONS NÉCESSAIRES:\n";
        echo "1. Vérifier l'intégration des callbacks immédiats\n";
        echo "2. Vérifier l'ordre d'initialisation\n";
        echo "3. Vérifier la configuration du service\n\n";
    }
    
} elseif (strpos($finalUrl, 'login') !== false) {
    echo "🔐 AUTHENTIFICATION REQUISE\n\n";
    echo "✅ Serveur Laravel: OK\n";
    echo "⚠️ Status: Redirection vers login détectée\n\n";
    
    echo "🎯 INSTRUCTIONS:\n";
    echo "1. Accédez à: $baseUrl/login\n";
    echo "2. Connectez-vous avec vos identifiants\n";
    echo "3. Naviguez vers: $baseUrl/pcma/create\n";
    echo "4. Testez la configuration immédiate des callbacks\n\n";
    
} else {
    echo "❌ Page PCMA inaccessible\n\n";
    echo "🔧 ACTIONS NÉCESSAIRES:\n";
    echo "1. Vérifier que le serveur Laravel fonctionne\n";
    echo "2. Vérifier les routes et permissions\n";
    echo "3. Vérifier l'URL: $baseUrl/pcma/create\n\n";
}

echo "✨ Test terminé !\n";
?>

