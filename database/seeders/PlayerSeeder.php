<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Club;
use App\Models\Association;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the English Football Association
        $englishFA = Association::where('name', 'The Football Association')->first();
        $associationId = $englishFA ? $englishFA->id : null;

        // Get Manchester United club
        $manUtd = Club::where('name', 'Manchester United')->first();
        $clubId = $manUtd ? $manUtd->id : null;

        // Create players with real photos using reliable image URLs
        $players = [
            [
                'name' => 'Lionel Messi',
                'first_name' => 'Lionel',
                'last_name' => 'Messi',
                'date_of_birth' => '1987-06-24',
                'nationality' => 'Argentina',
                'position' => 'RW',
                'overall_rating' => 93,
                'potential_rating' => 93,
                'age' => 36,
                'height' => 170,
                'weight' => 72,
                'preferred_foot' => 'Left',
                'work_rate' => 'Medium/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=1',
                'fifa_connect_id' => '158023',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Cristiano Ronaldo',
                'first_name' => 'Cristiano',
                'last_name' => 'Ronaldo',
                'date_of_birth' => '1985-02-05',
                'nationality' => 'Portugal',
                'position' => 'ST',
                'overall_rating' => 88,
                'potential_rating' => 88,
                'age' => 38,
                'height' => 187,
                'weight' => 83,
                'preferred_foot' => 'Right',
                'work_rate' => 'High/High',
                'player_face_url' => 'https://picsum.photos/400/400?random=2',
                'fifa_connect_id' => '20801',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Kylian Mbappé',
                'first_name' => 'Kylian',
                'last_name' => 'Mbappé',
                'date_of_birth' => '1998-12-20',
                'nationality' => 'France',
                'position' => 'ST',
                'overall_rating' => 91,
                'potential_rating' => 95,
                'age' => 24,
                'height' => 178,
                'weight' => 73,
                'preferred_foot' => 'Right',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=3',
                'fifa_connect_id' => '231747',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Bukayo Saka',
                'first_name' => 'Bukayo',
                'last_name' => 'Saka',
                'date_of_birth' => '2001-09-05',
                'nationality' => 'England',
                'position' => 'RW',
                'overall_rating' => 86,
                'potential_rating' => 90,
                'age' => 22,
                'height' => 178,
                'weight' => 65,
                'preferred_foot' => 'Left',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=4',
                'fifa_connect_id' => '238794',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Erling Haaland',
                'first_name' => 'Erling',
                'last_name' => 'Haaland',
                'date_of_birth' => '2000-07-21',
                'nationality' => 'Norway',
                'position' => 'ST',
                'overall_rating' => 91,
                'potential_rating' => 94,
                'age' => 23,
                'height' => 195,
                'weight' => 88,
                'preferred_foot' => 'Left',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=5',
                'fifa_connect_id' => '239085',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Kevin De Bruyne',
                'first_name' => 'Kevin',
                'last_name' => 'De Bruyne',
                'date_of_birth' => '1991-06-28',
                'nationality' => 'Belgium',
                'position' => 'CAM',
                'overall_rating' => 91,
                'potential_rating' => 91,
                'age' => 32,
                'height' => 181,
                'weight' => 70,
                'preferred_foot' => 'Right',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=6',
                'fifa_connect_id' => '192985',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Virgil van Dijk',
                'first_name' => 'Virgil',
                'last_name' => 'van Dijk',
                'date_of_birth' => '1991-07-08',
                'nationality' => 'Netherlands',
                'position' => 'CB',
                'overall_rating' => 89,
                'potential_rating' => 89,
                'age' => 32,
                'height' => 193,
                'weight' => 92,
                'preferred_foot' => 'Right',
                'work_rate' => 'Medium/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=7',
                'fifa_connect_id' => '192985',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Jude Bellingham',
                'first_name' => 'Jude',
                'last_name' => 'Bellingham',
                'date_of_birth' => '2003-06-29',
                'nationality' => 'England',
                'position' => 'CM',
                'overall_rating' => 86,
                'potential_rating' => 92,
                'age' => 20,
                'height' => 186,
                'weight' => 75,
                'preferred_foot' => 'Right',
                'work_rate' => 'High/High',
                'player_face_url' => 'https://picsum.photos/400/400?random=8',
                'fifa_connect_id' => '246834',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Vinícius Júnior',
                'first_name' => 'Vinícius',
                'last_name' => 'Júnior',
                'date_of_birth' => '2000-07-12',
                'nationality' => 'Brazil',
                'position' => 'LW',
                'overall_rating' => 89,
                'potential_rating' => 93,
                'age' => 23,
                'height' => 176,
                'weight' => 73,
                'preferred_foot' => 'Right',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=9',
                'fifa_connect_id' => '238794',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ],
            [
                'name' => 'Mohamed Salah',
                'first_name' => 'Mohamed',
                'last_name' => 'Salah',
                'date_of_birth' => '1992-06-15',
                'nationality' => 'Egypt',
                'position' => 'RW',
                'overall_rating' => 89,
                'potential_rating' => 89,
                'age' => 31,
                'height' => 175,
                'weight' => 71,
                'preferred_foot' => 'Left',
                'work_rate' => 'High/Medium',
                'player_face_url' => 'https://picsum.photos/400/400?random=10',
                'fifa_connect_id' => '209331',
                'club_id' => $clubId,
                'association_id' => $associationId,
                'status' => 'active'
            ]
        ];

        foreach ($players as $playerData) {
            Player::updateOrCreate(
                ['fifa_connect_id' => $playerData['fifa_connect_id']],
                $playerData
            );
        }
    }
}
