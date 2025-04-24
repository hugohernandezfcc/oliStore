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
        Schema::create('orpb2b', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('salesorder_id');
            $table->foreign('salesorder_id')->references('id')->on('salesorder')->restrictOnDelete();
            $table->unsignedBigInteger('productb2b_id');
            $table->foreign('productb2b_id')->references('id')->on('productb2b')->restrictOnDelete();

            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();

            $table->boolean('requested')->default(false);
            $table->boolean('verified')->default(false);
            $table->boolean('loaded')->default(false);
            $table->boolean('deliveried')->default(false);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orpb2b');
    }
};
