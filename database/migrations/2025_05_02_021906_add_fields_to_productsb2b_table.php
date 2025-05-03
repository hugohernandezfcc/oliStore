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
        Schema::table('productb2b', function (Blueprint $table) {
            $table->boolean('promo')->default(false);
            $table->boolean('bulkSale')->default(false);
            $table->boolean('drinks')->default(false);
            $table->boolean('snacks')->default(false);
            $table->boolean('groceries')->default(false);
            $table->boolean('cleaning')->default(false);
            $table->boolean('underFox')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productb2b', function (Blueprint $table) {
            //
        });
    }
};
