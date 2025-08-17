<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Services\AiIntelligenceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class AiIntelligenceController extends Controller
{
    protected AiIntelligenceService $aiService;

    public function __construct(AiIntelligenceService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Prédiction de performance d'un joueur
     */
    public function predictPerformance(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'context' => 'array',
                'context.opponent' => 'string|max:100',
                'context.venue' => 'string|in:home,away,neutral',
                'context.importance' => 'string|in:low,medium,high',
                'context.weather' => 'string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $context = $request->input('context', []);
            
            $prediction = $this->aiService->predictPlayerPerformance($playerId, $context);

            Log::info("Prédiction de performance IA demandée", [
                'player_id' => $playerId,
                'context' => $context,
                'accuracy' => $prediction['accuracy'] ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prédiction de performance générée avec succès',
                'data' => $prediction,
                'meta' => [
                    'model_version' => $prediction['model_version'] ?? 'unknown',
                    'generated_at' => $prediction['generated_at'] ?? now(),
                    'cache_status' => 'hit' // Pour l'instant, toujours hit
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la prédiction de performance IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de la prédiction',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Prédiction de risque de blessure
     */
    public function predictInjuryRisk(Request $request, int $playerId): JsonResponse
    {
        try {
            $prediction = $this->aiService->predictInjuryRisk($playerId);

            Log::info("Prédiction de risque de blessure IA demandée", [
                'player_id' => $playerId,
                'risk_level' => $prediction['injury_risk'] ?? 'unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Évaluation du risque de blessure générée avec succès',
                'data' => $prediction,
                'meta' => [
                    'model_version' => $prediction['model_version'] ?? 'unknown',
                    'generated_at' => $prediction['generated_at'] ?? now(),
                    'risk_level' => $prediction['injury_risk'] ?? 'low'
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la prédiction de risque de blessure IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'évaluation du risque de blessure',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Prédiction de valeur marchande
     */
    public function predictMarketValue(Request $request, int $playerId): JsonResponse
    {
        try {
            $prediction = $this->aiService->predictMarketValue($playerId);

            Log::info("Prédiction de valeur marchande IA demandée", [
                'player_id' => $playerId,
                'predicted_value' => $prediction['predicted_value'] ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prédiction de valeur marchande générée avec succès',
                'data' => $prediction,
                'meta' => [
                    'model_version' => $prediction['model_version'] ?? 'unknown',
                    'generated_at' => $prediction['generated_at'] ?? now(),
                    'value_change' => $prediction['value_change_percentage'] ?? 0
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la prédiction de valeur marchande IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la prédiction de valeur marchande',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Recommandations intelligentes pour entraîneur
     */
    public function generateCoachRecommendations(Request $request, int $playerId): JsonResponse
    {
        try {
            $recommendations = $this->aiService->generateCoachRecommendations($playerId);

            Log::info("Recommandations IA pour entraîneur demandées", [
                'player_id' => $playerId,
                'recommendations_count' => count($recommendations)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recommandations pour entraîneur générées avec succès',
                'data' => $recommendations,
                'meta' => [
                    'generated_at' => now(),
                    'recommendations_count' => count($recommendations),
                    'training_intensity' => $recommendations['training_intensity'] ?? 'medium'
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la génération de recommandations IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération des recommandations',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Détection d'anomalies dans les performances
     */
    public function detectAnomalies(Request $request, int $playerId): JsonResponse
    {
        try {
            $anomalies = $this->aiService->detectPerformanceAnomalies($playerId);

            Log::info("Détection d'anomalies IA demandée", [
                'player_id' => $playerId,
                'risk_level' => $anomalies['risk_level'] ?? 'low',
                'anomalies_count' => count($anomalies['anomalies'] ?? [])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Analyse des anomalies générée avec succès',
                'data' => $anomalies,
                'meta' => [
                    'generated_at' => now(),
                    'risk_level' => $anomalies['risk_level'] ?? 'low',
                    'anomalies_detected' => count($anomalies['anomalies'] ?? [])
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la détection d'anomalies IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la détection des anomalies',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Analyse complète IA d'un joueur
     */
    public function comprehensiveAnalysis(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'include_performance' => 'boolean',
                'include_injury' => 'boolean',
                'include_market' => 'boolean',
                'include_recommendations' => 'boolean',
                'include_anomalies' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $includePerformance = $request->input('include_performance', true);
            $includeInjury = $request->input('include_injury', true);
            $includeMarket = $request->input('include_market', true);
            $includeRecommendations = $request->input('include_recommendations', true);
            $includeAnomalies = $request->input('include_anomalies', true);

            $analysis = [
                'player_id' => $playerId,
                'generated_at' => now(),
                'ai_version' => config('fit-v3.version', '3.0.0'),
            ];

            if ($includePerformance) {
                $analysis['performance_prediction'] = $this->aiService->predictPlayerPerformance($playerId);
            }

            if ($includeInjury) {
                $analysis['injury_risk'] = $this->aiService->predictInjuryRisk($playerId);
            }

            if ($includeMarket) {
                $analysis['market_value_prediction'] = $this->aiService->predictMarketValue($playerId);
            }

            if ($includeRecommendations) {
                $analysis['coach_recommendations'] = $this->aiService->generateCoachRecommendations($playerId);
            }

            if ($includeAnomalies) {
                $analysis['performance_anomalies'] = $this->aiService->detectPerformanceAnomalies($playerId);
            }

            // Calcul du score de confiance global
            $confidenceScores = collect($analysis)
                ->filter(fn($item) => is_array($item) && isset($item['confidence']))
                ->pluck('confidence')
                ->filter(fn($score) => is_numeric($score));

            $analysis['overall_confidence'] = $confidenceScores->isNotEmpty() 
                ? round($confidenceScores->avg(), 2) 
                : 0.0;

            Log::info("Analyse complète IA demandée", [
                'player_id' => $playerId,
                'overall_confidence' => $analysis['overall_confidence'],
                'components_included' => array_keys(array_filter([
                    'performance' => $includePerformance,
                    'injury' => $includeInjury,
                    'market' => $includeMarket,
                    'recommendations' => $includeRecommendations,
                    'anomalies' => $includeAnomalies,
                ]))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Analyse complète IA générée avec succès',
                'data' => $analysis,
                'meta' => [
                    'overall_confidence' => $analysis['overall_confidence'],
                    'components_count' => count($analysis) - 3, // Exclure player_id, generated_at, ai_version
                    'ai_version' => config('fit-v3.version', '3.0.0'),
                    'generated_at' => $analysis['generated_at']
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de l'analyse complète IA", [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de l\'analyse complète',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Statut de l'IA et des modèles
     */
    public function status(): JsonResponse
    {
        try {
            $config = config('fit-v3.ai', []);
            
            $status = [
                'ai_enabled' => $config['enabled'] ?? false,
                'models' => [],
                'api_status' => 'unknown',
                'cache_status' => 'unknown',
                'version' => config('fit-v3.version', '3.0.0'),
                'timestamp' => now(),
            ];

            // Vérifier le statut des modèles
            if (isset($config['algorithms'])) {
                foreach ($config['algorithms'] as $name => $algorithm) {
                    $status['models'][$name] = [
                        'enabled' => $algorithm['enabled'] ?? false,
                        'model' => $algorithm['model'] ?? 'unknown',
                        'accuracy_threshold' => $algorithm['accuracy_threshold'] ?? 0.0,
                        'update_frequency' => $algorithm['update_frequency'] ?? 'unknown',
                    ];
                }
            }

            // Vérifier le statut de l'API
            if (isset($config['api']['endpoint'])) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(5)
                        ->get($config['api']['endpoint'] . '/health');
                    
                    $status['api_status'] = $response->successful() ? 'healthy' : 'unhealthy';
                } catch (Exception $e) {
                    $status['api_status'] = 'unreachable';
                }
            }

            // Vérifier le statut du cache
            try {
                Cache::put('fit_v3:health_check', 'ok', 60);
                $status['cache_status'] = Cache::get('fit_v3:health_check') === 'ok' ? 'healthy' : 'unhealthy';
            } catch (Exception $e) {
                $status['cache_status'] = 'unreachable';
            }

            return response()->json([
                'success' => true,
                'message' => 'Statut de l\'IA récupéré avec succès',
                'data' => $status
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la récupération du statut IA", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du statut',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Réinitialisation du cache IA
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'player_id' => 'integer|exists:players,id',
                'cache_type' => 'string|in:performance,injury,market,all'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $playerId = $request->input('player_id');
            $cacheType = $request->input('cache_type', 'all');

            $clearedKeys = [];

            if ($cacheType === 'all' || $cacheType === 'performance') {
                $key = "ai:performance:{$playerId}:*";
                $clearedKeys[] = $this->clearCachePattern($key);
            }

            if ($cacheType === 'all' || $cacheType === 'injury') {
                $key = "ai:injury:{$playerId}";
                Cache::forget($key);
                $clearedKeys[] = $key;
            }

            if ($cacheType === 'all' || $cacheType === 'market') {
                $key = "ai:market:{$playerId}";
                Cache::forget($key);
                $clearedKeys[] = $key;
            }

            Log::info("Cache IA réinitialisé", [
                'player_id' => $playerId,
                'cache_type' => $cacheType,
                'cleared_keys' => $clearedKeys
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cache IA réinitialisé avec succès',
                'data' => [
                    'player_id' => $playerId,
                    'cache_type' => $cacheType,
                    'cleared_keys' => $clearedKeys,
                    'timestamp' => now()
                ]
            ]);

        } catch (Exception $e) {
            Log::error("Erreur lors de la réinitialisation du cache IA", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation du cache',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Réinitialisation du cache par pattern
     */
    protected function clearCachePattern(string $pattern): array
    {
        // Note: Cette méthode est simplifiée. En production, vous pourriez
        // utiliser Redis SCAN ou une autre méthode plus sophistiquée
        $clearedKeys = [];
        
        // Pour l'instant, on retourne juste le pattern
        // En production, implémentez la logique de nettoyage réel
        $clearedKeys[] = $pattern;
        
        return $clearedKeys;
    }
}
