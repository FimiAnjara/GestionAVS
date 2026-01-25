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
        Schema::create('site', function (Blueprint $table) {
            $table->string('id_site', 50)->primary();
            $table->string('libelle', 150)->nullable();
            $table->string('id_entite', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_entite')->references('id_entite')->on('entite')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site');
    }
};
