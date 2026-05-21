<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteo_asociado', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sorteo_id')
                ->constrained('sorteos')
                ->onDelete('cascade');

            $table->foreignId('asociado_id')
                ->constrained('asociados')
                ->onDelete('cascade');

            $table->timestamps();

            // Evita duplicados (un asociado no se repite en el mismo sorteo)
            $table->unique(['sorteo_id', 'asociado_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteo_asociado');
    }
};