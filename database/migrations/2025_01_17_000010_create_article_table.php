<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->string('id_article', 150)->primary();
            $table->string('nom', 250);
            $table->decimal('stock', 15, 2);
            $table->string('id_unite', 50);
            $table->string('id_categorie', 50);
            $table->timestamps();
            $table->foreign('id_unite')->references('id_unite')->on('unite');
            $table->foreign('id_categorie')->references('id_categorie')->on('categorie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article');
    }
};
