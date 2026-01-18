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
            // Supprimer les colonnes existantes et les relations
            $table->dropForeign(['id_emplacement']);
            $table->dropForeign(['id_bonReception']);
            
            $table->dropColumn(['id_emplacement', 'id_bonReception']);
            
            // Ajouter les nouvelles colonnes
            $table->string('id_magasin')->nullable()->after('date_');
            $table->string('id_mvt_source')->nullable()->after('id_bonCommande');
            
            // Ajouter les nouvelles relations
            $table->foreign('id_magasin')
                ->references('id_magasin')
                ->on('magasin')
                ->onDelete('set null');
            
            $table->foreign('id_mvt_source')
                ->references('id_mvt_stock')
                ->on('mvt_stock')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mvt_stock', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes et relations
            $table->dropForeign(['id_magasin']);
            $table->dropForeign(['id_mvt_source']);
            
            $table->dropColumn(['id_magasin', 'id_mvt_source']);
            
            // Restaurer les anciennes colonnes
            $table->string('id_emplacement')->nullable()->after('date_');
            $table->string('id_bonReception')->nullable()->after('id_bonCommande');
            
            // Restaurer les anciennes relations
            $table->foreign('id_emplacement')
                ->references('id_emplacement')
                ->on('emplacement')
                ->onDelete('set null');
            
            $table->foreign('id_bonReception')
                ->references('id_bonReception')
                ->on('bonReception')
                ->onDelete('set null');
        });
    }
};
