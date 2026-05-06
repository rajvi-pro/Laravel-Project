<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modify appointments table to add 'needs_reschedule' status
        // We'll need to drop and recreate the column since MySQL enum doesn't allow easy modification
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('status')->change();
        });
        
        // Update existing records to ensure they have valid statuses
        DB::table('appointments')
            ->whereNotIn('status', ['scheduled', 'completed', 'cancelled', 'needs_reschedule'])
            ->update(['status' => 'scheduled']);
    }

    public function down()
    {
        // Revert status column back to enum
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled')->change();
        });
    }
};