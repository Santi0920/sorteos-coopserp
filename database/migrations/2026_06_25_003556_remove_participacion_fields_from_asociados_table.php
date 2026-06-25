<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asociados', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'telefono',
                'whatsapp',
                'cuenta',
                'boletas_por_persona',
                'agencia',
                'nomina',
                'coordinador',
                'dependencia',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('asociados', function (Blueprint $table) {
            $table->string('email')->nullable()->after('apellidos');
            $table->string('telefono')->nullable()->after('email');
            $table->string('whatsapp')->nullable()->after('telefono');

            $table->string('cuenta')->nullable()->after('activo');
            $table->unsignedInteger('boletas_por_persona')->default(1)->after('cuenta');

            $table->string('agencia')->nullable()->after('boletas_por_persona');
            $table->string('nomina')->nullable()->after('agencia');
            $table->string('coordinador')->nullable()->after('nomina');
            $table->string('dependencia')->nullable()->after('coordinador');
        });
    }
};