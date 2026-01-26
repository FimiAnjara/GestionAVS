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
        Schema::table('utilisateur', function (Blueprint $table) {
            $table->foreign('id_entite')->references('id_entite')->on('entite')->onDelete('set null');
            $table->foreign('id_site')->references('id_site')->on('site')->onDelete('set null');
            $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            $table->dropForeign(['id_entite']);
            $table->dropForeign(['id_site']);
            $table->dropForeign(['id_magasin']);
        });
    }
};
