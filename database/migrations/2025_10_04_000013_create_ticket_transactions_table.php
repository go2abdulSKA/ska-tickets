<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Line items for Finance Tickets and Delivery Notes
     * Multiple transactions per ticket
     */
    public function up(): void
    {
        Schema::create('ticket_transactions', function (Blueprint $table) {
            $table->id();

            // Parent ticket
            $table->foreignId('ticket_id')->constrained('ticket_masters')->onDelete('cascade');

            // Line item details
            $table->unsignedInteger('sr_no'); // Serial number within ticket (1, 2, 3...)
            $table->text('description'); // Service/item description
            $table->decimal('qty', 15, 3); // Quantity (supports decimals: 1.5, 2.25)
            $table->foreignId('uom_id')->constrained('uom')->onDelete('restrict');
            $table->decimal('unit_cost', 15, 2); // Price per unit
            $table->decimal('total_cost', 15, 2); // qty × unit_cost

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Indexes
            $table->index('ticket_id');
            $table->index(['ticket_id', 'sr_no']); // Composite for ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_transactions');
    }
};
