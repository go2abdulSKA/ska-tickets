<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extend Jetstream's users table with role and additional fields
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role assignment
            $table->foreignId('role_id')->nullable()->after('email')->constrained()->onDelete('set null');

            // Additional user information
            $table->string('full_name')->nullable()->after('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();

            // Profile photo (if not using Jetstream's profile_photo_path)
            $table->string('profile_photo')->nullable();

            // Account status
            $table->boolean('is_active')->default(true);

            // 2FA preference (optional - Jetstream has its own 2FA)
            $table->boolean('two_factor_enabled')->default(false);

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            // Soft deletes for user deactivation
            $table->softDeletes();

            // Indexes for performance
            $table->index('role_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn([
                'role_id',
                'full_name',
                'phone',
                'address',
                'country',
                'profile_photo',
                'is_active',
                'two_factor_enabled',
                'created_by',
                'updated_by',
                'deleted_at',
            ]);
        });
    }
};
