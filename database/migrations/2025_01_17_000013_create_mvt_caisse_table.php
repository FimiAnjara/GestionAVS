<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mvt_caisse', function (Blueprint $table) {
            $table->string('id_mvt_caisse', 50)->primary();
            $table->string('origine', 50);
            $table->decimal('debit', 15, 2);
            $table->decimal('credit', 15, 2);
            $table->text('description')->nullable();
            $table->timestamp('date_');
            $table->string('id_caisse', 50);
            $table->timestamps();
            $table->foreign('id_caisse')->references('id_caisse')->on('caisse');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mvt_caisse');
    }
};
