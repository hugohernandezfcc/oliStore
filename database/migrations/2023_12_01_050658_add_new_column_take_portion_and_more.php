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
        Schema::table('product_line_items', function (Blueprint $table) {
            $table->boolean('take_portion')->default(0);
            $table->string('unit_measure')->nullable();
            $table->double('quantity', 8, 2)->nullable();
            $table->double('final_price', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_line_items', function (Blueprint $table) {
            //
        });
    }
};
