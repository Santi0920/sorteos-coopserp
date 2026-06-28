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


            $asociados = $sorteo->asociados()->get();

            if ($asociados->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay participantes vinculados a este sorteo.',
                    'creadas' => 0,
                    'boletasPorAsociado' => collect(),
                ];
            }

            $creadas = 0;
            $sinNumeros = false;

            foreach ($asociados as $asociado) {

                /*
                |--------------------------------------------------------------------------
                | La cantidad de boletas ya NO sale de asociados
                |--------------------------------------------------------------------------
                | Ahora sale de sorteo_asociado.
                */
                $cantidadDeseada = max(
                    1,
                    (int) ($asociado->pivot->boletas_por_persona ?? 1)
                );

                $cantidadActual = Boleta::where('sorteo_id', $sorteo->id)
                    ->where('asociado_id', $asociado->id)
                    ->count();

                $faltantes = $cantidadDeseada - $cantidadActual;

                if ($faltantes <= 0) {
                    continue;
                }

                for ($i = 1; $i <= $faltantes; $i++) {

                    $numero = DB::table('sorteo_numeros')
                        ->where('sorteo_id', $sorteo->id)
                        ->where('usado', false)
                        ->lockForUpdate()
                        ->inRandomOrder()
                        ->first();

                    if (!$numero) {
                        $sinNumeros = true;
                        break 2;
                    }

                    Boleta::create([
                        'sorteo_id' => $sorteo->id,
                        'asociado_id' => $asociado->id,
                        'numero_boleta' => $numero->numero,
                        'monto_base' => 0,
                        'bloque_boletas' => $cantidadDeseada,
                        'ganadora' => false,
                    ]);

                    DB::table('sorteo_numeros')
                        ->where('id', $numero->id)
                        ->update([
                            'usado' => true,
                            'updated_at' => now(),
                        ]);

                    $creadas++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Agrupar boletas generadas por asociado
            |--------------------------------------------------------------------------
            */
            $boletasPorAsociado = Boleta::with(['asociado', 'sorteo'])
                ->where('sorteo_id', $sorteo->id)
                ->orderBy('asociado_id')
                ->orderBy('numero_boleta')
                ->get()
                ->groupBy('asociado_id');


            if ($creadas > 0) {
                $sorteo->update([
                    'boletas_generadas' => true,
                ]);
            }

            if ($sinNumeros) {
                return [
                    'success' => false,
                    'message' => "Se generaron {$creadas} boletas, pero se agotaron los números disponibles.",
                    'creadas' => $creadas,
                    'boletasPorAsociado' => $boletasPorAsociado,
                ];
            }

            return [
                'success' => true,
                'message' => $creadas > 0
                    ? "Boletas generadas correctamente. Nuevas boletas creadas: {$creadas}."
                    : 'No había boletas pendientes por generar.',
                'creadas' => $creadas,
                'boletasPorAsociado' => $boletasPorAsociado,
            ];
        });
    }
}