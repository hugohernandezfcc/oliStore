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
        Schema::create('productb2b', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('folio')->nullable();
            $table->string('description')->nullable();
            $table->string('unit_measure')->nullable();
            $table->string('expiration_range')->nullable();
            

            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->boolean('sold_out')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productb2b');
    }
};
