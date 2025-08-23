<?php

// Script de modification du GoogleAssistantController avec logging
$filePath = 'app/Http/Controllers/GoogleAssistantController.php';

$newContent = '<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhisperService;
use App\Models\VoiceSession;
use App\Services\PcmaMappingService;
use App\Events\PcmaVoiceCompleted;

class GoogleAssistantController extends Controller
{
    protected $whisperService;
    protected $pcmaMappingService;

    public function __construct(WhisperService $whisperService, PcmaMappingService $pcmaMappingService)
    {
        $this->whisperService = $whisperService;
        $this->pcmaMappingService = $pcmaMappingService;
    }

    public function handleIntent(Request $request)
    {
        try {
            $intent = $request->input("queryResult.intent.displayName");
            $parameters = $request->input("queryResult.parameters", []);
            $contexts = $request->input("queryResult.outputContexts", []);
            
            \Log::info("=== WEBHOOK DEBUG ===");
            \Log::info("Intent:", ["intent" => $intent]);
            \Log::info("Parameters:", $parameters);
            \Log::info("Contexts:", $contexts);
            
            $response = $this->generateContextualResponse($intent, $parameters, $contexts);
            
            \Log::info("Response generated:", ["response" => $response]);
            
            return response()->json([
                "fulfillmentText" => $response,
                "fulfillmentMessages" => [
                    [
                        "text" => [
                            "text" => [$response]
                        ]
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error in handleIntent:", ["error" => $e->getMessage()]);
            return $this->fallbackToWhisper($request);
        }
    }

    private function generateContextualResponse($intent, $parameters, $contexts)
    {
        \Log::info("generateContextualResponse called:", ["intent" => $intent]);
        
        if ($intent === "answer_field") {
            return $this->generateAnswerFieldResponse($parameters);
        }
        
        if ($intent === "start_pcma") {
            return "Parfait ! Commençons le formulaire PCMA. Quel est le nom du joueur ?";
        }
        
        return "Je ne comprends pas cette intention.";
    }

    private function generateAnswerFieldResponse($parameters)
    {
        \Log::info("=== generateAnswerFieldResponse DEBUG ===");
        \Log::info("Raw parameters:", $parameters);
        
        $playerName = $parameters["player_name"] ?? null;
        $age = $parameters["age"] ?? null;
        $position = $parameters["position"] ?? null;
        
        \Log::info("Extracted values:", [
            "playerName" => $playerName,
            "age" => $age,
            "position" => $position
        ]);
        
        if ($playerName && !$age) {
            \Log::info("✅ Condition 1 met: playerName exists, age missing");
            return "Parfait ! $playerName est enregistré. Maintenant, dites-moi son âge.";
        }
        
        if ($playerName && $age && !$position) {
            \Log::info("✅ Condition 2 met: playerName and age exist, position missing");
            return "Excellent ! $playerName a $age ans. Maintenant, dites-moi sa position sur le terrain.";
        }
        
        if ($playerName && $age && $position) {
            \Log::info("✅ Condition 3 met: all parameters exist");
            return "Parfait ! Récapitulons : $playerName a $age ans et joue $position. Voulez-vous continuer avec les antécédents médicaux ?";
        }
        
        \Log::info("❌ No conditions met, using default response");
        return "Parfait ! Maintenant, dites-moi la prochaine information.";
    }

    public function fallbackToWhisper(Request $request)
    {
        try {
            return response()->json([
                "fulfillmentText" => "Je n\'ai pas compris. Pouvez-vous répéter ?",
                "fulfillmentMessages" => [
                    [
                        "text" => [
                            "text" => ["Je n\'ai pas compris. Pouvez-vous répéter ?"]
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "fulfillmentText" => "Erreur technique. Utilisez l\'interface web.",
                "fulfillmentMessages" => [
                    [
                        "text" => [
                            "text" => ["Erreur technique. Utilisez l\'interface web."]
                        ]
                    ]
                ]
            ]);
        }
    }

    public function health()
    {
        return response()->json(["status" => "healthy"]);
    }

    public function test(Request $request)
    {
        return response()->json([
            "message" => "Google Assistant webhook test successful",
            "request_data" => $request->all()
        ]);
    }
}
';

// Remplacer le contenu du fichier
if (file_put_contents($filePath, $newContent)) {
    echo "✅ GoogleAssistantController.php a été modifié avec logging de débogage !\n";
    echo "📁 Fichier modifié : $filePath\n";
    echo "\n🔍 Maintenant testez et regardez les logs Laravel !\n";
    echo " Commande pour voir les logs : tail -f storage/logs/laravel.log\n";
} else {
    echo "❌ Erreur lors de la modification du fichier\n";
}

echo "\n🚀 Testez l'intent 'answer_field' et regardez les logs !\n";
?>
EOF
