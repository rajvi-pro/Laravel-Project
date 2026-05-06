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
        Schema::table('billings', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('amount');
            $table->decimal('cgst', 10, 2)->default(0)->after('subtotal');
            $table->decimal('sgst', 10, 2)->default(0)->after('cgst');
            $table->decimal('total_tax', 10, 2)->default(0)->after('sgst');
            $table->text('billing_items')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'cgst', 'sgst', 'total_tax', 'billing_items']);
        });
    }
};
