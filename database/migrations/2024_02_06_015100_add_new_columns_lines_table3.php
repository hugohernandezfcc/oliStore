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
        Schema::table('ticket_items', function (Blueprint $table) {
            $table->string('bar_code')->nullable();
            $table->string('product_name')->nullable();
            $table->double('quantity', 8, 2)->nullable();
            $table->double('cost_customer', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_items', function (Blueprint $table) {
            //
        });
    }
};
