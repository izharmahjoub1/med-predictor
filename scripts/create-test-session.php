<?php
/**
 * Créer une session de test pour l'interface web
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

// Créer une session
$session = new \App\Models\VoiceSession();
$session->user_id = 1;
$session->player_name = 'Test Player Live';
$session->current_field = 'position';
$session->session_data = [
    'player_name' => 'Test Player Live',
    'age' => 27,
    'position' => 'Attaquant'
];
$session->language = 'fr';
$session->status = 'active';
$session->error_count = 2;
$session->fallback_requested = true;
$session->dialogflow_session = 'live-test-' . uniqid();
$session->save();

echo "✅ Session créée avec succès!\n";
echo "🌐 URL à tester: http://localhost:8000/pcma/voice-fallback?session={$session->dialogflow_session}\n";
echo "📊 API: http://localhost:8000/api/google-assistant/session/{$session->dialogflow_session}\n";
echo "👤 Nom attendu: {$session->player_name}\n";
echo "🔢 ID de session: {$session->dialogflow_session}\n";

