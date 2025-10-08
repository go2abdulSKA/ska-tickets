<?php
// database/seeders/UOMSeeder.php

namespace Database\Seeders;

use App\Models\UOM;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * UOMSeeder
 *
 * Creates sample Units of Measurement
 * Used in ticket line items
 *
 * To run:
 * php artisan db:seed --class=UOMSeeder
 */
class UOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing UOMs
        UOM::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $uoms = [
            // Quantity
            ['code' => 'PCS', 'name' => 'Pieces', 'description' => 'Individual units or items', 'is_active' => true],
            ['code' => 'EA', 'name' => 'Each', 'description' => 'Individual items', 'is_active' => true],
            ['code' => 'SET', 'name' => 'Set', 'description' => 'Group of items sold together', 'is_active' => true],
            ['code' => 'LOT', 'name' => 'Lot', 'description' => 'Batch or collection of items', 'is_active' => true],

            // Weight
            ['code' => 'KG', 'name' => 'Kilogram', 'description' => 'Metric weight unit', 'is_active' => true],
            ['code' => 'LBS', 'name' => 'Pounds', 'description' => 'Imperial weight unit', 'is_active' => true],
            ['code' => 'TON', 'name' => 'Metric Ton', 'description' => '1000 kilograms', 'is_active' => true],
            ['code' => 'G', 'name' => 'Gram', 'description' => 'Small metric weight', 'is_active' => true],

            // Volume
            ['code' => 'LTR', 'name' => 'Liter', 'description' => 'Metric volume unit', 'is_active' => true],
            ['code' => 'GAL', 'name' => 'Gallon', 'description' => 'Imperial volume unit', 'is_active' => true],
            ['code' => 'M3', 'name' => 'Cubic Meter', 'description' => 'Volume measurement', 'is_active' => true],
            ['code' => 'BBL', 'name' => 'Barrel', 'description' => 'Oil barrel (42 gallons)', 'is_active' => true],

            // Length
            ['code' => 'M', 'name' => 'Meter', 'description' => 'Metric length unit', 'is_active' => true],
            ['code' => 'CM', 'name' => 'Centimeter', 'description' => 'Small metric length', 'is_active' => true],
            ['code' => 'FT', 'name' => 'Feet', 'description' => 'Imperial length unit', 'is_active' => true],
            ['code' => 'IN', 'name' => 'Inch', 'description' => 'Small imperial length', 'is_active' => true],
            ['code' => 'KM', 'name' => 'Kilometer', 'description' => '1000 meters', 'is_active' => true],

            // Area
            ['code' => 'M2', 'name' => 'Square Meter', 'description' => 'Area measurement', 'is_active' => true],
            ['code' => 'SQ.FT', 'name' => 'Square Feet', 'description' => 'Imperial area unit', 'is_active' => true],

            // Time
            ['code' => 'HR', 'name' => 'Hour', 'description' => 'Time unit - 60 minutes', 'is_active' => true],
            ['code' => 'DAY', 'name' => 'Day', 'description' => 'Time unit - 24 hours', 'is_active' => true],
            ['code' => 'WK', 'name' => 'Week', 'description' => 'Time unit - 7 days', 'is_active' => true],
            ['code' => 'MO', 'name' => 'Month', 'description' => 'Time unit - 30 days', 'is_active' => true],
            ['code' => 'MIN', 'name' => 'Minute', 'description' => 'Time unit - 60 seconds', 'is_active' => true],

            // Special
            ['code' => 'BOX', 'name' => 'Box', 'description' => 'Packaged container', 'is_active' => true],
            ['code' => 'PKG', 'name' => 'Package', 'description' => 'Wrapped or boxed items', 'is_active' => true],
            ['code' => 'CTN', 'name' => 'Carton', 'description' => 'Large box container', 'is_active' => true],
            ['code' => 'PLT', 'name' => 'Pallet', 'description' => 'Wooden shipping platform', 'is_active' => true],
            ['code' => 'SVC', 'name' => 'Service', 'description' => 'Service provided', 'is_active' => true],
            ['code' => 'JOB', 'name' => 'Job', 'description' => 'Complete job or task', 'is_active' => true],
        ];

        foreach ($uoms as $uomData) {
            UOM::create($uomData);
        }

        $this->command->info('✓ Created ' . count($uoms) . ' units of measurement');
    }
}
