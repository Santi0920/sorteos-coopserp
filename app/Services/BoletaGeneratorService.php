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

            $asociados = $sorteo->asociados()
                ->whereDoesntHave('boletas', function ($q) use ($sorteo) {
                    $q->where('sorteo_id', $sorteo->id);
                })
                ->get();

            if ($asociados->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay participantes',
                    'boletasPorAsociado' => collect()
                ];
            }

            $creadas = 0;

            foreach ($asociados as $asociado) {

                $cantidadBoletas = max(
                    1,
                    intval($asociado->boletas_por_persona ?? 1)
                );

                for ($i = 1; $i <= $cantidadBoletas; $i++) {

                    $numero = DB::table('sorteo_numeros')
                        ->where('sorteo_id', $sorteo->id)
                        ->where('usado', false)
                        ->inRandomOrder()
                        ->lockForUpdate()
                        ->first();

                    if (!$numero) {
                        break 2;
                    }

                    Boleta::create([
                        'sorteo_id' => $sorteo->id,
                        'asociado_id' => $asociado->id,
                        'numero_boleta' => $numero->numero,
                        'monto_base' => 0,
                        'bloque_boletas' => $cantidadBoletas,
                        'ganadora' => false
                    ]);

                    DB::table('sorteo_numeros')
                        ->where('id', $numero->id)
                        ->update([
                            'usado' => true
                        ]);

                    $creadas++;
                }
            }

            $boletasPorAsociado = Boleta::with(['asociado', 'sorteo'])
                ->where('sorteo_id', $sorteo->id)
                ->get()
                ->groupBy('asociado_id');

            return [
                'success' => true,
                'message' => 'Boletas generadas correctamente',
                'boletasPorAsociado' => $boletasPorAsociado
            ];
        });
    }
}