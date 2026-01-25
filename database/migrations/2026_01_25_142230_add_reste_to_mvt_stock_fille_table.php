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
        Schema::table('mvt_stock_fille', function (Blueprint $table) {
            $table->double('reste')->default(0)->after('prix_unitaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mvt_stock_fille', function (Blueprint $table) {
            $table->dropColumn('reste');
        });
    }
};
