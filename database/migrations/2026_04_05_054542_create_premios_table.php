<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premios', function (Blueprint $table) {

            $table->id();

            /*
            ==========================================
            RELACIONES
            ==========================================
            */

            $table->foreignId('sorteo_id')
                ->constrained('sorteos')
                ->cascadeOnDelete();

            $table->foreignId('boleta_ganadora_id')
                ->nullable()
                ->constrained('boletas')
                ->nullOnDelete();

            /*
            ==========================================
            INFORMACIÓN DEL PREMIO
            ==========================================
            */

            $table->string('titulo');

            $table->text('descripcion')
                ->nullable();

            $table->string('imagen')
                ->nullable();

            /*
            ==========================================
            CONTROL / ADMIN
            ==========================================
            */

            $table->integer('orden')->default(1);

            $table->boolean('activo')->default(true);

            $table->timestamps();

            /*
            ==========================================
            ÍNDICES
            ==========================================
            */

            $table->index('activo');
            $table->index('sorteo_id');
            $table->index('boleta_ganadora_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premios');
    }
};