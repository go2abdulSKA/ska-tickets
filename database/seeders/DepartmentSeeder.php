<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\TicketNumber;
use Illuminate\Database\Seeder;

/**
 * DepartmentSeeder
 *
 * Seeds sample departments with their ticket number counters
 */
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department' => 'Camp & Accommodation',
                'short_name' => 'C&A',
                'prefix' => 'C/A',
                'form_name' => 'Camp & Accommodation Services',
                'is_active' => true,
            ],
            [
                'department' => 'Fuel Station',
                'short_name' => 'Fuel',
                'prefix' => 'FUEL',
                'form_name' => 'Fuel Sales',
                'is_active' => true,
            ],
            [
                'department' => 'Transportation',
                'short_name' => 'Trans',
                'prefix' => 'TRANS',
                'form_name' => 'Transportation Services',
                'is_active' => true,
            ],
            [
                'department' => 'Catering',
                'short_name' => 'Catering',
                'prefix' => 'CAT',
                'form_name' => 'Catering Services',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $deptData) {
            $department = Department::create($deptData);

            // Create ticket number counter for this department
            TicketNumber::create([
                'department_id' => $department->id,
                'last_used' => 0,
                'last_user' => 'System',
                'is_adding' => false,
            ]);

            $this->command->info("✓ Department created: {$department->department}");
        }

        $this->command->info('✓ All departments created successfully!');
    }
}
