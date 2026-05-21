<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteos', function (Blueprint $table) {

            $table->id();

            /*
            ==========================================
            INFORMACIÓN GENERAL
            ==========================================
            */

            $table->string('nombre');

            $table->date('fecha_sorteo');

            $table->string('loteria')
                ->nullable();

            $table->enum('estado', [
                'programado',
                'ejecutado',
                'cancelado'
            ])->default('programado');

            /*
            ==========================================
            CONFIGURACIÓN DEL SORTEO
            ==========================================
            */

            /*
            Ej:
            00 -> 99
            0000 -> 9999
            */
            $table->string('numero_inicio')
                ->default('0000');

            $table->string('numero_fin')
                ->default('9999');

            /*
            monto
            equitativo
            manual
            */
            $table->enum('tipo_asignacion', [
                'monto',
                'equitativo',
                'manual'
            ])->default('monto');

            /*
            SOLO aplica si:
            tipo_asignacion = monto
            */
            $table->decimal('monto_por_boleta', 15, 2)
                ->nullable();

            /*
            Texto promocional del sorteo
            */
            $table->text('texto_promocional')
                ->nullable();

            /*
            Permite desactivar el sorteo
            */
            $table->boolean('activo')
                ->default(true);

            /*
            ==========================================
            REPROGRAMACIONES
            ==========================================
            */

            $table->boolean('es_reprogramado')
                ->default(false);

            $table->foreignId('sorteo_padre_id')
                ->nullable()
                ->constrained('sorteos')
                ->nullOnDelete();

            $table->text('observaciones')
                ->nullable();

            $table->timestamps();

            /*
            ==========================================
            ÍNDICES
            ==========================================
            */

            $table->index('fecha_sorteo');

            $table->index('estado');

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteos');
    }
};