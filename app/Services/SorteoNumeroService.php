<?php

namespace App\Services;

use App\Models\Sorteo;
use Illuminate\Support\Facades\DB;

class SorteoNumeroService
{
    public function generarPool(Sorteo $sorteo)
    {
        $inicio = (int) $sorteo->numero_inicio;
        $fin = (int) $sorteo->numero_fin;

        DB::table('sorteo_numeros')->where('sorteo_id', $sorteo->id)->delete();

        $data = [];

        for ($i = $inicio; $i <= $fin; $i++) {

            $data[] = [
                'sorteo_id' => $sorteo->id,
                'numero' => str_pad($i, strlen((string)$fin), '0', STR_PAD_LEFT),
                'usado' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('sorteo_numeros')->insert($data);
    }

    public function obtenerNumero(Sorteo $sorteo)
    {
        return DB::table('sorteo_numeros')
            ->where('sorteo_id', $sorteo->id)
            ->where('usado', false)
            ->inRandomOrder()
            ->first();
    }

    public function marcarUsado($id)
    {
        DB::table('sorteo_numeros')
            ->where('id', $id)
            ->update([
                'usado' => true,
                'updated_at' => now()
            ]);
    }
}