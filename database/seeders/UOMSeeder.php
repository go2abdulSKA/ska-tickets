<?php

namespace Database\Seeders;

use App\Models\UOM;
use Illuminate\Database\Seeder;

/**
 * UOMSeeder
 * 
 * Seeds common units of measurement
 */
class UOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uoms = [
            ['code' => 'PCS', 'name' => 'Pieces', 'description' => 'Individual items or units'],
            ['code' => 'KG', 'name' => 'Kilograms', 'description' => 'Weight measurement'],
            ['code' => 'LTR', 'name' => 'Liters', 'description' => 'Liquid volume'],
            ['code' => 'MTR', 'name' => 'Meters', 'description' => 'Length measurement'],
            ['code' => 'HR', 'name' => 'Hours', 'description' => 'Time-based service'],
            ['code' => 'DAY', 'name' => 'Days', 'description' => 'Daily rate'],
            ['code' => 'WEEK', 'name' => 'Weeks', 'description' => 'Weekly rate'],
            ['code' => 'MONTH', 'name' => 'Months', 'description' => 'Monthly rate'],
            ['code' => 'SQM', 'name' => 'Square Meters', 'description' => 'Area measurement'],
            ['code' => 'SET', 'name' => 'Sets', 'description' => 'Complete set of items'],
            ['code' => 'BOX', 'name' => 'Boxes', 'description' => 'Boxed items'],
            ['code' => 'PKT', 'name' => 'Packets', 'description' => 'Packaged items'],
            ['code' => 'TRIP', 'name' => 'Trips', 'description' => 'Transportation trips'],
            ['code' => 'LOT', 'name' => 'Lots', 'description' => 'Batch or lot'],
        ];

        foreach ($uoms as $uom) {
            UOM::create(array_merge($uom, ['is_active' => true]));
        }

        $this->command->info('✓ UOM created successfully! Total: ' . count($uoms));
    }
}
