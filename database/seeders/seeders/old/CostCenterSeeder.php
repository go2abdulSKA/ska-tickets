<?php
// database/seeders/CostCenterSeeder.php

namespace Database\Seeders;

use App\Models\CostCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * CostCenterSeeder
 *
 * Creates sample internal cost centers
 * Cost centers are company-wide (not department-specific)
 *
 * To run:
 * php artisan db:seed --class=CostCenterSeeder
 */
class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing cost centers
        CostCenter::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $costCenters = [
            [
                'code' => 'CC-001',
                'name' => 'CAMP',
                'description' => 'Camp operations and accommodations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-002',
                'name' => 'DFAC',
                'description' => 'Dining Facility and food services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-003',
                'name' => 'CATERING & EVENTS',
                'description' => 'Catering services and special events',
                'is_active' => true,
            ],
            [
                'code' => 'CC-004',
                'name' => 'CONSTRUCTION',
                'description' => 'Construction and infrastructure projects',
                'is_active' => true,
            ],
            [
                'code' => 'CC-005',
                'name' => 'FUEL',
                'description' => 'Fuel supply and distribution services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-006',
                'name' => 'PX',
                'description' => 'Post Exchange retail operations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-007',
                'name' => 'VEES LOUNGE',
                'description' => 'VEES Lounge hospitality and entertainment',
                'is_active' => true,
            ],
            [
                'code' => 'CC-008',
                'name' => 'SLD',
                'description' => 'Supply, Logistics, and Distribution',
                'is_active' => true,
            ],
            [
                'code' => 'CC-009',
                'name' => 'SRM',
                'description' => 'Supplier Relationship Management',
                'is_active' => true,
            ],
            [
                'code' => 'CC-010',
                'name' => 'SKA TOYOTA',
                'description' => 'SKA Toyota dealership and services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-011',
                'name' => 'TOYOTA CITY',
                'description' => 'Toyota City operations and sales',
                'is_active' => true,
            ],
            [
                'code' => 'CC-012',
                'name' => 'WFP',
                'description' => 'World Food Programme support services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-013',
                'name' => 'MEDI-PARK',
                'description' => 'Medical park and healthcare services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-014',
                'name' => 'CAMEL LAND',
                'description' => 'Camel Land facility and operations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-015',
                'name' => 'BAIDOA',
                'description' => 'Baidoa regional operations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-016',
                'name' => 'CMGT',
                'description' => 'Contract Management and Governance Team',
                'is_active' => true,
            ],
            [
                'code' => 'CC-017',
                'name' => 'DUBAI OFFICE',
                'description' => 'Dubai head office operations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-018',
                'name' => 'CONSTELLIS - KISMAYO',
                'description' => 'Constellis support operations in Kismayo',
                'is_active' => true,
            ],
            [
                'code' => 'CC-019',
                'name' => 'CONSTELLIS - BALEDOGLE',
                'description' => 'Constellis support operations in Baledogle',
                'is_active' => true,
            ],
            [
                'code' => 'CC-020',
                'name' => 'EXTERNAL CLIENT',
                'description' => 'External client billing and services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-021',
                'name' => 'KISMAYO',
                'description' => 'Kismayo regional operations',
                'is_active' => true,
            ],
            [
                'code' => 'CC-022',
                'name' => 'BUSINESS DEVELOPMENT',
                'description' => 'Business development and strategy',
                'is_active' => true,
            ],
            [
                'code' => 'CC-023',
                'name' => 'HEAVY WORKSHOP',
                'description' => 'Heavy vehicle maintenance workshop',
                'is_active' => true,
            ],
            [
                'code' => 'CC-024',
                'name' => 'SKA LOGISTICS',
                'description' => 'Logistics and transportation services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-025',
                'name' => 'FLUOR - BALEDOGLE',
                'description' => 'Fluor operations support in Baledogle',
                'is_active' => true,
            ],
            [
                'code' => 'CC-026',
                'name' => 'FLUOR - KISMAYO',
                'description' => 'Fluor operations support in Kismayo',
                'is_active' => true,
            ],
            [
                'code' => 'CC-027',
                'name' => 'TRAINING',
                'description' => 'Employee and client training services',
                'is_active' => true,
            ],
            [
                'code' => 'CC-028',
                'name' => 'SKA PROCUREMENT',
                'description' => 'Procurement and supply chain management',
                'is_active' => true,
            ],
            [
                'code' => 'CC-029',
                'name' => 'FLUOR - MOGADISHU',
                'description' => 'Fluor operations support in Mogadishu',
                'is_active' => true,
            ],
            [
                'code' => 'CC-030',
                'name' => 'JOC',
                'description' => 'Joint Operations Center',
                'is_active' => true,
            ],
        ];

        foreach ($costCenters as $costCenterData) {
            CostCenter::create($costCenterData);
        }

        $this->command->info('✓ Created ' . count($costCenters) . ' cost centers');
    }
}
