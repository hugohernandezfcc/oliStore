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
            $table->boolean('bimbo')->default(false);
            $table->boolean('marinela')->default(false);
            $table->boolean('sabritas')->default(false);
            $table->boolean('barcel')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productb2b', function (Blueprint $table) {
            $table->dropColumn('bimbo');
            $table->dropColumn('marinela');
            $table->dropColumn('sabritas');
            $table->dropColumn('barcel');
        });
    }
};
