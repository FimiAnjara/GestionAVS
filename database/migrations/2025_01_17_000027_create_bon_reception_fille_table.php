<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonReceptionFille', function (Blueprint $table) {
            $table->string('id_bonReceptionFille', 50)->primary();
            $table->decimal('quantite', 15, 2);
            $table->string('id_bonReception', 50);
            $table->string('id_article', 150);
            $table->timestamps();
            $table->foreign('id_bonReception')->references('id_bonReception')->on('bonReception');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonReceptionFille');
    }
};
