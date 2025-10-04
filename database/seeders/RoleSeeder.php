<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * RoleSeeder
 *
 * Creates the three main user roles:
 * 1. User - Basic user with limited access
 * 2. Admin - Can post/unpost tickets and manage master data
 * 3. Super Admin - Full system access
 *
 * To create this file:
 * php artisan make:seeder RoleSeeder
 *
 * To run:
 * php artisan db:seed --class=RoleSeeder
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing roles
        Role::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create User role
        Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Basic user with access to create and manage their own tickets within assigned departments.',
        ]);

        // Create Admin role
        Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Administrator with ability to post/unpost tickets, manage master data, and access reports.',
        ]);

        // Create Super Admin role
        Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Super Administrator with full system access including user management and system settings.',
        ]);

        $this->command->info('✓ Created 3 roles: User, Admin, Super Admin');
    }
}
