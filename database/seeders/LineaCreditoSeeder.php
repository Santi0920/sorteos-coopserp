<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LineaCreditoSeeder extends Seeder
{
    public function run(): void
    {
        $lineas = [
            [
                'codigo' => '88',
                'nombre' => 'Línea 88',
                'descripcion' => 'Línea inicial participante',
                'participa_sorteo' => true,
                'activo' => true,
            ],
            [
                'codigo' => '90',
                'nombre' => 'Línea 90',
                'descripcion' => 'Línea inicial participante',
                'participa_sorteo' => true,
                'activo' => true,
            ],
            [
                'codigo' => '99',
                'nombre' => 'Línea 99',
                'descripcion' => 'Línea inicial participante',
                'participa_sorteo' => true,
                'activo' => true,
            ],
            [
                'codigo' => '1',
                'nombre' => 'Línea 1',
                'descripcion' => 'Línea configurable alternativa',
                'participa_sorteo' => false,
                'activo' => true,
            ],
            [
                'codigo' => '9',
                'nombre' => 'Línea 9',
                'descripcion' => 'Línea configurable alternativa',
                'participa_sorteo' => false,
                'activo' => true,
            ],
        ];

        foreach ($lineas as $linea) {
            DB::table('lineas_credito')->updateOrInsert(
                ['codigo' => $linea['codigo']],
                array_merge($linea, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}