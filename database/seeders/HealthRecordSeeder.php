<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthRecord;
use App\Models\User;
use App\Models\Player;

class HealthRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing health records first
        HealthRecord::truncate();
        
        // Créer un utilisateur de test si il n'existe pas
        $user = User::firstOrCreate(
            ['email' => 'test@medpredictor.com'],
            [
                'name' => 'Dr. Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Créer quelques joueurs de test si ils n'existent pas
        $players = [
            [
                'name' => 'Lionel Messi',
                'first_name' => 'Lionel',
                'last_name' => 'Messi',
                'date_of_birth' => '1987-06-24',
                'nationality' => 'Argentina',
                'position' => 'RW',
                'height' => 170,
                'weight' => 72,
                'overall_rating' => 93,
                'potential_rating' => 93,
                'value_eur' => 50000000,
                'wage_eur' => 500000,
            ],
            [
                'name' => 'Cristiano Ronaldo',
                'first_name' => 'Cristiano',
                'last_name' => 'Ronaldo',
                'date_of_birth' => '1985-02-05',
                'nationality' => 'Portugal',
                'position' => 'ST',
                'height' => 187,
                'weight' => 83,
                'overall_rating' => 88,
                'potential_rating' => 88,
                'value_eur' => 15000000,
                'wage_eur' => 200000,
            ],
        ];

        foreach ($players as $playerData) {
            Player::firstOrCreate(
                ['name' => $playerData['name']],
                $playerData
            );
        }

        // Créer exactement 2 dossiers médicaux actifs
        $healthRecords = [
            [
                'player_id' => Player::where('name', 'Lionel Messi')->first()->id,
                'blood_pressure_systolic' => 120,
                'blood_pressure_diastolic' => 80,
                'heart_rate' => 65,
                'temperature' => 36.8,
                'weight' => 72,
                'height' => 170,
                'blood_type' => 'A+',
                'allergies' => ['Pénicilline'],
                'medications' => ['Vitamine D', 'Oméga-3'],
                'medical_history' => ['Blessure au genou droit (2019)'],
                'symptoms' => ['Fatigue légère'],
                'diagnosis' => 'État de santé excellent',
                'treatment_plan' => 'Maintenir l\'activité physique régulière et l\'alimentation équilibrée',
                'record_date' => now()->subDays(5),
                'next_checkup_date' => now()->addMonths(6),
                'status' => 'active',
            ],
            [
                'player_id' => Player::where('name', 'Cristiano Ronaldo')->first()->id,
                'blood_pressure_systolic' => 118,
                'blood_pressure_diastolic' => 78,
                'heart_rate' => 62,
                'temperature' => 36.6,
                'weight' => 83,
                'height' => 187,
                'blood_type' => 'O+',
                'allergies' => [],
                'medications' => ['Vitamine C', 'Protéines'],
                'medical_history' => ['Blessure à la cheville (2020)'],
                'symptoms' => [],
                'diagnosis' => 'État de santé optimal',
                'treatment_plan' => 'Continuer l\'entraînement intensif et la nutrition sportive',
                'record_date' => now()->subDays(3),
                'next_checkup_date' => now()->addMonths(4),
                'status' => 'active',
            ],
        ];

        foreach ($healthRecords as $recordData) {
            // Calculer le BMI
            if (isset($recordData['weight']) && isset($recordData['height'])) {
                $heightInMeters = $recordData['height'] / 100;
                $recordData['bmi'] = round($recordData['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $recordData['user_id'] = $user->id;

            HealthRecord::create($recordData);
        }

        $this->command->info('Health records seeded successfully!');
        $this->command->info('Created 2 active records, 0 pending, 0 archived');
    }
}
