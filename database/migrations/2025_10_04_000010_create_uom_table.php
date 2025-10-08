<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Units of measurement for ticket line items
     * Company-wide (not department-specific)
     */
    public function up(): void
    {
        Schema::create('uom', function (Blueprint $table) {
            $table->id();

            // UOM information
            $table->string('code')->unique(); // "PCS", "KG", "LTR"
            $table->string('name'); // "Pieces", "Kilograms", "Liters"
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uom');
    }
};
