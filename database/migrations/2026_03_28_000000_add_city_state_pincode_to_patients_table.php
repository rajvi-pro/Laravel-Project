<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('patients', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('patients', 'pincode')) {
                $table->string('pincode')->nullable()->after('state');
            }
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'pincode')) {
                $table->dropColumn('pincode');
            }
            if (Schema::hasColumn('patients', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('patients', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};
