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
        Schema::table('bonReception', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('bonReception', 'id_fournisseur')) {
                $table->string('id_fournisseur', 50)->nullable()->after('id_bonCommande');
                $table->foreign('id_fournisseur')->references('id_fournisseur')->on('fournisseur')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('bonReception', 'id_magasin')) {
                $table->string('id_magasin', 50)->nullable()->after('id_fournisseur');
                $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonReception', function (Blueprint $table) {
            $table->dropForeignKey(['id_fournisseur']);
            $table->dropColumn('id_fournisseur');
            $table->dropForeignKey(['id_magasin']);
            $table->dropColumn('id_magasin');
        });
    }
};
