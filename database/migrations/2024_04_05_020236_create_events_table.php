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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('start')->precision(0);
            $table->timestamp('end')->precision(0);
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->foreign('assigned_to_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
