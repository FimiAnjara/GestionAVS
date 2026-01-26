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
        Schema::create('facture_fournisseur_fille', function (Blueprint $table) {
            $table->string('id_facture_fournisseur_fille', 50)->primary();
            $table->decimal('prix_achat', 15, 2)->nullable();
            $table->integer('quantite')->default(0);
            $table->string('id_facture_fournisseur', 50)->nullable();
            $table->string('id_article', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_facture_fournisseur')->references('id_facture_fournisseur')->on('facture_fournisseur')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_fournisseur_fille');
    }
};
