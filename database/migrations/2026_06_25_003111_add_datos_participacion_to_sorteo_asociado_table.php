<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorteo_asociado', function (Blueprint $table) {
            $table->unsignedInteger('boletas_por_persona')
                ->default(1)
                ->after('asociado_id');

            $table->string('email')->nullable()->after('boletas_por_persona');
            $table->string('telefono')->nullable()->after('email');
            $table->string('whatsapp')->nullable()->after('telefono');

            $table->string('cuenta')->nullable()->after('whatsapp');
            $table->string('agencia')->nullable()->after('cuenta');
            $table->string('nomina')->nullable()->after('agencia');
            $table->string('coordinador')->nullable()->after('nomina');
            $table->string('dependencia')->nullable()->after('coordinador');
        });
    }

    public function down(): void
    {
        Schema::table('sorteo_asociado', function (Blueprint $table) {
            $table->dropColumn([
                'boletas_por_persona',
                'email',
                'telefono',
                'whatsapp',
                'cuenta',
                'agencia',
                'nomina',
                'coordinador',
                'dependencia',
            ]);
        });
    }
};