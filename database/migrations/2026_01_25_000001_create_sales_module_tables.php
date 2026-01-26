<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Proforma Client
        Schema::create('proforma_client', function (Blueprint $table) {
            $table->string('id_proforma_client', 50)->primary();
            $table->datetime('date_');
            $table->text('description')->nullable();
            $table->string('id_client', 50);
            $table->string('id_magasin', 50)->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_client')->references('id_client')->on('Client');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin');
        });

        Schema::create('proforma_client_fille', function (Blueprint $table) {
            $table->string('id_proforma_client_fille', 70)->primary();
            $table->string('id_proforma_client', 50);
            $table->string('id_article', 50);
            $table->decimal('quantite', 15, 2);
            $table->decimal('prix', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_proforma_client')->references('id_proforma_client')->on('proforma_client')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article');
        });

        // 2. Bon de Commande Client
        Schema::create('bon_commande_client', function (Blueprint $table) {
            $table->string('id_bon_commande_client', 50)->primary();
            $table->datetime('date_');
            $table->text('description')->nullable();
            $table->string('id_client', 50);
            $table->string('id_magasin', 50)->nullable();
            $table->string('id_proforma_client', 50)->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_client')->references('id_client')->on('Client');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin');
            $table->foreign('id_proforma_client')->references('id_proforma_client')->on('proforma_client');
        });

        Schema::create('bon_commande_client_fille', function (Blueprint $table) {
            $table->string('id_bon_commande_client_fille', 70)->primary();
            $table->string('id_bon_commande_client', 50);
            $table->string('id_article', 50);
            $table->decimal('quantite', 15, 2);
            $table->decimal('prix', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_bon_commande_client')->references('id_bon_commande_client')->on('bon_commande_client')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article');
        });

        // 3. Facture Client
        Schema::create('facture_client', function (Blueprint $table) {
            $table->string('id_facture_client', 50)->primary();
            $table->datetime('date_');
            $table->text('description')->nullable();
            $table->string('id_client', 50);
            $table->string('id_bon_commande_client', 50)->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_client')->references('id_client')->on('Client');
            $table->foreign('id_bon_commande_client')->references('id_bon_commande_client')->on('bon_commande_client');
        });

        Schema::create('facture_client_fille', function (Blueprint $table) {
            $table->string('id_facture_client_fille', 70)->primary();
            $table->string('id_facture_client', 50);
            $table->string('id_article', 50);
            $table->decimal('quantite', 15, 2);
            $table->decimal('prix', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_facture_client')->references('id_facture_client')->on('facture_client')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article');
        });

        // 4. Bon de Livraison Client
        Schema::create('bon_livraison_client', function (Blueprint $table) {
            $table->string('id_bon_livraison_client', 50)->primary();
            $table->datetime('date_');
            $table->text('description')->nullable();
            $table->string('id_bon_commande_client', 50);
            $table->string('id_magasin', 50);
            $table->string('id_mvt_stock', 50)->nullable(); // Lien vers le mouvement de sortie
            $table->integer('etat')->default(1); // 1: Créé, 2: Livré (Stock sorti)
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_bon_commande_client')->references('id_bon_commande_client')->on('bon_commande_client');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin');
            $table->foreign('id_mvt_stock')->references('id_mvt_stock')->on('mvt_stock');
        });

        Schema::create('bon_livraison_client_fille', function (Blueprint $table) {
            $table->string('id_bon_livraison_client_fille', 70)->primary();
            $table->string('id_bon_livraison_client', 50);
            $table->string('id_article', 50);
            $table->decimal('quantite', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_bon_livraison_client')->references('id_bon_livraison_client')->on('bon_livraison_client')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bon_livraison_client_fille');
        Schema::dropIfExists('bon_livraison_client');
        Schema::dropIfExists('facture_client_fille');
        Schema::dropIfExists('facture_client');
        Schema::dropIfExists('bon_commande_client_fille');
        Schema::dropIfExists('bon_commande_client');
        Schema::dropIfExists('proforma_client_fille');
        Schema::dropIfExists('proforma_client');
    }
};
