<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('sorteos', function (Blueprint $table) {

            $table
                ->integer('boletas_por_persona')
                ->default(1)
                ->after('tipo_asignacion');

            $table
                ->boolean('boletas_generadas')
                ->default(false);

            $table->dropColumn([
                'monto_por_boleta'
            ]);

        });
    }

    public function down()
    {
        Schema::table('sorteos', function (Blueprint $table) {

            $table->dropColumn([
                'boletas_por_persona',
                'boletas_generadas'
            ]);

            $table
                ->decimal(
                    'monto_por_boleta',
                    15,
                    2
                )
                ->nullable();

        });
    }

};