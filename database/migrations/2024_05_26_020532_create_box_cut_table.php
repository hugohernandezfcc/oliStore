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
        Schema::create('box_cut', function (Blueprint $table) {
            $table->id();

            $table->double('cash_box', 8, 2)->nullable();
            $table->double('cash_calculated', 8, 2)->nullable();
            $table->double('cash_diff', 8, 2)->nullable();
            $table->double('cash_withdrawal', 8, 2)->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->restrictOnDelete();
            $table->unsignedBigInteger('box_id');
            $table->foreign('box_id')->references('id')->on('box')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_cut');
    }
};
