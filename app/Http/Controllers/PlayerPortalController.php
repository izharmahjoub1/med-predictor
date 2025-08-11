<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\HealthRecord;
use App\Models\PCMA;
use App\Models\MedicalPrediction;
use App\Models\Club;
use App\Models\Association;
use App\Models\MatchModel;
use App\Models\Performance;
use App\Services\PlayerPerformanceService;
use App\Services\PlayerNotificationService;
use App\Services\PlayerHealthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlayerPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:player');
    }

    /**
     * Dashboard principal du joueur
     */
    public function dashboard(): View
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder au portail joueur.');
            }
            
            $player = $user->player;
            
            if (!$player) {
                return redirect()->route('login')->with('error', 'Aucun joueur associé à votre compte. Contactez l\'administrateur.');
            }

            // Version simplifiée pour debug
            $stats = [
                'total_health_records' => $player->healthRecords->count(),
                'total_pcma' => $player->pcmas->count(),
                'total_predictions' => $player->medicalPredictions->count(),
                'total_performances' => $player->performances->count(),
                'last_health_check' => null,
                'next_pcma_due' => 'Non disponible',
                'recent_matches' => [],
                'health_score' => 75,
            ];

            return view('player-portal.dashboard', compact('player', 'stats'));
        } catch (\Exception $e) {
            \Log::error('PlayerPortalController dashboard error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Erreur lors du chargement du portail joueur: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard FIFA Ultimate avec performances détaillées
     */
    public function fifaUltimateDashboard(): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder au portail joueur.');
            }
            
            $player = $user->player;
            
            if (!$player) {
                return redirect()->route('login')->with('error', 'Aucun joueur associé à votre compte. Contactez l\'administrateur.');
            }

            $player->load(['club', 'association', 'healthRecords', 'pcmas', 'performances']);

            // Utiliser le service de performance pour calculer les statistiques réelles
            $performanceService = new PlayerPerformanceService($player);
            $performanceData = [
                'offensiveStats' => $performanceService->getOffensiveStats(),
                'physicalStats' => $performanceService->getPhysicalStats(),
                'technicalStats' => $performanceService->getTechnicalStats(),
                'performanceEvolution' => $performanceService->getPerformanceEvolution(),
                'seasonSummary' => $performanceService->getSeasonSummary()
            ];

            // Utiliser le service de notifications pour les données réelles
            $notificationService = new PlayerNotificationService($player);
            $notificationData = [
                'nationalTeam' => $notificationService->getNationalTeamNotifications(),
                'trainingSessions' => $notificationService->getTrainingNotifications(),
                'matches' => $notificationService->getMatchNotifications(),
                'medicalAppointments' => $notificationService->getMedicalNotifications(),
                'socialMedia' => $notificationService->getSocialMediaNotifications()
            ];

            // Utiliser le service de santé pour les données réelles
            $healthService = new PlayerHealthService($player);
            $healthData = $healthService->getHealthData();

            return view('player-portal/fifa-ultimate-optimized', compact('player', 'performanceData', 'notificationData', 'healthData'));
        } catch (\Exception $e) {
            \Log::error('PlayerPortalController fifaUltimateDashboard error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Erreur lors du chargement du portail FIFA Ultimate: ' . $e->getMessage());
        }
    }

    /**
     * Profil détaillé du joueur
     */
    public function profile(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $player->load(['club', 'association', 'healthRecords', 'pcmas']);

        return view('player-portal.profile', compact('player'));
    }

    /**
     * Dossiers médicaux du joueur
     */
    public function medicalRecords(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $healthRecords = $player->healthRecords()
            ->with(['user'])
            ->orderBy('record_date', 'desc')
            ->paginate(10);

        $pcmas = $player->pcmas()
            ->orderBy('assessment_date', 'desc')
            ->paginate(10);

        return view('player-portal.medical-records', compact('player', 'healthRecords', 'pcmas'));
    }

    /**
     * Prédictions médicales du joueur
     */
    public function predictions(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $predictions = $player->medicalPredictions()
            ->with(['healthRecord', 'user'])
            ->orderBy('prediction_date', 'desc')
            ->paginate(10);

        return view('player-portal.predictions', compact('player', 'predictions'));
    }

    /**
     * Performances du joueur
     */
    public function performances(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $performances = $player->performances()
            ->orderBy('match_date', 'desc')
            ->paginate(10);

        return view('player-portal.performances', compact('player', 'performances'));
    }

    /**
     * Matchs du joueur
     */
    public function matches(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $matches = MatchModel::where(function($query) use ($player) {
            $query->where('home_team_id', $player->club_id)
                  ->orWhere('away_team_id', $player->club_id);
        })
        ->with(['homeTeam', 'awayTeam', 'competition'])
        ->orderBy('match_date', 'desc')
        ->paginate(10);

        return view('player-portal.matches', compact('player', 'matches'));
    }

    /**
     * Documents du joueur
     */
    public function documents(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        return view('player-portal.documents', compact('player'));
    }

    /**
     * Paramètres du joueur
     */
    public function settings(): View
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        return view('player-portal.settings', compact('player'));
    }

    /**
     * Mettre à jour le profil du joueur
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $player = Auth::user()->player;
        
        if (!$player) {
            return redirect()->route('login')->with('error', 'Accès non autorisé au portail joueur.');
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'preferences' => 'nullable|json',
        ]);

        $player->update($validated);

        return redirect()->route('player-portal.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Calculer le score de santé du joueur
     */
    private function calculateHealthScore(Player $player): int
    {
        $score = 100;
        
        // Réduire le score basé sur les problèmes de santé récents
        $recentHealthIssues = $player->healthRecords()
            ->where('record_date', '>=', now()->subMonths(3))
            ->where('risk_score', '>', 0.5)
            ->count();
        
        $score -= ($recentHealthIssues * 10);
        
        // Réduire le score si pas de PCMA récent
        $lastPCMA = $player->pcmas()
            ->where('assessment_date', '>=', now()->subYear())
            ->first();
        
        if (!$lastPCMA) {
            $score -= 20;
        }
        
        return max(0, $score);
    }

    /**
     * Obtenir la prochaine PCMA due
     */
    private function getNextPCMADue(Player $player): ?string
    {
        $lastPCMA = $player->pcmas()
            ->orderBy('assessment_date', 'desc')
            ->first();
        
        if (!$lastPCMA) {
            return 'Aucune PCMA effectuée';
        }
        
        $nextDue = $lastPCMA->assessment_date->addYear();
        
        if ($nextDue->isPast()) {
            return 'PCMA en retard';
        }
        
        return $nextDue->format('d/m/Y');
    }

    /**
     * Obtenir les matchs récents du joueur
     */
    private function getRecentMatches(Player $player): array
    {
        return MatchModel::where(function($query) use ($player) {
            $query->where('home_team_id', $player->club_id)
                  ->orWhere('away_team_id', $player->club_id);
        })
        ->with(['homeTeam', 'awayTeam'])
        ->orderBy('match_date', 'desc')
        ->limit(5)
        ->get()
        ->map(function($match) {
            return [
                'id' => $match->id,
                'date' => $match->match_date->format('d/m/Y'),
                'home_team' => $match->homeTeam->name,
                'away_team' => $match->awayTeam->name,
                'score' => $match->home_score . ' - ' . $match->away_score,
                'status' => $match->status
            ];
        })
        ->toArray();
    }
}
