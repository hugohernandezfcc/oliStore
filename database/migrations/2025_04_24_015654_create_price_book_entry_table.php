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
        Schema::create('pricebookentry', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('productb2b_id');
            $table->foreign('productb2b_id')->references('id')->on('productb2b')->restrictOnDelete();
            $table->unsignedBigInteger('pricebook_id');
            $table->foreign('pricebook_id')->references('id')->on('pricebook')->restrictOnDelete();


            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->double('price', 8,2)->nullable();
            $table->double('cost', 8,2)->nullable();
            $table->double('profit', 8,2)->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('description')->nullable();
            $table->string('name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricebookentry');
    }
};
