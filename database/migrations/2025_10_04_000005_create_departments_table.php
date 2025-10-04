<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Departments organize the system by business units
     * Each department has its own ticket numbering sequence
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            // Department information
            $table->string('department'); // "Camp & Accommodation"
            $table->string('short_name')->nullable(); // "C&A"
            $table->string('prefix')->unique(); // "C/A" for ticket numbers
            $table->string('form_name')->nullable(); // Custom name for forms/invoices

            // Department logo for invoices
            $table->string('logo_path')->nullable();

            // Additional information
            $table->text('notes')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('is_active');
            $table->index('prefix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
