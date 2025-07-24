<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Club;
use App\Models\Team;
use App\Models\Player;
use App\Models\TeamPlayer;

echo "=== FIXING CLUBS AND PLAYERS ===\n\n";

// Temporarily disable ClubObserver to avoid relationship errors
Club::unsetEventDispatcher();

// Step 1: Create missing Premier League clubs
echo "Step 1: Creating missing Premier League clubs...\n";

$clubs = [
    ['Arsenal', 'ARS'],
    ['Aston Villa', 'AVL'],
    ['Bournemouth', 'BOU'],
    ['Brentford', 'BRE'],
    ['Brighton & Hove Albion', 'BHA'],
    ['Burnley', 'BUR'],
    ['Chelsea', 'CHE'],
    ['Crystal Palace', 'CRY'],
    ['Everton', 'EVE'],
    ['Fulham', 'FUL'],
    ['Liverpool', 'LIV'],
    ['Luton Town', 'LUT'],
    ['Manchester City', 'MCI'],
    ['Newcastle United', 'NEW'],
    ['Nottingham Forest', 'NFO'],
    ['Sheffield United', 'SHU'],
    ['Tottenham Hotspur', 'TOT'],
    ['West Ham United', 'WHU'],
    ['Wolverhampton Wanderers', 'WOL']
];

foreach ($clubs as $club) {
    if (!Club::where('name', $club[0])->exists()) {
        Club::create([
            'name' => $club[0],
            'short_name' => $club[1],
            'country' => 'England',
            'city' => 'Unknown',
            'league' => 'Premier League',
            'division' => '1',
            'status' => 'active'
        ]);
        echo "Created: {$club[0]}\n";
    } else {
        echo "Exists: {$club[0]}\n";
    }
}

echo "\nStep 2: Updating teams to point to correct clubs...\n";

// Step 2: Update teams to point to correct clubs
$teamUpdates = [
    'Arsenal' => 'Arsenal',
    'Aston Villa' => 'Aston Villa',
    'Bournemouth' => 'Bournemouth',
    'Brentford' => 'Brentford',
    'Brighton & Hove Albion' => 'Brighton & Hove Albion',
    'Burnley' => 'Burnley',
    'Chelsea' => 'Chelsea',
    'Crystal Palace' => 'Crystal Palace',
    'Everton' => 'Everton',
    'Fulham' => 'Fulham',
    'Liverpool' => 'Liverpool',
    'Luton Town' => 'Luton Town',
    'Manchester City' => 'Manchester City',
    'Newcastle United' => 'Newcastle United',
    'Nottingham Forest' => 'Nottingham Forest',
    'Sheffield United' => 'Sheffield United',
    'Tottenham Hotspur' => 'Tottenham Hotspur',
    'West Ham United' => 'West Ham United',
    'Wolverhampton Wanderers' => 'Wolverhampton Wanderers',
    'Manchester United' => 'Manchester United'
];

foreach ($teamUpdates as $teamName => $clubName) {
    $club = Club::where('name', $clubName)->first();
    if ($club) {
        $updated = Team::where('name', $teamName)->update(['club_id' => $club->id]);
        if ($updated > 0) {
            echo "Updated team '{$teamName}' to club '{$clubName}'\n";
        }
    }
}

echo "\nStep 3: Updating players to point to correct clubs...\n";

// Step 3: Update players to point to correct clubs based on their team
$updatedPlayers = 0;
$teamPlayers = TeamPlayer::with(['team', 'player'])->get();

foreach ($teamPlayers as $teamPlayer) {
    if ($teamPlayer->team && $teamPlayer->player) {
        $teamPlayer->player->update(['club_id' => $teamPlayer->team->club_id]);
        $updatedPlayers++;
    }
}

echo "Updated {$updatedPlayers} players to correct clubs\n";

echo "\nStep 4: Verifying the fix...\n";

// Step 4: Verify the fix
$clubDistribution = Club::withCount('players')->get();

foreach ($clubDistribution as $club) {
    echo "{$club->name}: {$club->players_count} players\n";
}

echo "\n=== FIX COMPLETED ===\n"; 