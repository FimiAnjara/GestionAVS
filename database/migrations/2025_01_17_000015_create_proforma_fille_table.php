<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proformaFille', function (Blueprint $table) {
            $table->string('id_proformaFille', 50)->primary();
            $table->integer('quantite');
            $table->string('id_unite', 50);
            $table->string('id_article', 150);
            $table->string('id_proforma', 50);
            $table->timestamps();
            $table->foreign('id_unite')->references('id_unite')->on('unite');
            $table->foreign('id_article')->references('id_article')->on('article');
            $table->foreign('id_proforma')->references('id_proforma')->on('proforma');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proformaFille');
    }
};
