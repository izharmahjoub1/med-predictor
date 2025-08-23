<?php
/**
 * 🎯 TEST DE LA COMMANDE "ID FIFA CONNECT"
 * Diagnostic du problème de reconnaissance et de chargement des données
 */

echo "🎯 TEST DE LA COMMANDE 'ID FIFA CONNECT'\n";
echo "=====================================\n\n";

// 1. Vérifier la page PCMA
echo "📋 Test 1: Accès à la page PCMA\n";
echo "--------------------------------\n";

$url = 'http://localhost:8081/pcma/create';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "URL finale: $finalUrl\n";

if ($httpCode === 200 && strpos($finalUrl, '/pcma/create') !== false) {
    echo "✅ Page PCMA accessible\n";
    
    // 2. Analyser le contenu HTML
    echo "\n📋 Test 2: Analyse du contenu HTML\n";
    echo "----------------------------------\n";
    
    // Extraire le body
    $bodyStart = strpos($response, "\r\n\r\n");
    if ($bodyStart === false) {
        $bodyStart = strpos($response, "\n\n");
    }
    
    if ($bodyStart !== false) {
        $html = substr($response, $bodyStart + 4);
        
        // Vérifier les éléments clés
        $checks = [
            'console-vocale' => 'Console vocale',
            'mode-manuel' => 'Mode Manuel',
            'mode-vocal' => 'Mode Vocal',
            'voice-status' => 'Statut vocal',
            'voice_player_name' => 'Champ nom joueur',
            'voice_age' => 'Champ âge',
            'voice_position' => 'Champ position',
            'voice_club' => 'Champ club'
        ];
        
        foreach ($checks as $id => $description) {
            if (strpos($html, "id=\"$id\"") !== false) {
                echo "✅ $description: TROUVÉ (id=\"$id\")\n";
            } else {
                echo "❌ $description: NON TROUVÉ (id=\"$id\")\n";
            }
        }
        
        // Vérifier les fonctions JavaScript
        echo "\n🔍 Vérification des fonctions JavaScript:\n";
        $jsChecks = [
            'searchPlayerByFifaConnect' => 'Recherche automatique FIFA',
            'fillFormFieldsWithPlayerData' => 'Remplissage des champs',
            'analyzeVoiceText' => 'Analyse du texte vocal',
            'displayVoiceResults' => 'Affichage des résultats'
        ];
        
        foreach ($jsChecks as $function => $description) {
            if (strpos($html, "function $function") !== false) {
                echo "✅ $description: TROUVÉ (function $function)\n";
            } else {
                echo "❌ $description: NON TROUVÉ (function $function)\n";
            }
        }
        
        // Vérifier les patterns de détection FIFA
        echo "\n🔍 Vérification des patterns FIFA:\n";
        $fifaPatterns = [
            'id\\s+fifa\\s+connect' => 'Pattern principal FIFA',
            'fifa\\s+connect' => 'Pattern FIFA Connect',
            'fifa_connect_search' => 'Commande FIFA'
        ];
        
        foreach ($fifaPatterns as $pattern => $description) {
            if (strpos($html, $pattern) !== false) {
                echo "✅ $description: TROUVÉ ($pattern)\n";
            } else {
                echo "❌ $description: NON TROUVÉ ($pattern)\n";
            }
        }
        
        // Vérifier les callbacks
        echo "\n🔍 Vérification des callbacks:\n";
        $callbackChecks = [
            'onResult' => 'Callback onResult',
            'onError' => 'Callback onError',
            'window.speechService' => 'Service vocal global'
        ];
        
        foreach ($callbackChecks as $callback => $description) {
            if (strpos($html, $callback) !== false) {
                echo "✅ $description: TROUVÉ ($callback)\n";
            } else {
                echo "❌ $description: NON TROUVÉ ($callback)\n";
            }
        }
        
    } else {
        echo "❌ Impossible d'extraire le contenu HTML\n";
    }
    
} else {
    echo "❌ Page PCMA non accessible\n";
    echo "🔐 AUTHENTIFICATION REQUISE\n";
    echo "⚠️ Status: Redirection vers login détectée\n";
}

echo "\n🎯 DIAGNOSTIC COMPLET TERMINÉ\n";
echo "=============================\n";
?>

