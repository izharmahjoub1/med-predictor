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
            [
                'name' => 'Kylian Mbappé',
                'first_name' => 'Kylian',
                'last_name' => 'Mbappé',
                'date_of_birth' => '1998-12-20',
                'nationality' => 'France',
                'position' => 'ST',
                'height' => 178,
                'weight' => 73,
                'overall_rating' => 91,
                'potential_rating' => 95,
                'value_eur' => 180000000,
                'wage_eur' => 400000,
            ],
        ];

        foreach ($players as $playerData) {
            Player::firstOrCreate(
                ['name' => $playerData['name']],
                $playerData
            );
        }

        // Créer des dossiers médicaux de test
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
                'blood_pressure_systolic' => 135,
                'blood_pressure_diastolic' => 85,
                'heart_rate' => 58,
                'temperature' => 37.1,
                'weight' => 83,
                'height' => 187,
                'blood_type' => 'O+',
                'allergies' => [],
                'medications' => ['Protéines', 'Créatine'],
                'medical_history' => ['Blessure à la cheville (2020)'],
                'symptoms' => [],
                'diagnosis' => 'Condition physique excellente',
                'treatment_plan' => 'Continuer le programme d\'entraînement personnalisé',
                'record_date' => now()->subDays(3),
                'next_checkup_date' => now()->addMonths(3),
                'status' => 'active',
            ],
            [
                'player_id' => Player::where('name', 'Kylian Mbappé')->first()->id,
                'blood_pressure_systolic' => 118,
                'blood_pressure_diastolic' => 78,
                'heart_rate' => 62,
                'temperature' => 36.9,
                'weight' => 73,
                'height' => 178,
                'blood_type' => 'B+',
                'allergies' => ['Latex'],
                'medications' => ['Vitamine C'],
                'medical_history' => [],
                'symptoms' => ['Douleur légère au mollet'],
                'diagnosis' => 'État de santé très bon, surveillance recommandée pour la douleur au mollet',
                'treatment_plan' => 'Repos relatif, étirements, surveillance de la douleur',
                'record_date' => now()->subDays(1),
                'next_checkup_date' => now()->addWeeks(2),
                'status' => 'active',
            ],
            [
                'player_id' => null, // Patient anonyme
                'blood_pressure_systolic' => 145,
                'blood_pressure_diastolic' => 95,
                'heart_rate' => 85,
                'temperature' => 37.5,
                'weight' => 85,
                'height' => 175,
                'blood_type' => 'A-',
                'allergies' => ['Arachides', 'Sulfamides'],
                'medications' => ['Médicament pour l\'hypertension'],
                'medical_history' => ['Hypertension', 'Diabète de type 2'],
                'symptoms' => ['Maux de tête', 'Fatigue', 'Essoufflement'],
                'diagnosis' => 'Hypertension artérielle non contrôlée',
                'treatment_plan' => 'Ajustement du traitement antihypertenseur, surveillance rapprochée',
                'record_date' => now()->subHours(12),
                'next_checkup_date' => now()->addWeeks(1),
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
    }
}
