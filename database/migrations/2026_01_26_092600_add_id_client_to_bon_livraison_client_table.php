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
        Schema::table('bon_livraison_client', function (Blueprint $table) {
            if (!Schema::hasColumn('bon_livraison_client', 'id_client')) {
                $table->string('id_client', 50)->after('description')->nullable();
                $table->foreign('id_client')->references('id_client')->on('Client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_livraison_client', function (Blueprint $table) {
            $table->dropForeign(['id_client']);
            $table->dropColumn('id_client');
        });
    }
};
