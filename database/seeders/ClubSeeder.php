<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Team;
use App\Models\Player;
use App\Models\TeamPlayer;
use App\Models\Competition;
use App\Models\GameMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('team_players')->truncate();
        DB::table('teams')->truncate();
        DB::table('clubs')->truncate();
        DB::table('game_matches')->truncate();

        // Get the English Football Association
        $englishFA = \App\Models\Association::where('name', 'The Football Association')->first();
        $associationId = $englishFA ? $englishFA->id : null;

        // Create Premier League Clubs
        $premierLeagueClubs = [
            [
                'name' => 'Manchester United',
                'short_name' => 'Man Utd',
                'country' => 'England',
                'city' => 'Manchester',
                'stadium' => 'Old Trafford',
                'founded_year' => 1878,
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg',
                'website' => 'https://www.manutd.com',
                'email' => 'info@manutd.com',
                'phone' => '+44 161 868 8000',
                'address' => 'Sir Matt Busby Way, Manchester M16 0RA',
                'fifa_connect_id' => 'MUFC001',
                'association_id' => $associationId,
                'league' => 'Premier League',
                'division' => '1',
                'status' => 'active',
                'budget_eur' => 500000000,
                'wage_budget_eur' => 200000000,
                'transfer_budget_eur' => 100000000,
                'reputation' => 95,
                'facilities_level' => 5,
                'youth_development' => 5,
                'scouting_network' => 5,
                'medical_team' => 5,
                'coaching_staff' => 5,
            ],
            [
                'name' => 'Liverpool FC',
                'short_name' => 'Liverpool',
                'country' => 'England',
                'city' => 'Liverpool',
                'stadium' => 'Anfield',
                'founded_year' => 1892,
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg',
                'website' => 'https://www.liverpoolfc.com',
                'email' => 'info@liverpoolfc.com',
                'phone' => '+44 151 260 6677',
                'address' => 'Anfield Road, Liverpool L4 0TH',
                'fifa_connect_id' => 'LFC001',
                'league' => 'Premier League',
                'division' => '1',
                'status' => 'active',
                'budget_eur' => 450000000,
                'wage_budget_eur' => 180000000,
                'transfer_budget_eur' => 80000000,
                'reputation' => 92,
                'facilities_level' => 5,
                'youth_development' => 4,
                'scouting_network' => 5,
                'medical_team' => 5,
                'coaching_staff' => 5,
            ],
            [
                'name' => 'Arsenal FC',
                'short_name' => 'Arsenal',
                'country' => 'England',
                'city' => 'London',
                'stadium' => 'Emirates Stadium',
                'founded_year' => 1886,
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/5/53/Arsenal_FC.svg',
                'website' => 'https://www.arsenal.com',
                'email' => 'info@arsenal.com',
                'phone' => '+44 20 7619 5003',
                'address' => 'Hornsey Road, London N7 7AJ',
                'fifa_connect_id' => 'AFC001',
                'league' => 'Premier League',
                'division' => '1',
                'status' => 'active',
                'budget_eur' => 400000000,
                'wage_budget_eur' => 160000000,
                'transfer_budget_eur' => 70000000,
                'reputation' => 90,
                'facilities_level' => 5,
                'youth_development' => 5,
                'scouting_network' => 4,
                'medical_team' => 5,
                'coaching_staff' => 4,
            ],
            [
                'name' => 'Chelsea FC',
                'short_name' => 'Chelsea',
                'country' => 'England',
                'city' => 'London',
                'stadium' => 'Stamford Bridge',
                'founded_year' => 1905,
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/c/cc/Chelsea_FC.svg',
                'website' => 'https://www.chelseafc.com',
                'email' => 'info@chelseafc.com',
                'phone' => '+44 20 7386 9373',
                'address' => 'Fulham Road, London SW6 1HS',
                'fifa_connect_id' => 'CFC001',
                'league' => 'Premier League',
                'division' => '1',
                'status' => 'active',
                'budget_eur' => 600000000,
                'wage_budget_eur' => 250000000,
                'transfer_budget_eur' => 150000000,
                'reputation' => 88,
                'facilities_level' => 5,
                'youth_development' => 4,
                'scouting_network' => 5,
                'medical_team' => 5,
                'coaching_staff' => 5,
            ],
            [
                'name' => 'Manchester City',
                'short_name' => 'Man City',
                'country' => 'England',
                'city' => 'Manchester',
                'stadium' => 'Etihad Stadium',
                'founded_year' => 1880,
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/e/eb/Manchester_City_FC_badge.svg',
                'website' => 'https://www.mancity.com',
                'email' => 'info@mancity.com',
                'phone' => '+44 161 444 1894',
                'address' => 'Etihad Stadium, Manchester M11 3FF',
                'fifa_connect_id' => 'MCFC001',
                'league' => 'Premier League',
                'division' => '1',
                'status' => 'active',
                'budget_eur' => 800000000,
                'wage_budget_eur' => 300000000,
                'transfer_budget_eur' => 200000000,
                'reputation' => 96,
                'facilities_level' => 5,
                'youth_development' => 5,
                'scouting_network' => 5,
                'medical_team' => 5,
                'coaching_staff' => 5,
            ]
        ];

        foreach ($premierLeagueClubs as $clubData) {
            $club = Club::create($clubData);

            // Create teams for each club
            $teams = [
                [
                    'name' => 'First Team',
                    'type' => 'first_team',
                    'formation' => '4-3-3',
                    'tactical_style' => 'Possession-based attacking football',
                    'playing_philosophy' => 'High pressing, quick transitions, technical excellence',
                    'coach_name' => 'Erik ten Hag',
                    'assistant_coach' => 'Steve McClaren',
                    'fitness_coach' => 'Richard Hawkins',
                    'goalkeeper_coach' => 'Richard Hartis',
                    'scout' => 'Marcel Bout',
                    'medical_staff' => 'Dr. Gary O\'Driscoll',
                    'status' => 'active',
                    'season' => '2024/25',
                    'competition_level' => 'Premier League',
                    'budget_allocation' => 200000000,
                    'training_facility' => 'Carrington Training Complex',
                    'home_ground' => 'Old Trafford'
                ],
                [
                    'name' => 'Reserve Team',
                    'type' => 'reserve',
                    'formation' => '4-4-2',
                    'tactical_style' => 'Balanced approach with focus on development',
                    'playing_philosophy' => 'Technical development, tactical understanding',
                    'coach_name' => 'Neil Wood',
                    'assistant_coach' => 'Quinton Fortune',
                    'fitness_coach' => 'Tom Hughes',
                    'goalkeeper_coach' => 'Alan Fettis',
                    'scout' => 'Tommy Martin',
                    'medical_staff' => 'Dr. Sarah Lindsay',
                    'status' => 'active',
                    'season' => '2024/25',
                    'competition_level' => 'Premier League 2',
                    'budget_allocation' => 5000000,
                    'training_facility' => 'Carrington Training Complex',
                    'home_ground' => 'Leigh Sports Village'
                ],
                [
                    'name' => 'Youth Academy',
                    'type' => 'youth',
                    'formation' => '4-3-3',
                    'tactical_style' => 'Development-focused with emphasis on skills',
                    'playing_philosophy' => 'Technical excellence, creative play, character development',
                    'coach_name' => 'Travis Binnion',
                    'assistant_coach' => 'Colin Little',
                    'fitness_coach' => 'Mark Dempsey',
                    'goalkeeper_coach' => 'Richard Hartis',
                    'scout' => 'Jim Lawlor',
                    'medical_staff' => 'Dr. Tony Gill',
                    'status' => 'active',
                    'season' => '2024/25',
                    'competition_level' => 'U18 Premier League',
                    'budget_allocation' => 2000000,
                    'training_facility' => 'Carrington Training Complex',
                    'home_ground' => 'Carrington Training Ground'
                ]
            ];

            foreach ($teams as $teamData) {
                $team = $club->teams()->create($teamData);

                // Assign players to teams based on their existing club
                $players = Player::where('club_id', $club->id)->get();
                
                foreach ($players as $index => $player) {
                    $role = $index < 11 ? 'starter' : ($index < 18 ? 'substitute' : 'reserve');
                    $squadNumber = $index < 25 ? $index + 1 : null;
                    
                    TeamPlayer::create([
                        'team_id' => $team->id,
                        'player_id' => $player->id,
                        'role' => $role,
                        'squad_number' => $squadNumber,
                        'joined_date' => now()->subDays(rand(30, 365)),
                        'contract_end_date' => now()->addYears(rand(1, 5)),
                        'position_preference' => $player->position,
                        'status' => 'active'
                    ]);
                }
            }
        }

        // Create competitions
        $competitions = [
            [
                'name' => 'Premier League',
                'short_name' => 'EPL',
                'type' => 'league',
                'country' => 'England',
                'region' => 'Europe',
                'season' => '2024/25',
                'start_date' => '2024-08-10',
                'end_date' => '2025-05-25',
                'registration_deadline' => '2024-07-31',
                'max_teams' => 20,
                'min_teams' => 20,
                'format' => 'round_robin',
                'prize_pool' => 2500000000,
                'entry_fee' => 0,
                'status' => 'active',
                'description' => 'The top tier of English football',
                'rules' => 'Standard Premier League rules apply',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/f/f2/Premier_League_Logo.png',
                'website' => 'https://www.premierleague.com',
                'organizer' => 'Premier League',
                'sponsors' => 'Barclays, EA Sports, Nike',
                'broadcast_partners' => 'Sky Sports, BT Sport, Amazon Prime'
            ],
            [
                'name' => 'FA Cup',
                'short_name' => 'FA Cup',
                'type' => 'cup',
                'country' => 'England',
                'region' => 'Europe',
                'season' => '2024/25',
                'start_date' => '2024-08-15',
                'end_date' => '2025-05-17',
                'registration_deadline' => '2024-07-31',
                'max_teams' => 736,
                'min_teams' => 736,
                'format' => 'knockout',
                'prize_pool' => 20000000,
                'entry_fee' => 0,
                'status' => 'active',
                'description' => 'The oldest football competition in the world',
                'rules' => 'Single elimination knockout tournament',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/f/f2/The_F.A._Cup.svg',
                'website' => 'https://www.thefa.com/competitions/thefacup',
                'organizer' => 'The Football Association',
                'sponsors' => 'Emirates',
                'broadcast_partners' => 'BBC, ITV'
            ],
            [
                'name' => 'UEFA Champions League',
                'short_name' => 'UCL',
                'type' => 'tournament',
                'country' => 'Europe',
                'region' => 'Europe',
                'season' => '2024/25',
                'start_date' => '2024-09-17',
                'end_date' => '2025-06-01',
                'registration_deadline' => '2024-08-31',
                'max_teams' => 32,
                'min_teams' => 32,
                'format' => 'group_stage',
                'prize_pool' => 2000000000,
                'entry_fee' => 0,
                'status' => 'active',
                'description' => 'The most prestigious club competition in European football',
                'rules' => 'Group stage followed by knockout rounds',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/en/b/bf/UEFA_Champions_League_logo_2.svg',
                'website' => 'https://www.uefa.com/uefachampionsleague',
                'organizer' => 'UEFA',
                'sponsors' => 'Gazprom, Mastercard, PlayStation',
                'broadcast_partners' => 'BT Sport, CBS Sports'
            ]
        ];

        foreach ($competitions as $competitionData) {
            Competition::create($competitionData);
        }

        // Create some sample matches
        $clubs = Club::all();
        $competition = Competition::where('name', 'Premier League')->first();

        if ($clubs->count() >= 2 && $competition) {
            for ($i = 0; $i < 10; $i++) {
                $homeClub = $clubs->random();
                $awayClub = $clubs->where('id', '!=', $homeClub->id)->random();
                
                $homeTeam = $homeClub->teams()->where('type', 'first_team')->first();
                $awayTeam = $awayClub->teams()->where('type', 'first_team')->first();

                if ($homeTeam && $awayTeam) {
                    GameMatch::create([
                        'competition_id' => $competition->id,
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'home_club_id' => $homeClub->id,
                        'away_club_id' => $awayClub->id,
                        'match_date' => now()->addDays(rand(1, 30)),
                        'kickoff_time' => now()->addDays(rand(1, 30))->setTime(15, 0),
                        'venue' => 'home',
                        'stadium' => $homeClub->stadium,
                        'capacity' => rand(40000, 75000),
                        'weather_conditions' => 'Clear',
                        'pitch_condition' => 'Excellent',
                        'referee' => 'Michael Oliver',
                        'assistant_referee_1' => 'Stuart Burt',
                        'assistant_referee_2' => 'Simon Bennett',
                        'fourth_official' => 'David Coote',
                        'var_referee' => 'Paul Tierney',
                        'match_status' => 'scheduled',
                        'home_score' => null,
                        'away_score' => null,
                        'home_possession' => null,
                        'away_possession' => null,
                        'home_shots' => null,
                        'away_shots' => null,
                        'home_shots_on_target' => null,
                        'away_shots_on_target' => null,
                        'home_corners' => null,
                        'away_corners' => null,
                        'home_fouls' => null,
                        'away_fouls' => null,
                        'home_offsides' => null,
                        'away_offsides' => null
                    ]);
                }
            }
        }

        $this->command->info('Club management system seeded successfully!');
        $this->command->info('Created ' . count($premierLeagueClubs) . ' clubs with teams and sample matches.');
    }
}
