<?php
// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\TicketNumber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DepartmentSeeder
 *
 * Creates departments based on actual SKA business units
 * Initializes ticket numbering for each department
 *
 * To run:
 * php artisan db:seed --class=DepartmentSeeder
 */
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing departments and ticket numbers
        DB::table('ticket_numbers')->truncate();
        DB::table('user_departments')->truncate();
        Department::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $departments = [
            [
                'department' => 'Camp & Accomodation',
                'short_name' => 'Camp & Accom',
                'prefix' => 'C/A',
                'form_name' => 'Camp & Accommodation Finance Ticket',
                'notes' => 'Accommodation services, room rentals, airport transfers, and vehicle rentals',
                'is_active' => true,
            ],
            [
                'department' => 'SKA Risk Management',
                'short_name' => 'Risk Management',
                'prefix' => 'SRM',
                'form_name' => 'Risk Management Finance Ticket',
                'notes' => 'PSD services, static security, training, and secured transportation',
                'is_active' => true,
            ],
            [
                'department' => 'Vees Lounge',
                'short_name' => 'Vees Lounge',
                'prefix' => 'BEV',
                'form_name' => 'Vees Lounge Finance Ticket',
                'notes' => 'Beverage and food retail sales',
                'is_active' => true,
            ],
            [
                'department' => 'Construction',
                'short_name' => 'Construction',
                'prefix' => 'CONST',
                'form_name' => 'Construction Finance Ticket',
                'notes' => 'Construction projects, maintenance, and equipment rental',
                'is_active' => true,
            ],
            [
                'department' => 'Toyota',
                'short_name' => 'Workshop',
                'prefix' => 'WS',
                'form_name' => 'Workshop Finance Ticket',
                'notes' => 'Vehicle maintenance, car washing, spare parts, and generator services',
                'is_active' => true,
            ],
            [
                'department' => 'PX',
                'short_name' => 'PX',
                'prefix' => 'PX',
                'form_name' => 'PX Finance Ticket',
                'notes' => 'Retail sales and exchange services',
                'is_active' => true,
            ],
            [
                'department' => 'DFAC',
                'short_name' => 'DFAC',
                'prefix' => 'DFAC',
                'form_name' => 'DFAC Finance Ticket',
                'notes' => 'Dining facility catering, meals, and food services',
                'is_active' => true,
            ],
            [
                'department' => 'Fuels',
                'short_name' => 'Fuels',
                'prefix' => 'PFS',
                'form_name' => 'Fuels Sales Ticket',
                'notes' => 'Petroleum fuel services and distribution',
                'is_active' => true,
            ],
            [
                'department' => 'Camp & Accom - Baidoa',
                'short_name' => 'Camp Baidoa',
                'prefix' => 'B_C/A',
                'form_name' => 'Baidoa Camp & Accommodation Finance Ticket',
                'notes' => 'Baidoa location accommodation and support services',
                'is_active' => true,
            ],
            [
                'department' => 'DFAC Baidoa',
                'short_name' => 'DFAC Baidoa',
                'prefix' => 'B_DFAC',
                'form_name' => 'DFAC Baidoa Finance Ticket',
                'notes' => 'Baidoa location dining facility services',
                'is_active' => true,
            ],
            [
                'department' => 'Workshop Baidoa',
                'short_name' => 'Workshop Baidoa',
                'prefix' => 'B_WS',
                'form_name' => 'Workshop Baidoa Finance Ticket',
                'notes' => 'Baidoa location vehicle and equipment maintenance',
                'is_active' => true,
            ],
            [
                'department' => 'Logistics',
                'short_name' => 'Logistics',
                'prefix' => 'LOG',
                'form_name' => 'Logistics Finance Ticket',
                'notes' => 'Delivery, clearance, and logistics support services',
                'is_active' => true,
            ],
            [
                'department' => 'Life Support Services',
                'short_name' => 'Life Support',
                'prefix' => 'LSA',
                'form_name' => 'Life Support Services Finance Ticket',
                'notes' => 'Life support and essential services',
                'is_active' => true,
            ],
            [
                'department' => 'WFP',
                'short_name' => 'WFP',
                'prefix' => 'WFP',
                'form_name' => 'WFP Finance Ticket',
                'notes' => 'World Food Programme support services',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $deptData) {
            // Create department
            $department = Department::create($deptData);

            // Initialize ticket numbering directly
            TicketNumber::create([
                'department_id' => $department->id,
                'last_used' => 0,
                'last_user' => 'System',
                'is_adding' => false,
            ]);

            $this->command->info("✓ Created department: {$department->department} ({$department->prefix})");
        }

        $this->command->info('✓ Created ' . count($departments) . ' departments');
        $this->command->info('✓ Initialized ticket numbering for all departments');
    }
}
