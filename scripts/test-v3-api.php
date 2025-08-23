<?php

/**
 * Script de test pour l'API V3 de FIT
 * 
 * Ce script teste tous les endpoints principaux de l'API V3
 * pour vérifier que la configuration est correcte.
 */

echo "🧪 Test de l'API V3 FIT\n";
echo "========================\n\n";

$baseUrl = 'http://localhost:8000/api/v3';
$headers = [
    'X-API-Version: 3.0.0',
    'Accept: application/json',
    'Content-Type: application/json'
];

/**
 * Effectue une requête HTTP
 */
function makeRequest($method, $url, $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error, 'http_code' => 0];
    }
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'data' => json_decode($response, true)
    ];
}

/**
 * Teste un endpoint et affiche le résultat
 */
function testEndpoint($name, $method, $url, $data = null) {
    echo "🔍 Test: {$name}\n";
    echo "   URL: {$method} {$url}\n";
    
    $result = makeRequest($method, $url, $data, $GLOBALS['headers']);
    
    if (isset($result['error'])) {
        echo "   ❌ Erreur: {$result['error']}\n";
        return false;
    }
    
    if ($result['http_code'] >= 200 && $result['http_code'] < 300) {
        echo "   ✅ Succès (HTTP {$result['http_code']})\n";
        
        if (isset($result['data']['success']) && $result['data']['success']) {
            echo "   📊 Message: {$result['data']['message']}\n";
            
            // Afficher des informations spécifiques selon l'endpoint
            if (isset($result['data']['data']['version'])) {
                echo "   🏷️  Version: {$result['data']['data']['version']}\n";
            }
            if (isset($result['data']['data']['status'])) {
                echo "   📈 Statut: {$result['data']['data']['status']}\n";
            }
        }
        
        return true;
    } else {
        echo "   ❌ Échec (HTTP {$result['http_code']})\n";
        if (isset($result['data']['message'])) {
            echo "   📝 Message: {$result['data']['message']}\n";
        }
        return false;
    }
    
    echo "\n";
}

// Tests des endpoints principaux
echo "🚀 Tests des Endpoints Principaux\n";
echo "--------------------------------\n\n";

$tests = [
    // Informations système
    ['Santé du système', 'GET', "{$baseUrl}/health"],
    ['Informations système', 'GET', "{$baseUrl}/system-info"],
    
    // Intelligence Artificielle
    ['Statut IA', 'GET', "{$baseUrl}/ai/status"],
    ['Prédiction de performance', 'GET', "{$baseUrl}/ai/performance/1"],
    ['Risque de blessure', 'GET', "{$baseUrl}/ai/injury-risk/1"],
    
    // Analytics de Performance
    ['Analyse des tendances', 'GET', "{$baseUrl}/performance/trends/1?metric_type=speed"],
    ['Métriques de performance', 'GET', "{$baseUrl}/performance/metrics/1"],
    ['Résumé des performances', 'GET', "{$baseUrl}/performance/summary/1"],
    
    // APIs Sportives
    ['FIFA TMS Pro - Connectivité', 'GET', "{$baseUrl}/sports/fifa-tms-pro/connectivity"],
    ['Transfermarkt - Recherche', 'GET', "{$baseUrl}/sports/transfermarkt/search"],
    ['WhoScored - Stats joueur', 'GET', "{$baseUrl}/sports/whoscored/player/1/stats"],
    ['Opta - Stats avancées', 'GET', "{$baseUrl}/sports/opta/player/1/advanced-stats"],
    
    // Module Médical
    ['IA Médicale - Prédiction blessure', 'GET', "{$baseUrl}/medical/ai/injury-prediction/1"],
    ['Wearables - Biométrie', 'GET', "{$baseUrl}/medical/wearables/player/1/biometrics"],
    ['Prévention - Évaluation risque', 'GET', "{$baseUrl}/medical/prevention/risk-assessment/1"],
    
    // Analytics et BI
    ['Dashboard Performance', 'GET', "{$baseUrl}/analytics/dashboards/performance"],
    ['Prédictions Business', 'GET', "{$baseUrl}/analytics/predictions/market-trends"],
    ['Export Rapport', 'GET', "{$baseUrl}/analytics/export/report/performance"],
    
    // Système et Monitoring
    ['Métriques Système', 'GET', "{$baseUrl}/system/metrics"],
    ['Monitoring', 'GET', "{$baseUrl}/system/monitoring"],
    ['Optimisations', 'GET', "{$baseUrl}/system/optimizations"],
    
    // Sécurité et Conformité
    ['Audit Logs', 'GET', "{$baseUrl}/security/audit-logs"],
    ['Conformité GDPR', 'GET', "{$baseUrl}/security/gdpr/compliance-status"],
    
    // Développement et Tests
    ['Statut Tests', 'GET', "{$baseUrl}/dev/test-status"],
    ['Métriques Dev', 'GET', "{$baseUrl}/dev/dev-metrics"],
    ['Documentation API', 'GET', "{$baseUrl}/dev/api-docs"],
];

$successCount = 0;
$totalCount = count($tests);

foreach ($tests as $test) {
    $success = testEndpoint($test[0], $test[1], $test[2]);
    if ($success) {
        $successCount++;
    }
    echo "\n";
}

// Test de comparaison de joueurs (POST)
echo "🔄 Test de Comparaison de Joueurs\n";
echo "--------------------------------\n\n";

$comparisonData = [
    'player1_id' => 1,
    'player2_id' => 2,
    'metric_type' => 'speed',
    'days' => 30
];

testEndpoint('Comparaison de joueurs', 'POST', "{$baseUrl}/performance/compare", $comparisonData);

echo "\n";
echo "📊 Résumé des Tests\n";
echo "==================\n";
echo "Total des tests: {$totalCount}\n";
echo "Tests réussis: {$successCount}\n";
echo "Tests échoués: " . ($totalCount - $successCount) . "\n";
echo "Taux de succès: " . round(($successCount / $totalCount) * 100, 2) . "%\n\n";

if ($successCount === $totalCount) {
    echo "🎉 Tous les tests sont passés avec succès ! L'API V3 est parfaitement configurée.\n";
} else {
    echo "⚠️  Certains tests ont échoué. Vérifiez la configuration de l'API V3.\n";
}

echo "\n�� Test terminé.\n";
