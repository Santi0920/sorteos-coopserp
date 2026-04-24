<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SorteoSeeder extends Seeder
{
    public function run(): void
    {
        $sorteos = [
            [
                'nombre' => 'Sorteo Junio 2026',
                'fecha_sorteo' => '2026-06-30',
                'loteria' => 'Lotería de Medellín',
                'estado' => 'programado',
                'es_reprogramado' => false,
                'sorteo_padre_id' => null,
                'observaciones' => 'Sorteo principal de junio 2026',
                'lineas' => ['88', '90', '99'],
            ],
            [
                'nombre' => 'Sorteo Agosto 2026',
                'fecha_sorteo' => '2026-08-31',
                'loteria' => 'Lotería de Boyacá',
                'estado' => 'programado',
                'es_reprogramado' => false,
                'sorteo_padre_id' => null,
                'observaciones' => 'Sorteo principal de agosto 2026',
                'lineas' => ['88', '90', '99'],
            ],
            [
                'nombre' => 'Sorteo Noviembre 2026',
                'fecha_sorteo' => '2026-11-30',
                'loteria' => 'Lotería del Valle',
                'estado' => 'programado',
                'es_reprogramado' => true,
                'sorteo_padre_id' => null,
                'observaciones' => 'Sorteo de reprogramación en caso de no ganador',
                'lineas' => ['88', '90', '99'],
            ],
            [
                'nombre' => 'Sorteo Diciembre 2026',
                'fecha_sorteo' => '2026-12-28',
                'loteria' => 'Lotería de Bogotá',
                'estado' => 'programado',
                'es_reprogramado' => true,
                'sorteo_padre_id' => null,
                'observaciones' => 'Última reprogramación en caso de persistir sin ganador',
                'lineas' => ['88', '90', '99'],
            ],
        ];

        foreach ($sorteos as $item) {
            $sorteoId = DB::table('sorteos')->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'fecha_sorteo' => $item['fecha_sorteo'],
                    'loteria' => $item['loteria'],
                    'estado' => $item['estado'],
                    'es_reprogramado' => $item['es_reprogramado'],
                    'sorteo_padre_id' => $item['sorteo_padre_id'],
                    'observaciones' => $item['observaciones'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $sorteo = DB::table('sorteos')->where('nombre', $item['nombre'])->first();

            foreach ($item['lineas'] as $codigoLinea) {
                $linea = DB::table('lineas_credito')->where('codigo', $codigoLinea)->first();

                if ($linea && $sorteo) {
                    DB::table('linea_credito_sorteo')->updateOrInsert(
                        [
                            'linea_credito_id' => $linea->id,
                            'sorteo_id' => $sorteo->id,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}