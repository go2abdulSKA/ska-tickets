<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Comprehensive audit trail for all user actions
     * Tracks: Create, Update, Delete, View, Login, etc.
     *
     * Alternative: Can use Spatie Activity Log package
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name'); // Snapshot at time of action

            // What was affected
            $table->string('log_name')->nullable(); // "tickets", "users", "auth", etc.
            $table->text('description'); // "Created ticket C/A-00001"
            $table->string('subject_type')->nullable(); // Model class: "App\Models\TicketMaster"
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID

            // Action type
            $table->string('event')->nullable(); // "created", "updated", "deleted", "viewed", "posted"

            // Change tracking (before/after values)
            $table->json('properties')->nullable(); // Stores old and new values

            // Request information
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE

            $table->timestamps();

            // Indexes for fast querying
            $table->index('user_id');
            $table->index('subject_type');
            $table->index('subject_id');
            $table->index(['subject_type', 'subject_id']);
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
