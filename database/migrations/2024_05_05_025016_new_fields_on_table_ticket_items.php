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
        Schema::table('ticket_items', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks')->restrictOnDelete();
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('last_ticket_item_applied_id')->nullable();
            $table->foreign('last_ticket_item_applied_id')->references('id')->on('tickets')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_items', function (Blueprint $table) {
            //
        });
    }
};
