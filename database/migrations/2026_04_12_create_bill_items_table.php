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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained('billings')->onDelete('cascade');
            $table->enum('type', ['consultation', 'lab_test', 'prescription', 'extra_charge']);
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total', 12, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index('billing_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
