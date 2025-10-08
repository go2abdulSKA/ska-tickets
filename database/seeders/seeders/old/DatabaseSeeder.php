<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Master seeder that runs all other seeders in correct order
 *
 * To run all seeders:
 * php artisan db:seed
 *
 * Or with fresh migration:
 * php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('   SKA Tickets Database Seeder');
        $this->command->info('========================================');
        $this->command->info('');

        // Seed in correct order (respecting foreign key constraints)

        $this->command->info('1. Seeding Roles...');
        $this->call(RoleSeeder::class);

        $this->command->info('');
        $this->command->info('2. Seeding Permissions...');
        $this->call(PermissionSeeder::class);

        $this->command->info('');
        $this->command->info('3. Seeding Departments...');
        $this->call(DepartmentSeeder::class);

        $this->command->info('');
        $this->command->info('4. Seeding Users...');
        $this->call(UserSeeder::class);

        $this->command->info('');
        $this->command->info('5. Seeding Clients...');
        $this->call(ClientSeeder::class);

        $this->command->info('');
        $this->command->info('6. Seeding Cost Centers...');
        $this->call(CostCenterSeeder::class);

        $this->command->info('');
        $this->command->info('7. Seeding Service Types...');
        $this->call(ServiceTypeSeeder::class);

        $this->command->info('');
        $this->command->info('8. Seeding Units of Measurement...');
        $this->call(UOMSeeder::class);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('   Database Seeding Complete!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 3 Roles created');
        $this->command->info('   - 32 Permissions created');
        $this->command->info('   - 5 Departments created');
        $this->command->info('   - 7 Users created');
        $this->command->info('   - 10 Clients created');
        $this->command->info('   - 10 Cost Centers created');
        $this->command->info('   - 20 Service Types created');
        $this->command->info('   - 30 UOMs created');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Super Admin: admin@ska.com / password');
        $this->command->info('   Admin: manager@ska.com / password');
        $this->command->info('   User: user@ska.com / password');
        $this->command->info('');
        $this->command->info('🚀 Ready to start the application!');
        $this->command->info('   Run: php artisan serve');
        $this->command->info('   Visit: http://localhost:8000');
        $this->command->info('');
    }
}
