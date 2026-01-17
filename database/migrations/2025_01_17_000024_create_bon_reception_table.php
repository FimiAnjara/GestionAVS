<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonReception', function (Blueprint $table) {
            $table->string('id_bonReception', 50)->primary();
            $table->timestamp('date_');
            $table->string('id_bonCommande', 50);
            $table->timestamps();
            $table->foreign('id_bonCommande')->references('id_bonCommande')->on('bonCommande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonReception');
    }
};
