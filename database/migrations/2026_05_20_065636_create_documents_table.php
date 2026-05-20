<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained('court_cases')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Uploader
            $table->string('name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('document_type'); // e.g., petition, evidence, motion, judgment, fir
            $table->integer('version')->default(1);
            $table->foreignId('parent_id')->nullable()->constrained('documents')->onDelete('cascade'); // For version history
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
