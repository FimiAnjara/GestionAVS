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
        if (Schema::hasTable('mvt_stock')) {
            Schema::table('mvt_stock', function (Blueprint $table) {
                if (!Schema::hasColumn('mvt_stock', 'id_bonCommande')) {
                    $table->string('id_bonCommande')->nullable()->after('id_stock');
                }
                if (!Schema::hasColumn('mvt_stock', 'id_bonReception')) {
                    $table->string('id_bonReception')->nullable()->after('id_bonCommande');
                }
                if (!Schema::hasColumn('mvt_stock', 'date_expiration')) {
                    $table->date('date_expiration')->nullable()->after('id_bonReception');
                }
                if (!Schema::hasColumn('mvt_stock', 'deleted_at')) {
                    $table->softDeletes()->after('updated_at');
                }
            });

            // Ajouter les clés étrangères
            Schema::table('mvt_stock', function (Blueprint $table) {
                try {
                    $table->foreign('id_bonCommande')->references('id_bonCommande')->on('bonCommande')->onDelete('set null');
                } catch (\Exception $e) {
                    // La clé existe déjà
                }

                try {
                    $table->foreign('id_bonReception')->references('id_bonReception')->on('bonReception')->onDelete('set null');
                } catch (\Exception $e) {
                    // La clé existe déjà
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mvt_stock', function (Blueprint $table) {
            try {
                $table->dropForeign(['id_bonCommande']);
            } catch (\Exception $e) {}
            
            try {
                $table->dropForeign(['id_bonReception']);
            } catch (\Exception $e) {}
        });
    }
};
