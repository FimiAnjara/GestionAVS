<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emplacement', function (Blueprint $table) {
            $table->string('id_emplacement', 50)->primary();
            $table->string('lieux', 250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emplacement');
    }
};
