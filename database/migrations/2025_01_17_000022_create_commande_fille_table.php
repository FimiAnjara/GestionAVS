<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandeFille', function (Blueprint $table) {
            $table->string('id_commandeFille', 50)->primary();
            $table->integer('quantite');
            $table->string('id_unite', 50);
            $table->string('id_commande', 50);
            $table->string('id_article', 150);
            $table->timestamps();
            $table->foreign('id_unite')->references('id_unite')->on('unite');
            $table->foreign('id_commande')->references('id_commande')->on('commande');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandeFille');
    }
};
