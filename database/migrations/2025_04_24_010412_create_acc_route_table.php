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
        Schema::create('acc_route', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('account')->restrictOnDelete();

            $table->unsignedBigInteger('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('route')->restrictOnDelete();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('order')->nullable();
            $table->string('comments')->nullable();

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
        Schema::dropIfExists('acc_route');
    }
};
