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
        Schema::table('stores', function (Blueprint $table) {
            $table->text('no_servicio_cfe')->nullable();
            $table->text('no_servicio_agua')->nullable();
            $table->text('no_servicio_internet')->nullable();
            $table->date('fecha_pago_cfe')->nullable();
            $table->date('fecha_pago_agua')->nullable();
            $table->date('fecha_pago_internet')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();
        });
        Schema::create('line_any_item', function (Blueprint $table) {
            $table->text('type')->nullable();
            $table->text('origin')->nullable();
            $table->text('target')->nullable();
            $table->text('origin_id')->nullable();
            $table->text('target_id')->nullable();
            $table->text('description')->nullable();
            
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers');

            $table->unsignedBigInteger('worker_id')->nullable();
            $table->foreign('worker_id')->references('id')->on('users');

            $table->unsignedBigInteger('sales_id')->nullable();
            $table->foreign('sales_id')->references('id')->on('sales');

            $table->unsignedBigInteger('box_id')->nullable();
            $table->foreign('box_id')->references('id')->on('box');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('orders_id')->nullable();
            $table->foreign('orders_id')->references('id')->on('purchase_orders');
        });
    }

    /**
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::dropIfExists('database/migrations');
    }
};
