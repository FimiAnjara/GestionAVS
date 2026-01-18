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
        Schema::table('factureFournisseur', function (Blueprint $table) {
            $table->decimal('montant_total', 15, 2)->default(0)->after('description');
            $table->decimal('montant_paye', 15, 2)->default(0)->after('montant_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factureFournisseur', function (Blueprint $table) {
            $table->dropColumn(['montant_total', 'montant_paye']);
        });
    }
};
