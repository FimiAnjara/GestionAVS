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
        if (!Schema::hasTable('bonReception')) {
            Schema::create('bonReception', function (Blueprint $table) {
                $table->string('id_bonReception')->primary();
                $table->date('date_');
                $table->string('id_bonCommande');
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('id_bonCommande')->references('id_bonCommande')->on('bonCommande')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('bonReceptionFille')) {
            Schema::create('bonReceptionFille', function (Blueprint $table) {
                $table->string('id_bonReceptionFille')->primary();
                $table->string('id_bonReception');
                $table->string('id_article');
                $table->decimal('quantite', 10, 2);
                $table->date('date_expiration')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('id_bonReception')->references('id_bonReception')->on('bonReception')->onDelete('cascade');
                $table->foreign('id_article')->references('id_article')->on('article')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('bonReceptionFille');
        Schema::dropIfExists('bonReception');
        Schema::enableForeignKeyConstraints();
    }
};
