<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proforma', function (Blueprint $table) {
            $table->string('id_proforma', 50)->primary();
            $table->date('date_');
            $table->integer('validite');
            $table->string('id_client', 50);
            $table->timestamps();
            $table->foreign('id_client')->references('id_client')->on('Client');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma');
    }
};
