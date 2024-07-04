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
        Schema::table('products', function (Blueprint $table) {
            $table->string( 'expiry_date_range' )->nullable();
            $table->boolean('is_public'         )->nullable();
            $table->boolean('is_private'        )->nullable();
            $table->string( 'public_description')->nullable();
            $table->string( 'public_name'       )->nullable();
            $table->string( 'image'             )->nullable();

            $table->unsignedBigInteger('published_by_id')->nullable();
            $table->foreign('published_by_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
