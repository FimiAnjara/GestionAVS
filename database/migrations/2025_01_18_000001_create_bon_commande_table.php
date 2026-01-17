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
        // Si la table n'existe pas, la créer
        if (!Schema::hasTable('bonCommande')) {
            Schema::create('bonCommande', function (Blueprint $table) {
                $table->string('id_bonCommande')->primary();
                $table->date('date_');
                $table->integer('etat')->default(1);
                $table->string('id_utilisateur');
                $table->string('id_proformaFournisseur')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('cascade');
                $table->foreign('id_proformaFournisseur')->references('id_proformaFournisseur')->on('proformaFournisseur')->onDelete('set null');
            });
        }

        // Si la table bonCommandeFille n'existe pas, la créer
        if (!Schema::hasTable('bonCommandeFille')) {
            Schema::create('bonCommandeFille', function (Blueprint $table) {
                $table->string('id_bonCommandeFille')->primary();
                $table->decimal('quantite', 10, 2);
                $table->decimal('prix_achat', 15, 2)->default(0);
                $table->string('id_bonCommande');
                $table->string('id_article');
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('id_bonCommande')->references('id_bonCommande')->on('bonCommande')->onDelete('cascade');
                $table->foreign('id_article')->references('id_article')->on('article')->onDelete('cascade');
            });
        } else {
            // Si la table existe, ajouter la colonne prix_achat si elle n'existe pas
            if (!Schema::hasColumn('bonCommandeFille', 'prix_achat')) {
                Schema::table('bonCommandeFille', function (Blueprint $table) {
                    $table->decimal('prix_achat', 15, 2)->default(0)->after('quantite');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('bonCommandeFille');
        Schema::dropIfExists('bonCommande');
        Schema::enableForeignKeyConstraints();
    }
};
