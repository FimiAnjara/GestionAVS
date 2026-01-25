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
            $table->string('id_mvt_source')->nullable()->after('id_article');
            $table->foreign('id_mvt_source')->references('id_mvt_stock_fille')->on('mvt_stock_fille')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mvt_stock_fille', function (Blueprint $table) {
            $table->dropForeign(['id_mvt_source']);
            $table->dropColumn('id_mvt_source');
        });
    }
};
