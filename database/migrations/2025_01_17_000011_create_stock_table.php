<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->string('id_stock', 50)->primary();
            $table->string('origine', 50);
            $table->timestamp('date_');
            $table->text('description')->nullable();
            $table->integer('etat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
