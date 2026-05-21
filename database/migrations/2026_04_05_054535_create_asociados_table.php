<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asociados', function (Blueprint $table) {

            $table->id();

            $table->string('documento', 30)
                ->unique();

            $table->string('nombres');

            $table->string('apellidos')
                ->nullable();

            $table->string('email')
                ->nullable()
                ->index();

            $table->string('telefono', 30)
                ->nullable();

            $table->string('whatsapp', 30)
                ->nullable();

            $table->string('token_consulta', 120)
                ->unique()
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            /*
            NUEVOS CAMPOS
            */

            $table->string('cuenta')
                ->nullable();

            $table->string('agencia')
                ->nullable();

            $table->string('nomina')
                ->nullable();

            $table->timestamps();

            $table->index('activo');

            $table->index('documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asociados');
    }
};