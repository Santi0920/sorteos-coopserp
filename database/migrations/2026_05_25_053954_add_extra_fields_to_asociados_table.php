<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asociados', function (Blueprint $table) {

            $table->string('coordinador')->nullable();
            $table->string('monto')->nullable();

            // SI quieres separar dependencia de nomina:
            $table->string('dependencia')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('asociados', function (Blueprint $table) {

            $table->dropColumn([
                'coordinador',
                'monto',
                'dependencia'
            ]);

        });
    }
};