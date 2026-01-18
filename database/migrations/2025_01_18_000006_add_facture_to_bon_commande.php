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
        Schema::table('bonCommande', function (Blueprint $table) {
            $table->string('id_factureFournisseur')->nullable()->after('id_bonCommande');
            
            $table->foreign('id_factureFournisseur')
                ->references('id_factureFournisseur')
                ->on('factureFournisseur')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonCommande', function (Blueprint $table) {
            $table->dropForeign(['id_factureFournisseur']);
            $table->dropColumn('id_factureFournisseur');
        });
    }
};
