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
        Schema::create('week_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            
            $table->timestamps();
        });

        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->text('start')->nullable();
            $table->text('end')->nullable();

            $table->unsignedBigInteger('worker_id')->nullable();
            $table->foreign('worker_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('week_days');
    }
};
