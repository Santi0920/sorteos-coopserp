<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PremioSeeder extends Seeder
{
    public function run(): void
    {
        $premiosPorSorteo = [
            'Sorteo Junio 2026' => [
                [
                    'titulo' => 'Moto Yamaha NMAX',
                    'descripcion' => 'Premio principal del sorteo de junio',
                    'imagen' => 'premios/yamaha-nmax.jpg',
                    'orden' => 1,
                    'activo' => true,
                ],
            ],
            'Sorteo Agosto 2026' => [
                [
                    'titulo' => 'Moto AKT Dynamic Pro',
                    'descripcion' => 'Premio principal del sorteo de agosto',
                    'imagen' => 'premios/akt-dynamic-pro.jpg',
                    'orden' => 1,
                    'activo' => true,
                ],
            ],
            'Sorteo Noviembre 2026' => [
                [
                    'titulo' => 'Moto Suzuki GN 125',
                    'descripcion' => 'Premio de reprogramación noviembre',
                    'imagen' => 'premios/suzuki-gn125.jpg',
                    'orden' => 1,
                    'activo' => true,
                ],
            ],
            'Sorteo Diciembre 2026' => [
                [
                    'titulo' => 'Moto Hero Hunk 160R',
                    'descripcion' => 'Premio final de diciembre',
                    'imagen' => 'premios/hero-hunk-160r.jpg',
                    'orden' => 1,
                    'activo' => true,
                ],
            ],
        ];

        foreach ($premiosPorSorteo as $nombreSorteo => $premios) {
            $sorteo = DB::table('sorteos')->where('nombre', $nombreSorteo)->first();

            if (!$sorteo) {
                continue;
            }

            foreach ($premios as $premio) {
                DB::table('premios')->updateOrInsert(
                    [
                        'sorteo_id' => $sorteo->id,
                        'titulo' => $premio['titulo'],
                    ],
                    [
                        'descripcion' => $premio['descripcion'],
                        'imagen' => $premio['imagen'],
                        'orden' => $premio['orden'],
                        'activo' => $premio['activo'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}