<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fuel-specific fields for fuel sale tickets
     * Links to ticket_masters via ticket_id
     */
    public function up(): void
    {
        Schema::create('fuel_sales', function (Blueprint $table) {
            $table->id();

            // Link to main ticket
            $table->foreignId('ticket_id')->unique()->constrained('ticket_masters')->onDelete('cascade');

            // Vehicle information
            $table->string('vehicle_no'); // Plate number
            $table->string('vehicle_type')->nullable(); // Truck, Car, Generator, etc.
            $table->string('driver_name')->nullable();

            // Meter readings
            $table->decimal('meter_reading_before', 15, 2)->nullable();
            $table->decimal('meter_reading_after', 15, 2)->nullable();
            $table->decimal('meter_difference', 15, 2)->nullable(); // Calculated

            // Fuel details
            $table->enum('fuel_type', ['diesel', 'petrol', 'gas', 'other'])->default('diesel');
            $table->decimal('quantity', 15, 3); // Liters/Gallons
            $table->decimal('unit_price', 15, 2); // Price per liter
            $table->decimal('total_amount', 15, 2); // quantity × unit_price

            // Additional tracking
            $table->string('pump_no')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('ticket_id');
            $table->index('vehicle_no');
            $table->index('fuel_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_sales');
    }
};
