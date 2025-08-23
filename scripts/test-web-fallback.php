<?php
/**
 * Test complet de l'interface web de fallback PCMA
 * Teste le chargement des données de session et la soumission du formulaire
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

echo "=== Test de l'interface web de fallback PCMA ===\n\n";

try {
    // 1. Créer une session de test
    echo "1. Création d'une session de test...\n";
    
    $sessionData = [
        'player_name' => 'Test Player',
        'age' => 25,
        'position' => 'Attaquant',
        'current_field' => 'position',
        'status' => 'active'
    ];
    
    $session = new \App\Models\VoiceSession();
    $session->user_id = 1;
    $session->player_name = 'Test Player';
    $session->current_field = 'position';
    $session->session_data = $sessionData;
    $session->language = 'fr';
    $session->status = 'active';
    $session->error_count = 2;
    $session->fallback_requested = true;
    $session->dialogflow_session = 'test-session-' . uniqid();
    $session->save();
    
    echo "   ✓ Session créée avec ID: " . $session->id . "\n";
    echo "   ✓ Dialogflow Session ID: " . $session->dialogflow_session . "\n\n";
    
    // 2. Test de récupération des données de session...
    echo "2. Test de récupération des données de session...\n";
    
    $request = \Illuminate\Http\Request::create(
        "/api/google-assistant/session/{$session->dialogflow_session}",
        'GET'
    );
    
    $response = $app->handle($request);
    $content = $response->getContent();
    
    echo "   ✓ Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Contenu: " . substr($content, 0, 200) . "...\n";
    
    // Afficher la structure complète des données
    $data = json_decode($content, true);
    if ($data && isset($data['session'])) {
        echo "   ✓ Structure des données:\n";
        echo "     - ID: " . $data['session']['id'] . "\n";
        echo "     - Player Name: " . ($data['session']['player_name'] ?? 'null') . "\n";
        echo "     - Current Field: " . ($data['session']['current_field'] ?? 'null') . "\n";
        echo "     - Session Data: " . json_encode($data['session']['session_data'] ?? []) . "\n";
    }
    echo "\n";
    
    // 3. Tester la soumission du formulaire via l'API
    echo "3. Test de soumission du formulaire...\n";
    
    $submitData = [
        'player_name' => 'Test Player',
        'age' => 25,
        'position' => 'Attaquant',
        'session_id' => $session->dialogflow_session
    ];
    
    $request = \Illuminate\Http\Request::create(
        '/api/google-assistant/submit-pcma',
        'POST',
        $submitData
    );
    
    $response = $app->handle($request);
    $content = $response->getContent();
    
    echo "   ✓ Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Contenu: " . substr($content, 0, 200) . "...\n\n";
    
    // 4. Vérifier la mise à jour de la session
    echo "4. Vérification de la mise à jour de la session...\n";
    
    $session->refresh();
    echo "   ✓ Status final: " . $session->status . "\n";
    echo "   ✓ Completed at: " . ($session->completed_at ? $session->completed_at : 'Non') . "\n\n";
    
    // 5. Tester l'interface web directement
    echo "5. Test de l'interface web...\n";
    
    $request = \Illuminate\Http\Request::create(
        "/pcma/voice-fallback?session={$session->dialogflow_session}",
        'GET'
    );
    
    $response = $app->handle($request);
    
    echo "   ✓ Status: " . $response->getStatusCode() . "\n";
    echo "   ✓ Type de contenu: " . $response->headers->get('Content-Type') . "\n";
    
    if (str_contains($response->headers->get('Content-Type'), 'text/html')) {
        $html = $response->getContent();
        echo "   ✓ Contenu HTML généré (" . strlen($html) . " caractères)\n";
        
        // Vérifier que les données de session sont présentes dans le HTML
        if (str_contains($html, 'Test Player')) {
            echo "   ✓ Nom du joueur trouvé dans le HTML\n";
        } else {
            echo "   ✗ Nom du joueur non trouvé dans le HTML\n";
        }
        
        if (str_contains($html, '25')) {
            echo "   ✓ Âge trouvé dans le HTML\n";
        } else {
            echo "   ✗ Âge non trouvé dans le HTML\n";
        }
        
        if (str_contains($html, 'Attaquant')) {
            echo "   ✓ Position trouvée dans le HTML\n";
        } else {
            echo "   ✗ Position non trouvée dans le HTML\n";
        }
    } else {
        echo "   ✗ Réponse non-HTML reçue\n";
    }
    
    echo "\n=== Test terminé ===\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

// Nettoyer la session de test
if (isset($session)) {
    $session->delete();
    echo "\n🧹 Session de test supprimée\n";
}
