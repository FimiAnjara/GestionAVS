<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articleFille', function (Blueprint $table) {
            $table->string('id_articleFille', 50)->primary();
            $table->decimal('prix', 15, 2);
            $table->timestamp('date_');
            $table->decimal('quantite', 15, 2);
            $table->string('id_unite', 50)->nullable();
            $table->string('id_article', 150);
            $table->timestamps();
            $table->foreign('id_unite')->references('id_unite')->on('unite');
            $table->foreign('id_article')->references('id_article')->on('article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articleFille');
    }
};
