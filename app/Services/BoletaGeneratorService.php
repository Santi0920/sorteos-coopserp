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

            if ($sorteo->boletas_generadas) {
                return [
                    'success' => false,
                    'message' => 'Ya fueron generadas'
                ];
            }

            $asociados = $sorteo->asociados()->get();

            if ($asociados->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No hay participantes'
                ];
            }

            $porPersona = $sorteo->boletas_por_persona;

            $creadas = 0;

            foreach ($asociados as $asociado) {

                for ($i = 1; $i <= $porPersona; $i++) {

                    /**
                     * 🔐 BLOQUEO SEGURO: evita duplicados en concurrencia
                     */
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
                        'bloque_boletas' => $porPersona,
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

            $sorteo->update([
                'boletas_generadas' => true
            ]);

            $totalDisponibles = DB::table('sorteo_numeros')
                ->where('sorteo_id', $sorteo->id)
                ->count();

            return [
                'success' => true,
                'message' =>
                    'Boletas generadas: ' . $creadas .
                    ' | Disponibles totales: ' . $totalDisponibles
            ];
        });
    }
}