<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boleta_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boleta_id')->constrained('boletas')->cascadeOnDelete();

            $table->enum('canal', ['correo', 'whatsapp']);
            $table->string('destino')->nullable(); // email o número
            $table->enum('estado', ['pendiente', 'enviado', 'fallido'])->default('pendiente');
            $table->timestamp('fecha_envio')->nullable();
            $table->text('respuesta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boleta_envios');
    }
};