<?php

namespace App\Services;

use App\Models\Boleta;
use App\Models\Sorteo;
use Illuminate\Support\Facades\DB;

class BoletaGeneratorService
{
    public function generateForSorteo(Sorteo $sorteo)
    {
        return DB::transaction(function () use ($sorteo) {

            // 🔴 VALIDACIÓN: rango
            if ($sorteo->numero_fin <= $sorteo->numero_inicio) {
                return [
                    'success' => false,
                    'message' => 'El rango del sorteo no es válido',
                ];
            }

            // 🔴 ASOCIADOS DEL SORTEO (PIVOTE)
            $asociados = $sorteo->asociados()->get();

            if ($asociados->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay asociados vinculados al sorteo',
                ];
            }

            // 🔴 POOL DE NÚMEROS DISPONIBLES
            $numeros = DB::table('sorteo_numeros')
                ->where('sorteo_id', $sorteo->id)
                ->where('usado', false)
                ->orderBy('id')
                ->get();

            if ($numeros->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay números disponibles en el pool',
                ];
            }

            $totalBoletas = $numeros->count();
            $totalAsociados = $asociados->count();

            // 🔴 CUÁNTAS BOLETAS POR ASOCIADO
            $base = intdiv($totalBoletas, $totalAsociados);
            $resto = $totalBoletas % $totalAsociados;

            $index = 0;
            $creadas = 0;

            foreach ($asociados as $i => $asociado) {

                $cantidad = $base;

                // repartir sobrantes
                if ($resto > 0) {
                    $cantidad++;
                    $resto--;
                }

                for ($j = 0; $j < $cantidad; $j++) {

                    if (!isset($numeros[$index])) {
                        break;
                    }

                    $numero = $numeros[$index];

                    Boleta::create([
                        'sorteo_id' => $sorteo->id,
                        'asociado_id' => $asociado->id,
                        'numero_boleta' => $numero->numero,
                        'monto_base' => 0,
                        'ganadora' => false,
                    ]);

                    DB::table('sorteo_numeros')
                        ->where('id', $numero->id)
                        ->update(['usado' => true]);

                    $index++;
                    $creadas++;
                }
            }

            return [
                'success' => true,
                'message' => "Boletas generadas correctamente ({$creadas})",
                'generated' => $creadas
            ];
        });
    }
}