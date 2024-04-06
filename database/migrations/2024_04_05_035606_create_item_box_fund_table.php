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
        Schema::create('item_box_fund', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->nullable();
            $table->double('result', 8, 2)->nullable();
            $table->string('type')->nullable();
            
            $table->string('name')->nullable();


            $table->unsignedBigInteger('event_box_id');
            $table->foreign('event_box_id')->references('id')->on('event_box')->restrictOnDelete();

            $table->unsignedBigInteger('box_fund_id');
            $table->foreign('box_fund_id')->references('id')->on('box_fund')->restrictOnDelete();

            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::table('event_box', function (Blueprint $table) {
            $table->unsignedBigInteger('item_box_fund_id');
            $table->foreign('item_box_fund_id')->references('id')->on('item_box_fund')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_box_fund');
    }
};
