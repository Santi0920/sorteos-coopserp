<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditoSeeder extends Seeder
{
    public function run(): void
    {
        $asociado1 = DB::table('asociados')->where('documento', '100000001')->first();
        $asociado2 = DB::table('asociados')->where('documento', '100000002')->first();
        $asociado3 = DB::table('asociados')->where('documento', '100000003')->first();

        $linea88 = DB::table('lineas_credito')->where('codigo', '88')->first();
        $linea90 = DB::table('lineas_credito')->where('codigo', '90')->first();
        $linea99 = DB::table('lineas_credito')->where('codigo', '99')->first();

        $creditos = [
            [
                'asociado_id' => $asociado1?->id,
                'linea_credito_id' => $linea88?->id,
                'numero_credito' => 'CR-2026-0001',
                'monto' => 5000000.00,
                'fecha_desembolso' => '2026-05-15',
                'participa_sorteo' => true,
            ],
            [
                'asociado_id' => $asociado1?->id,
                'linea_credito_id' => $linea90?->id,
                'numero_credito' => 'CR-2026-0002',
                'monto' => 2500000.00,
                'fecha_desembolso' => '2026-05-20',
                'participa_sorteo' => true,
            ],
            [
                'asociado_id' => $asociado2?->id,
                'linea_credito_id' => $linea99?->id,
                'numero_credito' => 'CR-2026-0003',
                'monto' => 7800000.00,
                'fecha_desembolso' => '2026-06-01',
                'participa_sorteo' => true,
            ],
            [
                'asociado_id' => $asociado3?->id,
                'linea_credito_id' => $linea88?->id,
                'numero_credito' => 'CR-2026-0004',
                'monto' => 1200000.00,
                'fecha_desembolso' => '2026-06-10',
                'participa_sorteo' => true,
            ],
        ];

        foreach ($creditos as $credito) {
            if (!$credito['asociado_id'] || !$credito['linea_credito_id']) {
                continue;
            }

            DB::table('creditos')->updateOrInsert(
                ['numero_credito' => $credito['numero_credito']],
                array_merge($credito, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}