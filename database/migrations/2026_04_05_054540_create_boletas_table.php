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

            $table->foreignId('asociado_id')->constrained('asociados')->cascadeOnDelete();
            $table->foreignId('credito_id')->nullable()->constrained('creditos')->nullOnDelete();
            $table->foreignId('sorteo_id')->constrained('sorteos')->cascadeOnDelete();

            $table->string('numero_boleta', 4); // 0000 a 9999
            $table->decimal('monto_base', 15, 2)->default(0); // monto usado para asignación
            $table->integer('bloque_boletas')->default(1); // cuántas boletas generó ese bloque
            $table->boolean('ganadora')->default(false);

            $table->timestamps();

            $table->unique(['sorteo_id', 'numero_boleta']);
            $table->index(['asociado_id', 'sorteo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};