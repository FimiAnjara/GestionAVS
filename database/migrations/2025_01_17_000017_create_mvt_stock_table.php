<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mvt_stock', function (Blueprint $table) {
            $table->string('id_mvt_stock', 50)->primary();
            $table->decimal('entree', 15, 2);
            $table->decimal('sortie', 15, 2);
            $table->timestamp('date_');
            $table->string('id_emplacement', 50);
            $table->string('id_article', 150);
            $table->string('id_stock', 50);
            $table->timestamps();
            $table->foreign('id_emplacement')->references('id_emplacement')->on('emplacement');
            $table->foreign('id_article')->references('id_article')->on('article');
            $table->foreign('id_stock')->references('id_stock')->on('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mvt_stock');
    }
};
