<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Modify doctor_unavailabilities to use date ranges instead of time ranges
        Schema::table('doctor_unavailabilities', function (Blueprint $table) {
            // Add end_date column
            $table->date('end_date')->nullable()->after('unavailable_date');
            
            // Drop start_time and end_time columns
            $table->dropColumn(['start_time', 'end_time']);
        });
    }

    public function down()
    {
        // Revert changes
        Schema::table('doctor_unavailabilities', function (Blueprint $table) {
            $table->dropColumn('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }
};