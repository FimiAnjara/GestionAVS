<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande', function (Blueprint $table) {
            $table->string('id_commande', 50)->primary();
            $table->timestamp('date_');
            $table->integer('etat');
            $table->string('id_utilisateur', 50);
            $table->string('id_client', 50);
            $table->timestamps();
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur');
            $table->foreign('id_client')->references('id_client')->on('Client');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};
