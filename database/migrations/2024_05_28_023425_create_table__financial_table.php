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
        Schema::create('financial', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();

            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->restrictOnDelete();

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial');
    }
};
