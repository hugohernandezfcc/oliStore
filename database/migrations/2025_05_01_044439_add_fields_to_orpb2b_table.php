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
        Schema::table('orpb2b', function (Blueprint $table) {
            
            $table->date('delivery_date')->nullable();
            $table->string('name')->nullable();

            $table->string('quantity')->nullable();
            $table->string('image')->nullable();
            $table->double('unit_price', 8,2)->nullable();
            $table->double('subtotal_price', 8,2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orpb2b', function (Blueprint $table) {
            //
        });
    }
};
