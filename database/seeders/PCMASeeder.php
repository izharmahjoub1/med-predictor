<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PCMA;
use App\Models\Player;
use App\Models\User;
use App\Models\Athlete;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PCMASeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all players
        $players = Player::all();
        
        // Create assessor user
        $assessor = User::firstOrCreate(
            ['email' => 'assessor@medpredictor.com'],
            [
                'name' => 'Dr. Assessor',
                'password' => bcrypt('password'),
            ]
        );

        // Remove old PCMAs
        PCMA::truncate();

        // PCMA types for variety (using only the ones from migration)
        $pcmaTypes = ['bpma', 'cardio', 'dental', 'neurological', 'orthopedic'];
        $statuses = ['pending', 'completed', 'failed', 'cleared', 'not_cleared'];

        // Create 5 PCMAs for each player
        foreach ($players as $player) {
            // Create or get athlete record for this player
            $athlete = Athlete::firstOrCreate(
                ['name' => $player->name],
                [
                    'name' => $player->name,
                    'fifa_id' => strtoupper(substr($player->name, 0, 3)) . '_' . $player->id,
                    'dob' => $player->date_of_birth ?? '1990-01-01',
                    'nationality' => $player->nationality ?? 'Unknown',
                    'position' => $player->position ?? 'Unknown',
                    'jersey_number' => $player->id,
                    'gender' => 'male',
                    'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                    'team_id' => $player->club_id ?? 1,
                    'active' => 1,
                ]
            );

            // Create 5 PCMAs for this player
            for ($i = 1; $i <= 5; $i++) {
                $type = $faker->randomElement($pcmaTypes);
                $status = $faker->randomElement($statuses);
                
                // Generate result JSON based on type
                $resultJson = $this->generateResultJson($type, $faker);
                
                // Generate FIFA compliance data
                $approvedAt = $faker->optional(0.7)->dateTimeBetween('-1 year', 'now');
                $approvedBy = $faker->optional(0.7)->name();
                
                $fifaComplianceData = [
                    'fifa_id' => 'FIFA-' . $player->id . '-' . $i,
                    'competition_name' => $faker->randomElement(['Championnat National 2024', 'Coupe de France', 'Ligue 1', 'Champions League', 'Europa League']),
                    'competition_date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                    'team_name' => $player->club->name ?? 'Équipe nationale',
                    'position' => $player->position ?? $faker->randomElement(['goalkeeper', 'defender', 'midfielder', 'forward']),
                    'fifa_compliant' => $faker->boolean(80), // 80% chance of being compliant
                    'approved_at' => $approvedAt ? $approvedAt->format('Y-m-d H:i:s') : null,
                    'approved_by' => $approvedBy,
                ];

                PCMA::create([
                    'athlete_id' => $athlete->id,
                    'type' => $type,
                    'result_json' => $resultJson,
                    'status' => $status,
                    'completed_at' => $status === 'completed' ? $faker->dateTimeBetween('-1 year', 'now') : null,
                    'assessor_id' => $assessor->id,
                    'notes' => $faker->paragraph(),
                    'fifa_compliance_data' => $fifaComplianceData,
                ]);
            }
        }

        $this->command->info('Created 5 PCMA records for each of the ' . $players->count() . ' players.');
    }

    private function generateResultJson($type, $faker)
    {
        $baseResult = [
            'assessment_type' => $type,
            'overall_health' => $faker->randomElement(['excellent', 'good', 'fair', 'poor']),
            'recommendations' => $faker->sentences(2),
            'created_at' => now()->toISOString(),
            'assessment_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];

        switch ($type) {
            case 'cardio':
                return array_merge($baseResult, [
                    'cardiac_rhythm' => $faker->randomElement(['normal', 'irregular', 'arrhythmia']),
                    'blood_pressure' => $faker->numberBetween(110, 140) . '/' . $faker->numberBetween(60, 90),
                    'heart_rate' => $faker->numberBetween(55, 85),
                    'cardiac_notes' => $faker->optional(0.7)->sentence(),
                    'ecg_interpretation' => $faker->randomElement(['normal', 'sinus_bradycardia', 'sinus_tachycardia', 'atrial_fibrillation', 'ventricular_tachycardia', 'st_elevation', 'st_depression', 'qt_prolongation', 'abnormal']),
                ]);
            
            case 'neurological':
                return array_merge($baseResult, [
                    'consciousness' => $faker->randomElement(['alert', 'confused', 'drowsy']),
                    'cranial_nerves' => $faker->randomElement(['normal', 'abnormal']),
                    'motor_function' => $faker->randomElement(['normal', 'weakness', 'paralysis']),
                    'neurological_notes' => $faker->optional(0.7)->sentence(),
                    'cognitive_assessment' => $faker->randomElement(['normal', 'mild_impairment', 'moderate_impairment', 'severe_impairment']),
                ]);
            
            case 'orthopedic':
                return array_merge($baseResult, [
                    'joint_mobility' => $faker->randomElement(['normal', 'limited', 'restricted']),
                    'muscle_strength' => $faker->randomElement(['normal', 'reduced', 'weak']),
                    'pain_assessment' => $faker->randomElement(['none', 'mild', 'moderate', 'severe']),
                    'orthopedic_notes' => $faker->optional(0.7)->sentence(),
                    'range_of_motion' => $faker->randomElement(['full', 'limited', 'restricted']),
                ]);
            
            case 'dental':
                return array_merge($baseResult, [
                    'dental_health' => $faker->randomElement(['excellent', 'good', 'fair', 'poor']),
                    'cavities' => $faker->numberBetween(0, 5),
                    'gum_health' => $faker->randomElement(['healthy', 'gingivitis', 'periodontitis']),
                    'dental_notes' => $faker->optional(0.7)->sentence(),
                    'last_dental_visit' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                ]);
            
            case 'bpma': // Basic Physical Medical Assessment
            default:
                return array_merge($baseResult, [
                    'general_condition' => $faker->randomElement(['excellent', 'good', 'fair', 'poor']),
                    'fitness_level' => $faker->randomElement(['high', 'medium', 'low']),
                    'general_notes' => $faker->optional(0.7)->sentence(),
                    'vital_signs' => [
                        'blood_pressure' => $faker->numberBetween(110, 140) . '/' . $faker->numberBetween(60, 90),
                        'heart_rate' => $faker->numberBetween(55, 85),
                        'temperature' => $faker->randomFloat(1, 36.0, 37.5),
                        'respiratory_rate' => $faker->numberBetween(12, 20),
                        'oxygen_saturation' => $faker->numberBetween(95, 100),
                        'weight' => $faker->numberBetween(65, 85),
                    ],
                ]);
        }
    }
} 