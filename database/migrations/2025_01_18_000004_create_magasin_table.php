<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Assurez-vous que l'extension PostGIS est activée sur votre base PostgreSQL :
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
        
        Schema::create('magasin', function (Blueprint $table) {
            $table->string('id_magasin')->primary();
            $table->string('nom');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ajouter la colonne geometry séparément
        DB::statement('ALTER TABLE magasin ADD COLUMN location geometry(Point, 4326) null');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magasin');
    }
};

