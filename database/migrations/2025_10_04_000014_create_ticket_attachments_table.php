<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * File attachments for tickets (PDFs, images, documents)
     * Stored in: storage/app/attachments/YYYY/MM/
     */
    public function up(): void
    {
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();

            // Parent ticket
            $table->foreignId('ticket_id')->constrained('ticket_masters')->onDelete('cascade');

            // File information
            $table->string('original_name'); // "invoice.pdf"
            $table->string('stored_name'); // "C_A-00001_20240101120000_abc123.pdf"
            $table->string('file_path'); // "attachments/2024/01/C_A-00001_20240101120000_abc123.pdf"
            $table->string('mime_type'); // "application/pdf"
            $table->unsignedBigInteger('file_size'); // Size in bytes
            $table->enum('file_type', ['image', 'document', 'other'])->default('other');

            // Optional description
            $table->text('description')->nullable();

            // Audit fields
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Indexes
            $table->index('ticket_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
