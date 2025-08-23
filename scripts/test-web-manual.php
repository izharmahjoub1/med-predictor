<?php
/**
 * Test manuel de l'interface web de fallback PCMA
 * Crée une session et affiche l'URL à tester manuellement
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

echo "=== Test manuel de l'interface web de fallback ===\n\n";

try {
    // 1. Créer une session de test
    echo "1. Création d'une session de test...\n";
    
    $session = new \App\Models\VoiceSession();
    $session->user_id = 1;
    $session->player_name = 'Ahmed Test';
    $session->current_field = 'position';
    $session->session_data = [
        'player_name' => 'Ahmed Test',
        'age' => 26,
        'position' => 'Milieu'
    ];
    $session->language = 'fr';
    $session->status = 'active';
    $session->error_count = 3;
    $session->fallback_requested = true;
    $session->dialogflow_session = 'test-manual-' . uniqid();
    $session->save();
    
    echo "   ✓ Session créée avec ID: " . $session->id . "\n";
    echo "   ✓ Dialogflow Session ID: " . $session->dialogflow_session . "\n";
    echo "   ✓ Player Name: " . $session->player_name . "\n\n";
    
    // 2. Tester l'API de session
    echo "2. Test de l'API de session...\n";
    
    $request = \Illuminate\Http\Request::create(
        "/api/google-assistant/session/{$session->dialogflow_session}",
        'GET'
    );
    
    $response = $app->handle($request);
    $content = $response->getContent();
    
    echo "   ✓ Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Contenu: " . substr($content, 0, 300) . "...\n\n";
    
    // 3. Afficher l'URL à tester manuellement
    echo "3. Test manuel de l'interface web:\n";
    echo "   🌐 Ouvrez cette URL dans votre navigateur:\n";
    echo "   http://localhost:8000/pcma/voice-fallback?session={$session->dialogflow_session}\n\n";
    
    echo "   📱 Ou testez l'API directement:\n";
    echo "   curl http://localhost:8000/api/google-assistant/session/{$session->dialogflow_session}\n\n";
    
    echo "   ⚠️  ATTENTION: Ne fermez pas ce terminal ! La session sera supprimée à la fin.\n";
    echo "   Appuyez sur Entrée quand vous avez fini de tester...\n";
    
    // Attendre l'input de l'utilisateur
    $handle = fopen("php://stdin", "r");
    fgets($handle);
    fclose($handle);
    
    echo "\n=== Test terminé ===\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Nettoyer la session de test
if (isset($session)) {
    $session->delete();
    echo "\n🧹 Session de test supprimée\n";
}

