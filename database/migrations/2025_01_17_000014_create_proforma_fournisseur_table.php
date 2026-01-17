<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proformaFournisseur', function (Blueprint $table) {
            $table->string('id_proformaFournisseur', 50)->primary();
            $table->timestamp('date_');
            $table->integer('etat');
            $table->string('id_fournisseur', 50);
            $table->timestamps();
            $table->foreign('id_fournisseur')->references('id_fournisseur')->on('fournisseur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proformaFournisseur');
    }
};
