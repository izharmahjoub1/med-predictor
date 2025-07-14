<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerLicense;
use App\Models\Player;
use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PlayerLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing licenses
        DB::table('player_licenses')->truncate();

        // Get clubs and players
        $clubs = Club::all();
        $players = Player::all();
        $approver = User::where('role', 'club_admin')->first();

        if ($players->isEmpty()) {
            $this->command->warn('No players found. Please run PlayerSeeder first.');
            return;
        }

        if ($clubs->isEmpty()) {
            $this->command->warn('No clubs found. Please run ClubSeeder first.');
            return;
        }

        $licenseTypes = ['professional', 'amateur', 'youth', 'international'];
        $statuses = ['active', 'pending', 'suspended'];
        $licenseCategories = ['A', 'B', 'C', 'D', 'E'];
        $contractTypes = ['permanent', 'loan', 'free_agent'];
        $transferStatuses = ['registered', 'pending_transfer', 'transferred'];

        $licenses = [];

        foreach ($players as $player) {
            // Assign player to a random club if not already assigned
            $club = $player->club_id ? Club::find($player->club_id) : $clubs->random();
            
            $licenseType = $licenseTypes[array_rand($licenseTypes)];
            $status = $statuses[array_rand($statuses)];
            $licenseCategory = $licenseCategories[array_rand($licenseCategories)];
            $contractType = $contractTypes[array_rand($contractTypes)];
            $transferStatus = $transferStatuses[array_rand($transferStatuses)];

            // Generate realistic dates
            $issueDate = now()->subMonths(rand(1, 24));
            $expiryDate = $issueDate->copy()->addYears(rand(1, 5));
            $contractStartDate = $issueDate->copy()->subMonths(rand(0, 6));
            $contractEndDate = $contractStartDate->copy()->addYears(rand(1, 4));

            // Generate realistic wages based on player rating
            $baseWage = $player->overall_rating * 1000; // Base wage per week
            $wageAgreement = $baseWage * 52; // Annual wage

            $licenseData = [
                'player_id' => $player->id,
                'club_id' => $club->id,
                'fifa_connect_id' => 'FIFA_LICENSE_' . str_pad($player->id, 6, '0', STR_PAD_LEFT),
                'license_number' => 'LIC-' . strtoupper(substr($player->nationality, 0, 3)) . '-' . str_pad($player->id, 6, '0', STR_PAD_LEFT),
                'license_type' => $licenseType,
                'status' => $status,
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'renewal_date' => $expiryDate->copy()->subMonths(3),
                'issuing_authority' => 'The Football Association',
                'license_category' => $licenseCategory,
                'registration_number' => 'REG-' . str_pad($player->id, 8, '0', STR_PAD_LEFT),
                'transfer_status' => $transferStatus,
                'contract_type' => $contractType,
                'contract_start_date' => $contractStartDate,
                'contract_end_date' => $contractEndDate,
                'wage_agreement' => $wageAgreement,
                'bonus_structure' => json_encode([
                    'appearance_bonus' => $baseWage * 0.1,
                    'goal_bonus' => $baseWage * 0.5,
                    'assist_bonus' => $baseWage * 0.3,
                    'clean_sheet_bonus' => $baseWage * 0.2,
                    'win_bonus' => $baseWage * 0.15
                ]),
                'release_clause' => $player->value_eur * 1.5,
                'medical_clearance' => rand(0, 1),
                'fitness_certificate' => rand(0, 1),
                'disciplinary_record' => rand(0, 1) ? 'Clean record' : 'Minor infractions',
                'international_clearance' => $licenseType === 'international' ? 1 : rand(0, 1),
                'work_permit' => $player->nationality !== 'England' ? 1 : 0,
                'visa_status' => $player->nationality !== 'England' ? 'Valid' : null,
                'documentation_status' => 'complete',
                'approval_status' => 'approved',
                'approved_by' => $approver ? $approver->id : null,
                'approved_at' => $issueDate,
                'notes' => 'License issued for ' . $licenseType . ' player. Category ' . $licenseCategory . '.',
                'created_at' => $issueDate,
                'updated_at' => now(),
            ];

            $licenses[] = $licenseData;
        }

        // Create licenses in batches
        foreach (array_chunk($licenses, 100) as $batch) {
            PlayerLicense::insert($batch);
        }

        $this->command->info('Player licenses seeded successfully!');
        $this->command->info('Created ' . count($licenses) . ' player licenses.');
        
        // Display sample licenses
        $this->command->info('');
        $this->command->info('=== Sample Licenses Created ===');
        $sampleLicenses = PlayerLicense::with(['player', 'club'])->take(5)->get();
        
        foreach ($sampleLicenses as $license) {
            $this->command->info("- {$license->player->name} ({$license->club->name}) - {$license->license_type} - {$license->status}");
        }
    }
} 