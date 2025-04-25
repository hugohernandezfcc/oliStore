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
        Schema::table('productb2b', function (Blueprint $table) {
            $table->boolean('is_public'         )->nullable();
            $table->boolean('is_private'        )->nullable();
            $table->string( 'public_description')->nullable();
            $table->string( 'public_name'       )->nullable();
            $table->string( 'image'             )->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productb2b', function (Blueprint $table) {
            //
        });
    }
};
