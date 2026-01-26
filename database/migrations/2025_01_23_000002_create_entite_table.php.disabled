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
        Schema::create('entite', function (Blueprint $table) {
            $table->string('id_entite', 50)->primary();
            $table->string('nom', 50);
            $table->string('description', 50)->nullable();
            $table->string('logo', 50)->nullable();
            $table->string('code_couleur', 50)->nullable();
            $table->string('id_groupe', 50);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_groupe')
                  ->references('id_groupe')
                  ->on('groupe')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entite');
    }
};
