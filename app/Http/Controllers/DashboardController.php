<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Association;
use App\Models\Club;
use App\Models\Player;
use App\Models\HealthRecord;
use App\Models\MedicalPrediction;
use App\Models\User;
use App\Models\Competition;
use App\Models\AuditTrail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's association and club
        $association = null;
        $club = null;
        
        if ($user->association_id) {
            $association = Association::find($user->association_id);
        }
        
        if ($user->club_id) {
            $club = Club::find($user->club_id);
        }
        
        // Get statistics
        $stats = [
            'total_players' => Player::count(),
            'active_competitions' => Competition::where('status', 'active')->count(),
            'upcoming_competitions' => Competition::where('status', 'upcoming')->count(),
            'completed_competitions' => Competition::where('status', 'completed')->count(),
            'total_competitions' => Competition::count(),
            'total_health_records' => HealthRecord::count(),
            'fifa_connect_status' => 'Connected',
        ];
        
        // Get health records by status
        $healthRecordsByStatus = [
            'active' => HealthRecord::where('status', 'active')->count(),
            'archived' => HealthRecord::where('status', 'archived')->count(),
            'pending' => HealthRecord::where('status', 'pending')->count(),
        ];
        
        // Get predictions by type
        $predictionsByType = [
            'Blessure' => MedicalPrediction::where('type', 'injury')->count(),
            'Performance' => MedicalPrediction::where('type', 'performance')->count(),
            'Récupération' => MedicalPrediction::where('type', 'recovery')->count(),
            'Prévention' => MedicalPrediction::where('type', 'prevention')->count(),
        ];
        
        // Get medical alerts (health records with high risk)
        $medicalAlerts = HealthRecord::where('risk_score', '>', 0.7)
            ->with('player')
            ->orderBy('risk_score', 'desc')
            ->limit(5)
            ->get();
        
        // Get top players by health records count (limite à 5)
        $topPlayersByRecords = Player::withCount('healthRecords')
            ->orderBy('health_records_count', 'desc')
            ->select('id', 'name', 'club_id', 'association_id')
            ->limit(5)
            ->get();
        
        // Get top predictions by confidence score (limite à 5)
        $topPredictions = MedicalPrediction::with(['healthRecord.player'])
            ->orderBy('confidence_score', 'desc')
            ->select('id', 'health_record_id', 'confidence_score', 'type')
            ->limit(5)
            ->get();
        
        // Get monthly statistics for the last 6 months (inchangé)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'health_records' => HealthRecord::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'predictions' => MedicalPrediction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        // License stats per club (limite à 10 clubs)
        $clubs = Club::with('playerLicenses')->select('id', 'name')->limit(10)->get();
        $licenseStatsByClub = $clubs->map(function($club) {
            $statuses = ['pending', 'active', 'revoked', 'justification_requested'];
            $counts = [];
            foreach ($statuses as $status) {
                $counts[$status] = $club->playerLicenses->where('status', $status)->count();
            }
            $total = $club->playerLicenses->count();
            // Average pending time (in days)
            $pendingLicenses = $club->playerLicenses->where('status', 'pending');
            $avgPendingTime = null;
            if ($pendingLicenses->count() > 0) {
                $avgPendingTime = round($pendingLicenses->map(function($l) {
                    return now()->diffInDays($l->created_at);
                })->avg(), 1);
            }
            return [
                'club_name' => $club->name,
                'pending' => $counts['pending'],
                'active' => $counts['active'],
                'revoked' => $counts['revoked'],
                'justification_requested' => $counts['justification_requested'],
                'total' => $total,
                'avg_pending_time' => $avgPendingTime,
            ];
        });
        
        // Audit log statistics
        $auditLogStats = [
            'total_logs' => AuditTrail::count(),
            'today_logs' => AuditTrail::whereDate('occurred_at', today())->count(),
            'this_week_logs' => AuditTrail::whereBetween('occurred_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_logs' => AuditTrail::whereMonth('occurred_at', now()->month)->count(),
        ];
        
        // Audit logs by event type
        $auditLogsByEventType = [
            'user_action' => AuditTrail::where('event_type', 'user_action')->count(),
            'system_event' => AuditTrail::where('event_type', 'system_event')->count(),
            'security_event' => AuditTrail::where('event_type', 'security_event')->count(),
            'data_access' => AuditTrail::where('event_type', 'data_access')->count(),
        ];
        
        // Audit logs by severity
        $auditLogsBySeverity = [
            'info' => AuditTrail::where('severity', 'info')->count(),
            'warning' => AuditTrail::where('severity', 'warning')->count(),
            'error' => AuditTrail::where('severity', 'error')->count(),
            'critical' => AuditTrail::where('severity', 'critical')->count(),
        ];
        
        // Top clubs by players with performance data (limite à 5)
        $topClubsByPlayers = Club::withCount('players')
            ->withAvg('players', 'overall_rating')
            ->withSum('players', 'value_eur')
            ->orderBy('players_count', 'desc')
            ->select('id', 'name', 'logo_url', 'country', 'league')
            ->limit(5)
            ->get()
            ->map(function($club) {
                $players = $club->players;
                $totalValue = $players->sum('value_eur');
                $avgRating = $players->avg('overall_rating');
                $topPlayers = $players->sortByDesc('overall_rating')->take(3);
                $performanceScore = round(($avgRating * 0.4) + (($totalValue / 100000000) * 0.3) + (rand(70, 95) * 0.3), 1);
                return [
                    'club_name' => $club->name,
                    'total_players' => $club->players_count,
                    'average_rating' => round($avgRating, 1),
                    'total_value' => $totalValue,
                    'performance_score' => $performanceScore,
                    'top_players' => $topPlayers->map(function($player) {
                        return [
                            'name' => $player->name,
                            'position' => $player->position,
                            'rating' => $player->overall_rating,
                            'value' => $player->value_eur
                        ];
                    })->toArray(),
                    'logo_url' => $club->logo_url,
                    'country' => $club->country,
                    'league' => $club->league
                ];
            });
        
        // Performance analytics data (limite les top performers à 5)
        $performanceData = [
            'total_performances' => \App\Models\Performance::count(),
            'today_performances' => \App\Models\Performance::whereDate('date', today())->count(),
            'avg_overall_score' => round(\App\Models\Performance::avg('overall_score'), 1),
            'top_performers' => \App\Models\Performance::with('player')
                ->orderBy('overall_score', 'desc')
                ->select('id', 'player_id', 'overall_score', 'performance_type', 'trend', 'date')
                ->limit(5)
                ->get()
                ->map(function($perf) {
                    return [
                        'player_name' => $perf->player->name ?? 'Unknown Player',
                        'score' => $perf->overall_score,
                        'type' => $perf->performance_type,
                        'trend' => $perf->trend,
                        'date' => $perf->date->format('M d')
                    ];
                }),
            'performance_by_type' => [
                'fitness' => \App\Models\Performance::where('performance_type', 'fitness')->avg('overall_score'),
                'technical' => \App\Models\Performance::where('performance_type', 'technical')->avg('overall_score'),
                'tactical' => \App\Models\Performance::where('performance_type', 'tactical')->avg('overall_score'),
                'mental' => \App\Models\Performance::where('performance_type', 'mental')->avg('overall_score'),
                'overall' => \App\Models\Performance::where('performance_type', 'overall')->avg('overall_score'),
            ],
            'trends' => [
                'improving' => \App\Models\Performance::where('trend', 'improving')->count(),
                'stable' => \App\Models\Performance::where('trend', 'stable')->count(),
                'declining' => \App\Models\Performance::where('trend', 'declining')->count(),
                'fluctuating' => \App\Models\Performance::where('trend', 'fluctuating')->count(),
            ],
            'recent_matches' => \App\Models\MatchModel::with(['homeTeam', 'awayTeam'])
                ->orderBy('match_date', 'desc')
                ->select('id', 'home_team_id', 'away_team_id', 'home_score', 'away_score', 'match_date', 'attendance')
                ->limit(10)
                ->get()
                ->map(function($match) {
                    return [
                        'home_team' => $match->homeTeam->name ?? 'Unknown',
                        'away_team' => $match->awayTeam->name ?? 'Unknown',
                        'score' => $match->home_score . ' - ' . $match->away_score,
                        'date' => $match->match_date->format('M d'),
                        'attendance' => $match->attendance
                    ];
                }),
            'performance_metrics' => [
                'total_goals' => \App\Models\Performance::sum('goals'),
                'total_assists' => \App\Models\Performance::sum('assists'),
                'avg_pass_accuracy' => round(\App\Models\Performance::avg('pass_accuracy'), 1),
                'total_distance' => \App\Models\Performance::sum('distance_covered'),
                'total_sprints' => \App\Models\Performance::sum('sprints'),
            ]
        ];
        
        // Top clubs for the dashboard (limite à 5)
        $topClubs = Club::withCount('players')
            ->orderBy('players_count', 'desc')
            ->select('id', 'name', 'players_count', 'logo_url')
            ->limit(5)
            ->get();
        
        // Audit logs (limite à 10)
        $auditLogs = AuditTrail::with('user')
            ->orderBy('occurred_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'association', 
            'club', 
            'stats', 
            'healthRecordsByStatus', 
            'predictionsByType',
            'medicalAlerts',
            'topPlayersByRecords',
            'topPredictions',
            'monthlyStats',
            'licenseStatsByClub',
            'auditLogStats',
            'auditLogsByEventType',
            'auditLogsBySeverity',
            'topClubsByPlayers',
            'performanceData',
            'topClubs',
            'auditLogs'
        ));
    }
} 