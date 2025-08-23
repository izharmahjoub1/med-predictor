<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Controller;
use App\Services\V3\PerformanceAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PerformanceAnalyticsController extends Controller
{
    protected PerformanceAnalyticsService $analyticsService;

    public function __construct(PerformanceAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Analyser les tendances de performance d'un joueur
     */
    public function analyzeTrends(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'metric_type' => 'required|string|max:100',
                'days' => 'integer|min:1|max:365',
                'category' => 'string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $metricType = $request->input('metric_type');
            $days = $request->input('days', 30);
            $category = $request->input('category');

            $trends = $this->analyticsService->analyzePlayerTrends($playerId, $metricType, $days);

            Log::info("Analyse des tendances V3 demandée", [
                'player_id' => $playerId,
                'metric_type' => $metricType,
                'days' => $days,
                'data_points' => $trends['data_points']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Analyse des tendances générée avec succès',
                'data' => $trends,
                'meta' => [
                    'player_id' => $playerId,
                    'metric_type' => $metricType,
                    'period_days' => $days,
                    'generated_at' => now(),
                    'version' => '3.0.0'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'analyse des tendances V3", [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse des tendances',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Comparer les performances de deux joueurs
     */
    public function comparePlayers(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'player1_id' => 'required|integer|exists:players,id',
                'player2_id' => 'required|integer|exists:players,id',
                'metric_type' => 'required|string|max:100',
                'days' => 'integer|min:1|max:365',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $player1Id = $request->input('player1_id');
            $player2Id = $request->input('player2_id');
            $metricType = $request->input('metric_type');
            $days = $request->input('days', 30);

            $comparison = $this->analyticsService->comparePlayers($player1Id, $player2Id, $metricType, $days);

            Log::info("Comparaison de performances V3 demandée", [
                'player1_id' => $player1Id,
                'player2_id' => $player2Id,
                'metric_type' => $metricType,
                'days' => $days
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comparaison de performances générée avec succès',
                'data' => $comparison,
                'meta' => [
                    'metric_type' => $metricType,
                    'period_days' => $days,
                    'generated_at' => now(),
                    'version' => '3.0.0'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur lors de la comparaison de performances V3", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la comparaison de performances',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Obtenir les métriques de performance d'un joueur
     */
    public function getPlayerMetrics(Request $request, int $playerId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'metric_type' => 'string|max:100',
                'category' => 'string|max:100',
                'days' => 'integer|min:1|max:365',
                'limit' => 'integer|min:1|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = \App\Models\V3\PerformanceMetric::where('player_id', $playerId);

            if ($request->has('metric_type')) {
                $query->where('metric_type', $request->input('metric_type'));
            }

            if ($request->has('category')) {
                $query->where('category', $request->input('category'));
            }

            if ($request->has('days')) {
                $query->where('timestamp', '>=', now()->subDays($request->input('days')));
            }

            $limit = $request->input('limit', 100);
            $metrics = $query->orderBy('timestamp', 'desc')->limit($limit)->get();

            return response()->json([
                'success' => true,
                'message' => 'Métriques de performance récupérées avec succès',
                'data' => $metrics,
                'meta' => [
                    'player_id' => $playerId,
                    'total_metrics' => $metrics->count(),
                    'filters' => $request->only(['metric_type', 'category', 'days']),
                    'version' => '3.0.0'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des métriques V3", [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des métriques',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Obtenir le résumé des performances d'un joueur
     */
    public function getPlayerSummary(int $playerId): JsonResponse
    {
        try {
            $summary = $this->analyticsService->analyzePlayerTrends($playerId, 'overall', 90);

            return response()->json([
                'success' => true,
                'message' => 'Résumé des performances généré avec succès',
                'data' => $summary,
                'meta' => [
                    'player_id' => $playerId,
                    'generated_at' => now(),
                    'version' => '3.0.0'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération du résumé V3", [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du résumé',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }
}
