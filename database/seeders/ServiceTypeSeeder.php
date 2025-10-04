<?php
// database/seeders/ServiceTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceType;
use App\Models\Department;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ServiceType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Map department name => array of service types (from your CSV)
        $data = [
            'Camp & Accomodation' => ['Room Overnight', 'Room Day Rest', 'Airport Transfer', 'Soft Skin Vehicle Rental', 'Equipment Rental', 'Others', 'Add Service Type', 'Drinking Water', 'Suite', 'Night'],
            'SKA Risk Management' => ['PSD Services', 'Static Security', 'Training', 'Secured Trans.', 'Others'],
            'Vees Lounge' => ['Bev Items Daily Retail Sales', 'Bev Items Daily Bulk Sales', 'Food Items Daily Retail Sales', 'Others'],
            'Construction' => ['Special Construction Project', 'InHouse Construction Project', 'Routine Maintenance', 'AdHoc Request', 'Constr Equipment Rental', 'Construction Items Sale', 'Preventive Maintenance', 'Others'],
            'Toyota' => ['Vehicle Maintenance', 'Car Washing', 'Spare Parts', 'Generator Maintenance', 'Vehicle Lease', 'Others'],
            'Fuels' => ['Does not Apply for Fuels'],
            'PX' => ['PX Items Daily Retail Sales', 'Others'],
            'DFAC' => ['Inside Camp Catering', 'AdHoc Meals', 'Conference Room Rental', 'Special Events', 'Food Item Sales', 'Bakery Sales', 'Others'],
            'Camp & Accom - Baidoa' => ['Room Overnight', 'Room Day Rest', 'Airport Transfer', 'Soft Skin Vehicle Rental', 'Equipment Rental', 'Others'],
            'DFAC Baidoa' => ['Inside Camp Catering', 'AdHoc Meals', 'Conference Room Rental', 'Special Events', 'Food Item Sales', 'Bakery Sales', 'Others'],
            'Workshop Baidoa' => ['Vehicle Maintenance', 'Car Washing', 'Spare Parts', 'Generator Maintenance', 'Others'],
            'Logistics' => ['Delivery', 'Clearance', 'Others'],
            'Life Support Services' => ['Life Support services', 'Other Services'],
        ];

        $created = 0;
        foreach ($data as $departmentName => $serviceTypes) {
            // Locate department by name OR fallback to prefix if required
            $department = Department::where('department', $departmentName)->first();
            if (!$department) {
                // try by short variant (some DBs may store slightly different strings)
                $department = Department::where('department', $departmentName)->first();
            }

            if (!$department) {
                $this->command->warn("⚠ Department '{$departmentName}' not found - skipping its service types.");
                continue;
            }

            foreach ($serviceTypes as $st) {
                ServiceType::create([
                    'department_id' => $department->id,
                    'service_type' => $st,
                    'description' => $st,
                    'is_active' => true,
                ]);
                $created++;
            }
        }

        $this->command->info("✓ Created {$created} service types");
    }
}
