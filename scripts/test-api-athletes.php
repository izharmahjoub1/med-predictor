<?php
/**
 * 🏃 TEST DE L'API ATHLÈTES
 * Vérification de la recherche des joueurs
 */

echo "🏃 TEST DE L'API ATHLÈTES\n";
echo "==========================\n\n";

// 1. Test de l'endpoint de recherche
echo "📋 Test 1: Endpoint de recherche des athlètes\n";
echo "---------------------------------------------\n";

$searchUrl = 'http://localhost:8081/api/athletes/search?name=Ali%20Jebali';
echo "URL de test: $searchUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $searchUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Code HTTP: $httpCode\n";
echo "URL finale: $finalUrl\n";

if ($httpCode === 200) {
    echo "✅ API accessible\n";
    
    // Extraire le body JSON
    $bodyStart = strpos($response, "\r\n\r\n");
    if ($bodyStart === false) {
        $bodyStart = strpos($response, "\n\n");
    }
    
    if ($bodyStart !== false) {
        $jsonBody = substr($response, $bodyStart + 4);
        $data = json_decode($jsonBody, true);
        
        if ($data) {
            echo "✅ Réponse JSON valide\n";
            echo "📊 Structure de la réponse:\n";
            print_r($data);
            
            if (isset($data['success']) && $data['success']) {
                echo "\n✅ Recherche réussie\n";
                
                if (isset($data['player'])) {
                    $player = $data['player'];
                    echo "👤 Données du joueur trouvé:\n";
                    echo "   - ID: " . ($player['id'] ?? 'N/A') . "\n";
                    echo "   - Nom: " . ($player['name'] ?? 'N/A') . "\n";
                    echo "   - ID FIFA: " . ($player['fifa_connect_id'] ?? 'N/A') . "\n";
                    echo "   - Position: " . ($player['position'] ?? 'N/A') . "\n";
                    echo "   - Âge: " . ($player['age'] ?? 'N/A') . "\n";
                    echo "   - Club: " . ($player['club'] ?? 'N/A') . "\n";
                } else {
                    echo "❌ Données du joueur manquantes\n";
                }
            } else {
                echo "\n❌ Recherche échouée\n";
                if (isset($data['message'])) {
                    echo "Message: " . $data['message'] . "\n";
                }
            }
        } else {
            echo "❌ Réponse JSON invalide\n";
            echo "Contenu brut: " . substr($jsonBody, 0, 200) . "...\n";
        }
    } else {
        echo "❌ Impossible d'extraire le body de la réponse\n";
    }
    
} else {
    echo "❌ API non accessible (Code: $httpCode)\n";
    
    if ($httpCode === 401) {
        echo "🔐 Erreur d'authentification\n";
    } elseif ($httpCode === 404) {
        echo "🔍 Endpoint non trouvé\n";
    } elseif ($httpCode === 500) {
        echo "💥 Erreur serveur interne\n";
    }
}

// 2. Test de l'endpoint de base des athlètes
echo "\n📋 Test 2: Endpoint de base des athlètes\n";
echo "----------------------------------------\n";

$baseUrl = 'http://localhost:8081/api/athletes';
echo "URL de test: $baseUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ Endpoint de base accessible\n";
} else {
    echo "❌ Endpoint de base non accessible\n";
}

// 3. Vérification des routes Laravel
echo "\n📋 Test 3: Vérification des routes\n";
echo "----------------------------------\n";

$routesUrl = 'http://localhost:8081/api';
echo "URL de test: $routesUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $routesUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code HTTP: $httpCode\n";

if ($httpCode === 200) {
    echo "✅ API Laravel accessible\n";
} else {
    echo "❌ API Laravel non accessible\n";
}

echo "\n🎯 DIAGNOSTIC API TERMINÉ\n";
echo "=========================\n";
?>

