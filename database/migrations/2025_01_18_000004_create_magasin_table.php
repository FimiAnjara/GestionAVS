<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Assurez-vous que l'extension PostGIS est activée sur votre base PostgreSQL :
        // DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
        Schema::create('magasin', function (Blueprint $table) {
            $table->string('id_magasin')->primary();
            $table->string('nom');
            // Utilisation de PostGIS pour les coordonnées géographiques
            $table->geometry('location', '4326')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magasin');
    }
};
