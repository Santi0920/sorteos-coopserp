<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boleta_designs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sorteo_id')->nullable()->constrained()->nullOnDelete();

            $table->string('logo')->nullable();

            $table->string('titulo')->nullable();
            $table->text('subtitulo')->nullable();

            $table->text('descripcion')->nullable();
            $table->text('terminos')->nullable();

            $table->string('url_consulta_ganador')->nullable();

            $table->text('texto_coljuegos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boleta_designs');
    }
};
