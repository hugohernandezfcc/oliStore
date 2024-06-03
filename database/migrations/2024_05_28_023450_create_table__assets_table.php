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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();

            $table->unsignedBigInteger('financial_id')->nullable();
            $table->foreign('financial_id')->references('id')->on('financial')->restrictOnDelete();

            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('box')->restrictOnDelete();

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
