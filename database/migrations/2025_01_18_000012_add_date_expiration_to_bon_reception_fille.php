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
        Schema::table('bonReceptionFille', function (Blueprint $table) {
            if (!Schema::hasColumn('bonReceptionFille', 'date_expiration')) {
                $table->date('date_expiration')->nullable()->after('quantite');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonReceptionFille', function (Blueprint $table) {
            if (Schema::hasColumn('bonReceptionFille', 'date_expiration')) {
                $table->dropColumn('date_expiration');
            }
        });
    }
};
