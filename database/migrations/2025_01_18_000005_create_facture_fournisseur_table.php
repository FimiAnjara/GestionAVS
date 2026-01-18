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
        Schema::create('factureFournisseur', function (Blueprint $table) {
            $table->string('id_factureFournisseur')->primary();
            $table->dateTime('date_');
            $table->integer('etat')->default(1); // 1:Créée, 5:Validée par Finance, 11:Validée par DG, 0:Annulée
            $table->text('description')->nullable();
            $table->string('id_bonCommande');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_bonCommande')
                ->references('id_bonCommande')
                ->on('bonCommande')
                ->onDelete('cascade');
        });

        Schema::create('factureFournisseurFille', function (Blueprint $table) {
            $table->string('id_factureFournisseurFille')->primary();
            $table->string('id_factureFournisseur');
            $table->string('id_article');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_achat', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_factureFournisseur')
                ->references('id_factureFournisseur')
                ->on('factureFournisseur')
                ->onDelete('cascade');
            $table->foreign('id_article')
                ->references('id_article')
                ->on('article')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factureFournisseurFille');
        Schema::dropIfExists('factureFournisseur');
    }
};
