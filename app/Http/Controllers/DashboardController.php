<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\HealthRecord;
use App\Models\User;
use App\Models\Club;
use App\Models\Association;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get club and association data for the user
        $club = null;
        $association = null;
        
        if ($user->club_id) {
            $club = Club::with('association')->find($user->club_id);
        }
        
        if ($user->association_id) {
            $association = Association::find($user->association_id);
        }
        
        // If user has club but no association, get association from club
        if ($club && !$association && $club->association) {
            $association = $club->association;
        }

        // Test with three simple queries
        $stats = [
            'total_players' => Player::count(),
            'total_health_records' => HealthRecord::count(),
            'total_users' => User::count(),
            'total_predictions' => 0,
            'active_competitions' => 0,
            'fifa_connect_status' => 'Connected',
        ];

        // Empty collections for now
        $healthRecordsByStatus = [
            'active' => 0,
            'archived' => 0,
            'pending' => 0,
        ];
        $predictionsByType = [
            'Blessure' => 0,
            'Performance' => 0,
            'Récupération' => 0,
            'Prévention' => 0,
        ];
        $monthlyStats = [];
        $topPlayersByRecords = collect([]);
        $topPredictions = collect([]);
        $medicalAlerts = collect([]);

        return view('dashboard.index', compact(
            'stats',
            'healthRecordsByStatus',
            'predictionsByType',
            'monthlyStats',
            'topPlayersByRecords',
            'topPredictions',
            'medicalAlerts',
            'club',
            'association'
        ));
    }
} 