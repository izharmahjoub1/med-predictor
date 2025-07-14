<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Club;
use App\Models\Association;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create system admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@medpredictor.com',
            'password' => Hash::make('password123'),
            'role' => 'system_admin',
            'entity_type' => null,
            'entity_id' => null,
            'fifa_connect_id' => 'FIFA_SYS_ADMIN_001',
            'permissions' => [
                'player_registration_access',
                'competition_management_access',
                'healthcare_access',
                'system_administration'
            ],
            'status' => 'active',
            'login_count' => 0,
            'profile_picture_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face',
            'profile_picture_alt' => 'System Administrator Profile'
        ]);

        // Create club users (if clubs exist)
        $club = Club::first();
        if ($club) {
            // Club Administrator
            User::create([
                'name' => 'Club Administrator',
                'email' => 'club.admin@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'club_admin',
                'entity_type' => 'club',
                'entity_id' => $club->id,
                'fifa_connect_id' => 'FIFA_CLUB_ADMIN_001',
                'permissions' => [
                    'player_registration_access',
                    'competition_management_access',
                    'healthcare_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Club Administrator Profile'
            ]);

            // Club Manager
            User::create([
                'name' => 'Club Manager',
                'email' => 'club.manager@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'club_manager',
                'entity_type' => 'club',
                'entity_id' => $club->id,
                'fifa_connect_id' => 'FIFA_CLUB_MGR_001',
                'permissions' => [
                    'player_registration_access',
                    'competition_management_access',
                    'healthcare_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Club Manager Profile'
            ]);

            // Club Medical Staff
            User::create([
                'name' => 'Club Medical Staff',
                'email' => 'club.medical@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'club_medical',
                'entity_type' => 'club',
                'entity_id' => $club->id,
                'fifa_connect_id' => 'FIFA_CLUB_MED_001',
                'permissions' => [
                    'healthcare_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Club Medical Staff Profile'
            ]);
        }

        // Create association users (if associations exist)
        $association = Association::first();
        if ($association) {
            // Association Administrator
            User::create([
                'name' => 'Association Administrator',
                'email' => 'association.admin@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'association_admin',
                'entity_type' => 'association',
                'entity_id' => $association->id,
                'fifa_connect_id' => 'FIFA_ASSOC_ADMIN_001',
                'permissions' => [
                    'player_registration_access',
                    'competition_management_access',
                    'healthcare_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Association Administrator Profile'
            ]);

            // Association Registrar
            User::create([
                'name' => 'Association Registrar',
                'email' => 'association.registrar@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'association_registrar',
                'entity_type' => 'association',
                'entity_id' => $association->id,
                'fifa_connect_id' => 'FIFA_ASSOC_REG_001',
                'permissions' => [
                    'player_registration_access',
                    'competition_management_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Association Registrar Profile'
            ]);

            // Association Medical Director
            User::create([
                'name' => 'Association Medical Director',
                'email' => 'association.medical@medpredictor.com',
                'password' => Hash::make('password123'),
                'role' => 'association_medical',
                'entity_type' => 'association',
                'entity_id' => $association->id,
                'fifa_connect_id' => 'FIFA_ASSOC_MED_001',
                'permissions' => [
                    'healthcare_access'
                ],
                'status' => 'active',
                'login_count' => 0,
                'profile_picture_url' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=150&h=150&fit=crop&crop=face',
                'profile_picture_alt' => 'Association Medical Director Profile'
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Available login credentials:');
        $this->command->info('1. System Admin: admin@medpredictor.com / password123');
        $this->command->info('2. Club Administrator: club.admin@medpredictor.com / password123');
        $this->command->info('3. Club Manager: club.manager@medpredictor.com / password123');
        $this->command->info('4. Club Medical Staff: club.medical@medpredictor.com / password123');
        $this->command->info('5. Association Administrator: association.admin@medpredictor.com / password123');
        $this->command->info('6. Association Registrar: association.registrar@medpredictor.com / password123');
        $this->command->info('7. Association Medical Director: association.medical@medpredictor.com / password123');
    }
}
