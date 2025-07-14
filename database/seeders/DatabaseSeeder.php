<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order of dependencies
        $this->call([
            AssociationSeeder::class,
            ClubSeeder::class,
            PlayerSeeder::class,
            UserSeeder::class,
            HealthRecordSeeder::class,
            PlayerLicenseSeeder::class,
        ]);

        $this->command->info('All data seeded successfully!');
    }
}
