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
        Schema::table('article', function (Blueprint $table) {
            if (!Schema::hasColumn('article', 'id_type_evaluation_stock')) {
                $table->string('id_type_evaluation_stock')->nullable()->after('id_entite');
            }
            $table->foreign('id_type_evaluation_stock')
                  ->references('id_type_evaluation_stock')
                  ->on('type_evaluation_stock')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropForeign(['id_type_evaluation_stock']);
            $table->dropColumn('id_type_evaluation_stock');
        });
    }
};
