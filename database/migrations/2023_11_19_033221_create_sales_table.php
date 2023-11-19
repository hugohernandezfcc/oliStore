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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_method', ['cash', 'card', 'delivery cash', 'delivery card', 'other'])->nullable();
            $table->enum('delivery_method', ['delivery', 'on-site', 'remote'])->nullable();
            $table->enum('status', ['open', 'in-progress', 'closed'])->nullable();
            $table->string('no_products')->nullable();
            $table->string('note')->nullable();
            $table->string('store')->nullable();
            $table->double('inbound_amount', 8, 2)->nullable();
            $table->double('outbound_amount', 8, 2)->nullable();
            $table->double('subtotal', 8, 2)->nullable();
            $table->double('total', 8, 2)->nullable();
            $table->boolean('closed')->default(0);
            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('edited_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
