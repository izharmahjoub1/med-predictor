<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Club;
use App\Models\Team;
use App\Models\Player;
use App\Models\TeamPlayer;

echo "=== FIXING PLAYER TEAM ASSIGNMENTS ===\n\n";

// Temporarily disable observers to avoid relationship errors
Club::unsetEventDispatcher();
Team::unsetEventDispatcher();
Player::unsetEventDispatcher();
TeamPlayer::unsetEventDispatcher();

// Get all clubs with their teams
$clubs = Club::with('teams')->get();

$totalAssigned = 0;

foreach ($clubs as $club) {
    echo "Processing club: {$club->name}\n";
    
    // Get the first team for this club (or create one if none exists)
    $team = $club->teams->first();
    
    if (!$team) {
        echo "  No team found for {$club->name}, creating one...\n";
        $team = Team::create([
            'name' => $club->name,
            'club_id' => $club->id,
            'type' => 'first_team',
            'formation' => '4-4-2',
            'season' => '2024-25',
            'status' => 'active'
        ]);
    }
    
    // Get all players for this club
    $players = Player::where('club_id', $club->id)->get();
    
    echo "  Found {$players->count()} players for {$club->name}\n";
    
    // Assign players to the team
    foreach ($players as $player) {
        // Check if player is already assigned to this team
        $existingAssignment = TeamPlayer::where('team_id', $team->id)
            ->where('player_id', $player->id)
            ->first();
            
        if (!$existingAssignment) {
            TeamPlayer::create([
                'team_id' => $team->id,
                'player_id' => $player->id,
                'role' => 'substitute', // Default role
                'joined_date' => now(),
                'status' => 'active'
            ]);
            $totalAssigned++;
        }
    }
    
    echo "  Assigned players to team: {$team->name}\n\n";
}

echo "Total players assigned to teams: {$totalAssigned}\n";

echo "\nStep 2: Updating players to correct clubs based on team assignments...\n";

// Now update players to point to the correct clubs based on their team
$updatedPlayers = 0;
$teamPlayers = TeamPlayer::with(['team', 'player'])->get();

foreach ($teamPlayers as $teamPlayer) {
    if ($teamPlayer->team && $teamPlayer->player) {
        $teamPlayer->player->update(['club_id' => $teamPlayer->team->club_id]);
        $updatedPlayers++;
    }
}

echo "Updated {$updatedPlayers} players to correct clubs\n";

echo "\nStep 3: Verifying the fix...\n";

// Verify the fix
$clubDistribution = Club::withCount('players')->get();

foreach ($clubDistribution as $club) {
    echo "{$club->name}: {$club->players_count} players\n";
}

echo "\n=== FIX COMPLETED ===\n"; 