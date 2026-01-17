<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->string('id_utilisateur', 50)->primary();
            $table->string('email', 50)->unique();
            $table->string('mdp', 250);
            $table->string('id_departement', 50);
            $table->string('id_role', 50);
            $table->timestamps();
            $table->foreign('id_departement')->references('id_departement')->on('departement');
            $table->foreign('id_role')->references('id_role')->on('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
