<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseur', function (Blueprint $table) {
            $table->string('id_fournisseur', 50)->primary();
            $table->string('nom', 250);
            $table->string('lieux', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseur');
    }
};
