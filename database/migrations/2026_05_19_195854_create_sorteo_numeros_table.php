<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteo_numeros', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sorteo_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('numero');

            $table->boolean('usado')->default(false);

            $table->timestamps();

            $table->unique(['sorteo_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteo_numeros');
    }
};