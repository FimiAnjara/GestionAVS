<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonLivraisonFille', function (Blueprint $table) {
            $table->string('id_bonLivraisonFille', 50)->primary();
            $table->string('quantite', 50);
            $table->string('id_bonLivraison', 50);
            $table->string('id_article', 150);
            $table->timestamps();
            $table->foreign('id_bonLivraison')->references('id_bonLivraison')->on('bon_livraison');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonLivraisonFille');
    }
};
