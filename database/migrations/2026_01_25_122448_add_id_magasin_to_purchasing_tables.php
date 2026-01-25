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
        Schema::table('proformaFournisseur', function (Blueprint $table) {
            $table->string('id_magasin')->nullable()->after('id_fournisseur');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->nullOnDelete();
        });

        Schema::table('bonCommande', function (Blueprint $table) {
            $table->string('id_magasin')->nullable()->after('id_proformaFournisseur');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->nullOnDelete();
        });

        Schema::table('factureFournisseur', function (Blueprint $table) {
            $table->string('id_magasin')->nullable()->after('id_bonCommande');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proformaFournisseur', function (Blueprint $table) {
            $table->dropForeign(['id_magasin']);
            $table->dropColumn('id_magasin');
        });

        Schema::table('bonCommande', function (Blueprint $table) {
            $table->dropForeign(['id_magasin']);
            $table->dropColumn('id_magasin');
        });

        Schema::table('factureFournisseur', function (Blueprint $table) {
            $table->dropForeign(['id_magasin']);
            $table->dropColumn('id_magasin');
        });
    }
};
