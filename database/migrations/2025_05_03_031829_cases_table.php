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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productb2b_id')->nullable();
            $table->foreign('productb2b_id')->references('id')->on('productb2b')->restrictOnDelete();
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('edited_by_id');
            $table->foreign('edited_by_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('account')->restrictOnDelete();
            $table->unsignedBigInteger('salesorder_id')->nullable();
            $table->foreign('salesorder_id')->references('id')->on('salesorder')->restrictOnDelete();
            $table->unsignedBigInteger('orpb2b_id')->nullable();
            $table->foreign('orpb2b_id')->references('id')->on('orpb2b')->restrictOnDelete();


            $table->string('subject')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->string('type')->default('issue');
            $table->string('resolution')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
