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
        Schema::table('box', function (Blueprint $table) {
            $table->double('open_amount', 8, 2)->nullable();
            $table->double('open_foundbox', 8, 2)->nullable();
            $table->double('close_amount', 8, 2)->nullable();
            $table->double('close_foundbox', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('box', function (Blueprint $table) {
            $table->dropColumn('open_amount');
            $table->dropColumn('open_foundbox');
            $table->dropColumn('close_amount');
            $table->dropColumn('close_foundbox');
        });
    }
};
