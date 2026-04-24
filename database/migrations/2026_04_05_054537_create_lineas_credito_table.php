<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lineas_credito', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique(); // Ej: 88, 90, 99, 1, 9
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('participa_sorteo')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_credito');
    }
};