<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Audit trail for ticket status changes
     * Tracks: Draft → Posted → Cancelled (and Unpost actions)
     */
    public function up(): void
    {
        Schema::create('ticket_status_history', function (Blueprint $table) {
            $table->id();

            // Parent ticket
            $table->foreignId('ticket_id')->constrained('ticket_masters')->onDelete('cascade');

            // Status change
            $table->enum('from_status', ['draft', 'posted', 'cancelled'])->nullable(); // null for creation
            $table->enum('to_status', ['draft', 'posted', 'cancelled']);

            // Change details
            $table->text('notes')->nullable(); // Reason for change
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->string('ip_address')->nullable(); // User's IP
            $table->text('user_agent')->nullable(); // Browser info
            $table->timestamp('changed_at')->useCurrent();

            // Indexes
            $table->index('ticket_id');
            $table->index('changed_by');
            $table->index('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_status_history');
    }
};
