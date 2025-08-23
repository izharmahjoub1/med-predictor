<?php

/**
 * Script de test pour l'intégration FIT
 * Teste la connexion à l'API FIT et la soumission PCMA
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\FitPcmaIntegrationService;
use App\Models\VoiceSession;

// Initialiser Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Test d'intégration FIT API\n";
echo "==============================\n\n";

try {
    // 1. Vérifier la santé de l'API FIT
    echo "1. Vérification de la santé de l'API FIT...\n";
    $fitService = new FitPcmaIntegrationService();
    $health = $fitService->checkFitApiHealth();
    
    if ($health['success']) {
        echo "✅ API FIT en bonne santé\n";
        echo "   Statut: {$health['status']}\n";
        if (isset($health['response_time'])) {
            echo "   Temps de réponse: {$health['response_time']}s\n";
        }
    } else {
        echo "❌ API FIT non accessible\n";
        echo "   Erreur: {$health['error']}\n";
        echo "   Statut: {$health['status']}\n";
    }
    
    echo "\n";
    
    // 2. Créer une session de test
    echo "2. Création d'une session de test...\n";
    $testSession = VoiceSession::create([
        'user_id' => 1,
        'player_name' => 'Test Player',
        'current_field' => 'identite',
        'session_data' => [
            'dialogflow_session' => 'test-session-' . uniqid(),
            'player_name' => 'Test Player',
            'age1' => ['amount' => 25, 'unit' => 'year'],
            'position' => 'attaquant',
            'person' => ['name' => 'Test Player']
        ],
        'status' => 'active',
        'language' => 'fr'
    ]);
    
    echo "✅ Session de test créée (ID: {$testSession->id})\n";
    echo "   Joueur: {$testSession->player_name}\n";
    echo "   Âge: 25 ans\n";
    echo "   Position: attaquant\n";
    
    echo "\n";
    
    // 3. Tester la soumission PCMA
    echo "3. Test de soumission PCMA à l'API FIT...\n";
    $result = $fitService->submitPcmaData($testSession);
    
    if ($result['success']) {
        echo "✅ Données PCMA soumises avec succès !\n";
        echo "   Réponse API: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
        
        // Mettre à jour le statut de la session
        $testSession->update(['status' => 'completed']);
        echo "   Session marquée comme terminée\n";
        
    } else {
        echo "❌ Échec de la soumission PCMA\n";
        echo "   Erreur: {$result['error']}\n";
        if (isset($result['details'])) {
            echo "   Détails: " . json_encode($result['details'], JSON_PRETTY_PRINT) . "\n";
        }
        
        // Marquer la session comme erreur
        $testSession->update(['status' => 'error']);
        echo "   Session marquée comme erreur\n";
    }
    
    echo "\n";
    
    // 4. Nettoyer la session de test
    echo "4. Nettoyage...\n";
    $testSession->delete();
    echo "✅ Session de test supprimée\n";
    
    echo "\n";
    echo "🎯 Test d'intégration FIT terminé !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: {$e->getMessage()}\n";
    echo "   Trace: {$e->getTraceAsString()}\n";
}

echo "\n";
echo "📋 Configuration requise:\n";
echo "   - FIT_API_BASE_URL dans .env\n";
echo "   - FIT_API_KEY dans .env\n";
echo "   - API FIT accessible et fonctionnelle\n";
echo "\n";
