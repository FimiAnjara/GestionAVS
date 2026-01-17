<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfert', function (Blueprint $table) {
            $table->string('id_transfert', 50)->primary();
            $table->integer('etat');
            $table->timestamp('date_');
            $table->string('id_lot', 50);
            $table->string('id_emplacement', 50);
            $table->timestamps();
            $table->foreign('id_lot')->references('id_lot')->on('lot');
            $table->foreign('id_emplacement')->references('id_emplacement')->on('emplacement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfert');
    }
};
