<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->boolean('is_available_today')->default(true)->after('consultation_fee');
            $table->text('unavailable_message')->nullable()->after('is_available_today');
            $table->date('availability_date')->nullable()->after('unavailable_message');
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['is_available_today', 'unavailable_message', 'availability_date']);
        });
    }
};
