<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('file_disk')->default('private');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('file_size');
            $table->char('sha256_hash', 64)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('uploaded_at')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['tracked_item_id', 'version_number']);
            $table->index(['tracked_item_id', 'uploaded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
