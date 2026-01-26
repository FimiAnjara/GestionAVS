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
        Schema::create('mvt_stock_fille', function (Blueprint $table) {
            $table->string('id_mvt_stock_fille', 50)->primary();
            $table->decimal('entree', 15, 2)->default(0);
            $table->decimal('sortie', 15, 2)->default(0);
            $table->timestamp('date_')->useCurrent();
            $table->timestamp('date_expiration')->nullable();
            $table->decimal('prix', 15, 2)->nullable();
            $table->string('id_article', 150)->nullable();
            $table->string('id_mvt_stock', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_article')->references('id_article')->on('article')->onDelete('cascade');
            $table->foreign('id_mvt_stock')->references('id_mvt_stock')->on('mvt_stock')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mvt_stock_fille');
    }
};
