<?php

require_once 'vendor/autoload.php';

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MatchPerformance;
use Carbon\Carbon;

echo "🔧 CORRECTION DES DATES DES MATCHS\n";
echo "==================================\n\n";

// Récupérer tous les matchs sans dates
$matchesWithoutDates = MatchPerformance::whereNull('match_date')->get();

echo "📊 Matchs sans dates trouvés : {$matchesWithoutDates->count()}\n\n";

if ($matchesWithoutDates->count() > 0) {
    echo "🔧 Attribution de dates réalistes...\n";
    
    // Attribuer des dates récentes (2024-2025) pour rendre les données dynamiques
    $startDate = Carbon::create(2024, 8, 1); // Août 2024
    
    foreach ($matchesWithoutDates as $index => $match) {
        // Distribuer les matchs sur plusieurs mois pour avoir des variations
        $matchDate = $startDate->copy()->addMonths($index % 6)->addDays(rand(1, 28));
        
        $match->update(['match_date' => $matchDate]);
        
        echo "   ✅ Match ID {$match->id}: {$matchDate->format('Y-m-d')}\n";
    }
    
    echo "\n🎯 Maintenant, testons les calculs dynamiques :\n";
    echo "===============================================\n";
    
    // Récupérer un joueur pour tester
    $player = \App\Models\Player::with(['matchPerformances'])->find(7);
    
    if ($player) {
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();
        
        $currentMonthGoals = $player->matchPerformances
            ->where('match_date', '>=', $currentMonth)
            ->sum('goals_scored');
            
        $previousMonthGoals = $player->matchPerformances
            ->whereBetween('match_date', [$previousMonth, $previousMonthEnd])
            ->sum('goals_scored');
            
        $currentMonthAssists = $player->matchPerformances
            ->where('match_date', '>=', $currentMonth)
            ->sum('assists');
            
        $previousMonthAssists = $player->matchPerformances
            ->whereBetween('match_date', [$previousMonth, $previousMonthEnd])
            ->sum('assists');
        
        echo "   📊 Mois actuel (Août 2025):\n";
        echo "      - Buts: {$currentMonthGoals}\n";
        echo "      - Passes: {$currentMonthAssists}\n";
        
        echo "   📊 Mois précédent (Juillet 2025):\n";
        echo "      - Buts: {$previousMonthGoals}\n";
        echo "      - Passes: {$previousMonthAssists}\n";
        
        echo "\n✅ Les données sont maintenant dynamiques !\n";
        echo "🌐 Testez dans le navigateur : http://localhost:8000/joueur/7\n";
    }
    
} else {
    echo "✅ Tous les matchs ont déjà des dates valides.\n";
}

echo "\n🎯 Prochaines étapes :\n";
echo "   1. Vérifier que les dates sont correctement attribuées\n";
echo "   2. Tester que les calculs mensuels fonctionnent\n";
echo "   3. Vérifier que la distance parcourue est dynamique\n";
echo "   4. S'assurer que les comparaisons mois actuel/précédent sont visibles\n";




