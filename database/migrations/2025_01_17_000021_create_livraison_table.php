<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livraison', function (Blueprint $table) {
            $table->string('id_livraison', 50)->primary();
            $table->timestamp('date_');
            $table->integer('etat');
            $table->string('id_commande', 50);
            $table->timestamps();
            $table->foreign('id_commande')->references('id_commande')->on('commande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livraison');
    }
};
