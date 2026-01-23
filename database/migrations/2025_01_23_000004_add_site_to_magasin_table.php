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
        Schema::table('magasin', function (Blueprint $table) {
            $table->string('id_site', 50)->nullable()->after('latitude');
            
            $table->foreign('id_site')
                  ->references('id_site')
                  ->on('site')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magasin', function (Blueprint $table) {
            $table->dropForeign(['id_site']);
            $table->dropColumn('id_site');
        });
    }
};
