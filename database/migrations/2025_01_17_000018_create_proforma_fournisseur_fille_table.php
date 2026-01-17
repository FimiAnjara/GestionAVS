<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proformaFournisseurFille', function (Blueprint $table) {
            $table->string('id_proformaFornisseurFille', 50)->primary();
            $table->decimal('prix_achat', 15, 2);
            $table->string('id_proformaFournisseur', 50);
            $table->string('id_article', 150);
            $table->timestamps();
            $table->foreign('id_proformaFournisseur')->references('id_proformaFournisseur')->on('proformaFournisseur');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proformaFournisseurFille');
    }
};
