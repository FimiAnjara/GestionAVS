<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proformaFournisseurFille', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)->default(1)->after('prix_achat');
        });
    }

    public function down(): void
    {
        Schema::table('proformaFournisseurFille', function (Blueprint $table) {
            $table->dropColumn('quantite');
        });
    }
};
