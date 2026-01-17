<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departement', function (Blueprint $table) {
            $table->string('id_departement', 50)->primary();
            $table->string('libelle', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departement');
    }
};
