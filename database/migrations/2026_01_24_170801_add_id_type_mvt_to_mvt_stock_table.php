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
        Schema::table('mvt_stock', function (Blueprint $table) {
            $table->string('id_type_mvt')->after('date_')->nullable();
            $table->foreign('id_type_mvt')->references('id_type_mvt')->on('type_mvt_stock')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mvt_stock', function (Blueprint $table) {
            $table->dropForeign(['id_type_mvt']);
            $table->dropColumn('id_type_mvt');
        });
    }
};
