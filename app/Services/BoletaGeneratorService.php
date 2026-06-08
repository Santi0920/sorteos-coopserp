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
                    'message' => 'No hay participantes'
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

            $totalDisponibles = DB::table('sorteo_numeros')
                ->where('sorteo_id', $sorteo->id)
                ->count();

            $totalUsadas = DB::table('sorteo_numeros')
                ->where('sorteo_id', $sorteo->id)
                ->where('usado', true)
                ->count();

            $totalRestantes = DB::table('sorteo_numeros')
                ->where('sorteo_id', $sorteo->id)
                ->where('usado', false)
                ->count();

            return [
                'success' => true,
                'message' =>
                    'Boletas generadas: ' . $creadas .
                    ' | Usadas: ' . $totalUsadas .
                    ' | Restantes: ' . $totalRestantes .
                    ' | Total: ' . $totalDisponibles
            ];
        });
    }
}