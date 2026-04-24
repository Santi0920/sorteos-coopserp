<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionGeneralSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'monto_por_boleta'],
            [
                'valor' => '2500000',
                'descripcion' => 'Por cada este monto acumulado se asigna 1 boleta',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'max_lineas_participantes'],
            [
                'valor' => '5',
                'descripcion' => 'Máximo de líneas de crédito participantes',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'longitud_numero_boleta'],
            [
                'valor' => '4',
                'descripcion' => 'Cantidad de dígitos de la boleta',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'rango_boleta_desde'],
            [
                'valor' => '0000',
                'descripcion' => 'Número inicial permitido para boletas',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'rango_boleta_hasta'],
            [
                'valor' => '9999',
                'descripcion' => 'Número final permitido para boletas',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_generales')->updateOrInsert(
            ['clave' => 'texto_promocional'],
            [
                'valor' => 'Participa en nuestros sorteos y gana increíbles premios.',
                'descripcion' => 'Texto principal promocional',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}