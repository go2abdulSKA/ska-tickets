<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Main ticket header table for all three ticket types:
     * - Finance Tickets
     * - Delivery Notes
     * - Fuel Sales
     *
     * Contains common fields + type-specific fields
     */
    public function up(): void
    {
        Schema::create('ticket_masters', function (Blueprint $table) {
            $table->id();

            // Ticket identification
            $table->string('prefix'); // "C/A", "FUEL", etc.
            $table->string('ticket_no')->unique(); // "C/A-00001"
            $table->enum('ticket_type', ['finance', 'delivery_note', 'fuel_sale'])->default('finance');
            $table->date('ticket_date');

            // Department and user information
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_name'); // Snapshot at creation time
            $table->string('host_name')->nullable(); // IP address for audit

            // Client or Cost Center (one of these will be filled)
            $table->enum('client_type', ['client', 'cost_center']);
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cost_center_id')->nullable()->constrained()->onDelete('set null');

            // Project details (common to all ticket types)
            $table->string('project_code')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('service_location')->nullable();
            $table->foreignId('service_type_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ref_no')->nullable();

            // Finance-specific fields
            $table->string('payment_terms')->nullable();
            $table->enum('payment_type', ['po', 'cash', 'credit_card', 'na'])->default('na');
            $table->enum('currency', ['usd', 'aed', 'euro', 'others'])->default('usd');

            // Amounts (applicable to Finance and Fuel Sales)
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(5.00);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            // Additional information
            $table->text('remarks')->nullable();

            // Status workflow
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');
            $table->timestamp('posted_date')->nullable();

            // ERP integration fields (for posted tickets)
            $table->string('inv_ref')->nullable(); // Invoice reference in ERP
            $table->date('sage_inv_date')->nullable(); // Invoice date in Sage/ERP

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ticket_no');
            $table->index('ticket_type');
            $table->index('ticket_date');
            $table->index('department_id');
            $table->index('client_id');
            $table->index('cost_center_id');
            $table->index('status');
            $table->index('created_at');

            // Composite index for common queries
            $table->index(['department_id', 'status', 'ticket_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_masters');
    }
};
