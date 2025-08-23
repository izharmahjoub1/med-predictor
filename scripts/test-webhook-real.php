<?php
/**
 * Test du webhook en conditions réelles
 * Simule une vraie requête Dialogflow depuis Google Home
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Démarrer Laravel
$app = Application::configure(basePath: __DIR__ . '/..')
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(remove: [
            \App\Http\Middleware\AuthenticateSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du Webhook en Conditions Réelles ===\n\n";

try {
    // 1. Test de conversation complète PCMA
    echo "1. 🗣️  Test de conversation complète PCMA...\n";
    
    $conversation = [
        [
            'intent' => 'answer_field',
            'parameters' => ['player_name' => 'Karim'],
            'contexts' => [
                [
                    'name' => 'projects/test/locations/europe-west2/agent/sessions/real-test/contexts/start_pcma-followup',
                    'lifespanCount' => 5,
                    'parameters' => ['player_name' => 'Karim']
                ]
            ]
        ],
        [
            'intent' => 'answer_field', 
            'parameters' => ['age1' => ['amount' => 28, 'unit' => 'year']],
            'contexts' => [
                [
                    'name' => 'projects/test/locations/europe-west2/agent/sessions/real-test/contexts/start_pcma-followup',
                    'lifespanCount' => 5,
                    'parameters' => ['age1' => ['amount' => 28, 'unit' => 'year']]
                ]
            ]
        ],
        [
            'intent' => 'answer_field',
            'parameters' => ['position' => 'défenseur'],
            'contexts' => [
                [
                    'name' => 'projects/test/locations/europe-west2/agent/sessions/real-test/contexts/start_pcma-followup',
                    'lifespanCount' => 5,
                    'parameters' => ['position' => 'défenseur']
                ]
            ]
        ],
        [
            'intent' => 'submit_pcma',
            'parameters' => [],
            'contexts' => [
                [
                    'name' => 'projects/test/locations/europe-west2/agent/sessions/real-test/contexts/start_pcma-followup',
                    'lifespanCount' => 5,
                    'parameters' => []
                ]
            ]
        ]
    ];
    
    $sessionId = 'real-test-' . uniqid();
    $step = 1;
    
    foreach ($conversation as $turn) {
        echo "\n   📝 Tour $step - Intent: {$turn['intent']}\n";
        
        // Créer la requête Dialogflow
        $requestData = [
            'responseId' => 'test-' . uniqid(),
            'queryResult' => [
                'queryText' => 'Test conversation',
                'action' => $turn['intent'],
                'intent' => ['displayName' => $turn['intent']],
                'parameters' => $turn['parameters'],
                'allRequiredParamsPresent' => true,
                'fulfillmentText' => '...',
                'fulfillmentMessages' => [['text' => ['text' => ['...']]]],
                'outputContexts' => $turn['contexts'],
                'intentDetectionConfidence' => 0.95,
                'languageCode' => 'fr'
            ],
            'originalDetectIntentRequest' => [
                'source' => 'GOOGLE_HOME',
                'payload' => []
            ],
            'session' => "projects/test/locations/europe-west2/agent/sessions/$sessionId"
        ];
        
        // Envoyer la requête au webhook
        $request = \Illuminate\Http\Request::create(
            '/api/google-assistant/webhook',
            'POST',
            $requestData
        );
        
        $response = $app->handle($request);
        $content = $response->getContent();
        
        echo "      ✅ Status: " . $response->getStatusCode() . "\n";
        
        // Extraire la réponse
        $data = json_decode($content, true);
        if ($data && isset($data['fulfillmentText'])) {
            $responseText = $data['fulfillmentText'];
            echo "      💬 Réponse: " . substr($responseText, 0, 80) . "...\n";
        } else {
            echo "      ❌ Réponse invalide\n";
        }
        
        $step++;
        
        // Pause entre les tours
        usleep(500000); // 0.5 seconde
    }
    
    echo "\n2. 🔍 Vérification de la session finale...\n";
    
    // Vérifier que la session finale contient toutes les données
    $session = \App\Models\VoiceSession::where('dialogflow_session', $sessionId)->first();
    
    if ($session) {
        echo "   ✅ Session trouvée dans la base de données\n";
        echo "   👤 Nom: " . ($session->player_name ?? 'Non défini') . "\n";
        echo "   📊 Données: " . json_encode($session->session_data) . "\n";
        echo "   🏷️  Status: " . $session->status . "\n";
        
        // Nettoyer la session de test
        $session->delete();
        echo "   🧹 Session de test supprimée\n";
    } else {
        echo "   ❌ Session non trouvée dans la base de données\n";
    }
    
    echo "\n=== Test terminé ===\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

