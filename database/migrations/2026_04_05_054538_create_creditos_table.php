<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asociado_id')->constrained('asociados')->cascadeOnDelete();
            $table->foreignId('linea_credito_id')->constrained('lineas_credito')->cascadeOnDelete();

            $table->string('numero_credito', 50)->unique();
            $table->decimal('monto', 15, 2);
            $table->date('fecha_desembolso')->nullable();

            // Para saber si este crédito entra o no al cálculo de boletas
            $table->boolean('participa_sorteo')->default(true);

            $table->timestamps();

            $table->index(['asociado_id', 'linea_credito_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};