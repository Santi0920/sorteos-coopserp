<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linea_credito_sorteo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linea_credito_id')->constrained('lineas_credito')->cascadeOnDelete();
            $table->foreignId('sorteo_id')->constrained('sorteos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['linea_credito_id', 'sorteo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linea_credito_sorteo');
    }
};