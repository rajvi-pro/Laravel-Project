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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->string('day_of_week'); // 'monday', 'tuesday', etc. or specific date (YYYY-MM-DD)
            $table->time('start_time'); // e.g., 09:00
            $table->time('end_time'); // e.g., 12:00
            $table->time('break_start')->nullable(); // e.g., 12:00
            $table->time('break_end')->nullable(); // e.g., 14:00
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add index for faster queries
            $table->index(['doctor_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
