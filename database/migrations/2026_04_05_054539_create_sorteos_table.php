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
            $table->string('nombre');
            $table->date('fecha_sorteo');
            $table->string('loteria')->nullable(); // T = lotería a jugar
            $table->enum('estado', ['programado', 'ejecutado', 'cancelado'])->default('programado');
            $table->boolean('es_reprogramado')->default(false);
            $table->foreignId('sorteo_padre_id')->nullable()->constrained('sorteos')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('fecha_sorteo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteos');
    }
};