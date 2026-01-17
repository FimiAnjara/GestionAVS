<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Client', function (Blueprint $table) {
            $table->string('id_client', 50)->primary();
            $table->string('nom', 250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Client');
    }
};
