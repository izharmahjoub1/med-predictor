<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Club;
use App\Models\Team;
use App\Models\Player;
use App\Models\TeamPlayer;

echo "=== REDISTRIBUTING ALL PLAYERS ACROSS CLUBS ===\n\n";

// Temporarily disable observers to avoid relationship errors
Club::unsetEventDispatcher();
Team::unsetEventDispatcher();
Player::unsetEventDispatcher();
TeamPlayer::unsetEventDispatcher();

// First, clear all existing team player assignments
echo "Clearing existing team player assignments...\n";
TeamPlayer::truncate();
echo "Cleared all team player assignments\n\n";

// Get all Premier League clubs
$clubs = Club::where('league', 'Premier League')->orderBy('name')->get();
echo "Found " . $clubs->count() . " Premier League clubs\n\n";

// Get all players in the database
$allPlayers = Player::all();
echo "Found " . $allPlayers->count() . " players to redistribute\n\n";

// Calculate players per club (base + remainder distributed)
$totalClubs = $clubs->count();
$totalPlayers = $allPlayers->count();
$basePlayersPerClub = intdiv($totalPlayers, $totalClubs);
$remainder = $totalPlayers % $totalClubs;

echo "Base players per club: {$basePlayersPerClub}\n";
echo "Remainder to distribute: {$remainder}\n\n";

$playerIndex = 0;

foreach ($clubs as $clubIndex => $club) {
    echo "Processing {$club->name}...\n";
    
    // Calculate how many players this club should get
    $playersForThisClub = $basePlayersPerClub;
    if ($remainder > 0) {
        $playersForThisClub++;
        $remainder--;
    }
    
    echo "  Assigning {$playersForThisClub} players\n";
    
    // Get the team for this club
    $team = $club->teams->first();
    if (!$team) {
        echo "  Creating team for {$club->name}\n";
        $team = Team::create([
            'name' => $club->name,
            'club_id' => $club->id,
            'type' => 'first_team',
            'formation' => '4-4-2',
            'season' => '2024-25',
            'status' => 'active'
        ]);
    }
    
    // Assign players to this club
    for ($i = 0; $i < $playersForThisClub && $playerIndex < $allPlayers->count(); $i++) {
        $player = $allPlayers[$playerIndex];
        
        // Update player's club
        $player->update(['club_id' => $club->id]);
        
        // Create team player assignment
        TeamPlayer::create([
            'team_id' => $team->id,
            'player_id' => $player->id,
            'role' => 'substitute', // Default role
            'joined_date' => now(),
            'status' => 'active'
        ]);
        
        $playerIndex++;
    }
    
    echo "  Completed {$club->name}\n\n";
}

echo "Step 2: Verifying the redistribution...\n\n";

// Verify the redistribution
$clubDistribution = Club::withCount('players')->where('league', 'Premier League')->orderBy('name')->get();

foreach ($clubDistribution as $club) {
    echo "{$club->name}: {$club->players_count} players\n";
}

echo "\nStep 3: Checking team player assignments...\n";
$totalTeamPlayers = TeamPlayer::count();
echo "Total team player assignments: {$totalTeamPlayers}\n";

echo "\n=== REDISTRIBUTION COMPLETED ===\n"; 