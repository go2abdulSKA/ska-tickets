<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 *
 * Seeds sample users with different roles
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Get departments
        $campDept = Department::where('prefix', 'C/A')->first();
        $fuelDept = Department::where('prefix', 'FUEL')->first();

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'full_name' => 'System Super Administrator',
            'email' => 'admin@ska.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Super admin has access to all departments (we'll add them all)
        $allDepartments = Department::all();
        $superAdmin->departments()->attach($allDepartments->pluck('id'));

        $this->command->info('✓ Super Admin created: admin@ska.com / password');

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'full_name' => 'Department Administrator',
            'email' => 'admin.user@ska.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Admin has access to Camp & Fuel departments
        $admin->departments()->attach([$campDept->id, $fuelDept->id]);

        $this->command->info('✓ Admin created: admin.user@ska.com / password');

        // Create Regular User
        $user = User::create([
            'name' => 'John Doe',
            'full_name' => 'John Michael Doe',
            'email' => 'user@ska.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // User has access to Camp department only
        $user->departments()->attach($campDept->id);

        $this->command->info('✓ User created: user@ska.com / password');

        // Create another Regular User
        $user2 = User::create([
            'name' => 'Jane Smith',
            'full_name' => 'Jane Elizabeth Smith',
            'email' => 'jane@ska.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // User has access to Fuel department only
        $user2->departments()->attach($fuelDept->id);

        $this->command->info('✓ User created: jane@ska.com / password');

        $this->command->info("\n" . '=== Login Credentials ===');
        $this->command->info('Super Admin: admin@ska.com / password');
        $this->command->info('Admin: admin.user@ska.com / password');
        $this->command->info('User: user@ska.com / password');
        $this->command->info('User: jane@ska.com / password');
    }
}
