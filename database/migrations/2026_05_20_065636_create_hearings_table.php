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
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained('court_cases')->onDelete('cascade');
            $table->dateTime('hearing_date');
            $table->string('courtroom');
            $table->foreignId('judge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['scheduled', 'held', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
