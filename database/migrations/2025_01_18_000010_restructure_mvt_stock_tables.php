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
        // Recréer mvt_stock avec la nouvelle structure (parent)
        Schema::dropIfExists('mvt_stock_fille');
        Schema::dropIfExists('mvt_stock');

        Schema::create('mvt_stock', function (Blueprint $table) {
            $table->string('id_mvt_stock', 50)->primary();
            $table->timestamp('date_')->nullable();
            $table->string('id_magasin', 50)->nullable();
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
        });

        // Créer mvt_stock_fille (enfants)
        Schema::create('mvt_stock_fille', function (Blueprint $table) {
            $table->string('id_mvt_stock_fille', 50)->primary();
            $table->string('id_mvt_stock', 50);
            $table->string('id_article', 150)->nullable();
            $table->decimal('entree', 15, 2)->default(0);
            $table->decimal('sortie', 15, 2)->default(0);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->date('date_expiration')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('id_mvt_stock')->references('id_mvt_stock')->on('mvt_stock')->onDelete('cascade');
            $table->foreign('id_article')->references('id_article')->on('article')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mvt_stock_fille');
        Schema::dropIfExists('mvt_stock');
    }
};
