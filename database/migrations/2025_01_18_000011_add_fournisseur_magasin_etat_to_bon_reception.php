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
            if (!Schema::hasColumn('bonReception', 'id_fournisseur')) {
                $table->string('id_fournisseur')->nullable()->after('id_bonCommande');
                $table->foreign('id_fournisseur')
                    ->references('id_fournisseur')->on('fournisseur')
                    ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('bonReception', 'id_magasin')) {
                $table->string('id_magasin')->nullable()->after('id_fournisseur');
                $table->foreign('id_magasin')
                    ->references('id_magasin')->on('magasin')
                    ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('bonReception', 'etat')) {
                // 1 = créé, 11 = validé, 0 = annulé
                $table->tinyInteger('etat')->default(1)->after('id_magasin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonReception', function (Blueprint $table) {
            if (Schema::hasColumn('bonReception', 'id_fournisseur')) {
                $table->dropForeignKeyIfExists(['id_fournisseur']);
                $table->dropColumn('id_fournisseur');
            }
            
            if (Schema::hasColumn('bonReception', 'id_magasin')) {
                $table->dropForeignKeyIfExists(['id_magasin']);
                $table->dropColumn('id_magasin');
            }
            
            if (Schema::hasColumn('bonReception', 'etat')) {
                $table->dropColumn('etat');
            }
        });
    }
};
