<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletas', function (Blueprint $table) {

            $table->id();

            /*
            ==========================================
            RELACIONES
            ==========================================
            */

            $table->foreignId('sorteo_id')
                ->constrained('sorteos')
                ->cascadeOnDelete();

            $table->foreignId('asociado_id')
                ->constrained('asociados')
                ->cascadeOnDelete();

            $table->foreignId('credito_id')
                ->nullable()
                ->constrained('creditos')
                ->nullOnDelete();

            /*
            ==========================================
            BOLETA
            ==========================================
            */

            /*
            Compatible con:
            00
            9999
            000000
            etc
            */
            $table->string('numero_boleta', 20);

            /*
            Monto del crédito usado
            para calcular boletas
            */
            $table->decimal('monto_base', 15, 2)
                ->default(0);

            /*
            Cantidad total generada
            para ese crédito/persona
            */
            $table->integer('bloque_boletas')
                ->default(1);

            /*
            Marca si ganó
            */
            $table->boolean('ganadora')
                ->default(false);

            $table->timestamps();

            /*
            ==========================================
            RESTRICCIONES
            ==========================================
            */

            /*
            NO repetir número
            dentro del mismo sorteo
            */
            $table->unique([
                'sorteo_id',
                'numero_boleta'
            ]);

            /*
            CONSULTAS
            */
            $table->index([
                'asociado_id',
                'sorteo_id'
            ]);

            $table->index([
                'credito_id',
                'sorteo_id'
            ]);
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};