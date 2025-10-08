<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * CRITICAL: Maintains sequential ticket numbering per department
     * Uses database locking to prevent duplicate numbers with concurrent users
     *
     * Each department has ONE row tracking its current ticket number
     */
    public function up(): void
    {
        Schema::create('ticket_numbers', function (Blueprint $table) {
            $table->id();

            // Department association (one row per department)
            $table->foreignId('department_id')->unique()->constrained()->onDelete('cascade');

            // Current ticket number (starts at 0, increments to 1, 2, 3...)
            $table->unsignedInteger('last_used')->default(0);

            // Tracking information
            $table->string('last_user')->nullable(); // Who generated the last ticket
            $table->boolean('is_adding')->default(false); // Lock flag (legacy, may not be needed with row locking)

            $table->timestamps();

            // Index for quick lookups
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_numbers');
    }
};

