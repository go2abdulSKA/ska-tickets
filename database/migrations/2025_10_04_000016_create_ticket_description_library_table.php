<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Smart library of previously used descriptions
     * Powers the searchable dropdown with auto-complete
     * Tracks usage frequency and average costs
     */
    public function up(): void
    {
        Schema::create('ticket_description_library', function (Blueprint $table) {
            $table->id();

            // Department association
            $table->foreignId('department_id')->constrained()->onDelete('cascade');

            // Description text (use VARCHAR instead of TEXT for unique constraint)
            // MySQL has a limit on index key length, so we'll use VARCHAR(500)
            $table->string('description', 500);

            // Usage tracking
            $table->unsignedInteger('usage_count')->default(1); // How many times used
            $table->decimal('avg_unit_cost', 15, 2)->nullable(); // Average price
            $table->foreignId('last_uom_id')->nullable()->constrained('uom')->onDelete('set null'); // Most recent UOM
            $table->timestamp('last_used_at')->useCurrent();

            // Optional: Categorization
            $table->string('category')->nullable(); // "Accommodation", "Transportation", etc.
            $table->boolean('is_template')->default(false); // Mark as reusable template

            $table->timestamps();

            // Indexes for fast searching
            $table->index('department_id');
            $table->index('usage_count');
            $table->index('last_used_at');

            // Ensure unique description per department
            // NOTE: Using composite unique on department_id + description
            // Description length limited to 500 chars for index compatibility
            $table->unique(['department_id', 'description'], 'dept_desc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_description_library');
    }
};
