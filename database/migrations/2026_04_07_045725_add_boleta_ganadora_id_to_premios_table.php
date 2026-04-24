<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premios', function (Blueprint $table) {
            $table->foreignId('boleta_ganadora_id')
                ->nullable()
                ->after('sorteo_id')
                ->constrained('boletas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('premios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('boleta_ganadora_id');
        });
    }
};