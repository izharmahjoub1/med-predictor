<?php
/**
 * Test simple de l'interface web de fallback PCMA
 * Vérifie que les données de session sont correctement chargées
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

echo "=== Test simple de l'interface web de fallback ===\n\n";

try {
    // 1. Créer une session de test simple
    echo "1. Création d'une session de test...\n";
    
    $session = new \App\Models\VoiceSession();
    $session->user_id = 1;
    $session->player_name = 'Mohamed Test';
    $session->current_field = 'position';
    $session->session_data = [
        'player_name' => 'Mohamed Test',
        'age' => 24,
        'position' => 'Défenseur'
    ];
    $session->language = 'fr';
    $session->status = 'active';
    $session->error_count = 2;
    $session->fallback_requested = true;
    $session->dialogflow_session = 'test-simple-' . uniqid();
    $session->save();
    
    echo "   ✓ Session créée avec ID: " . $session->id . "\n";
    echo "   ✓ Dialogflow Session ID: " . $session->dialogflow_session . "\n";
    echo "   ✓ Player Name: " . $session->player_name . "\n\n";
    
    // 2. Tester l'interface web avec cette session
    echo "2. Test de l'interface web...\n";
    
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
        if (str_contains($html, 'Mohamed Test')) {
            echo "   ✓ Nom du joueur trouvé dans le HTML\n";
        } else {
            echo "   ✗ Nom du joueur non trouvé dans le HTML\n";
        }
        
        if (str_contains($html, '24')) {
            echo "   ✓ Âge trouvé dans le HTML\n";
        } else {
            echo "   ✗ Âge non trouvé dans le HTML\n";
        }
        
        if (str_contains($html, 'Défenseur')) {
            echo "   ✓ Position trouvée dans le HTML\n";
        } else {
            echo "   ✗ Position non trouvée dans le HTML\n";
        }
        
        // Vérifier que le JavaScript contient les bonnes données
        if (str_contains($html, 'test-simple-')) {
            echo "   ✓ Session ID trouvé dans le HTML\n";
        } else {
            echo "   ✗ Session ID non trouvé dans le HTML\n";
        }
    } else {
        echo "   ✗ Réponse non-HTML reçue\n";
    }
    
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

