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
        Schema::create('court_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // e.g., Civil, Criminal, Constitutional, Family
            $table->string('fir_number')->nullable();
            $table->string('petitioner');
            $table->string('respondent');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['filed', 'under_review', 'hearing_scheduled', 'evidence_submitted', 'judgment_pending', 'closed'])->default('filed');
            
            // Assignees
            $table->foreignId('judge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('lawyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('clerk_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('filed_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->text('judgment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
