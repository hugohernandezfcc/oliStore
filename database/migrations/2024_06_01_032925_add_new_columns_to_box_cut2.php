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
        Schema::table('box_cut', function (Blueprint $table) {
            $table->double('card_box', 8, 2)->nullable();
            $table->double('card_calculated', 8, 2)->nullable();
            $table->double('card_diff', 8, 2)->nullable();
            $table->double('card_withdrawal', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('box_cut', function (Blueprint $table) {
            //
        });
    }
};
