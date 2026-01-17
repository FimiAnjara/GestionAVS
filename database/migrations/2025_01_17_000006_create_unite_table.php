<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unite', function (Blueprint $table) {
            $table->string('id_unite', 50)->primary();
            $table->string('libelle', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unite');
    }
};
