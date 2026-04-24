<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;

class ResultadoController extends Controller
{
    public function index()
    {
        $sorteos = Sorteo::with([
            'premios' => function ($query) {
                $query->where('activo', true)
                    ->whereNotNull('boleta_ganadora_id')
                    ->with('boletaGanadora.asociado')
                    ->orderBy('orden');
            },
        ])
        ->orderBy('fecha_sorteo', 'desc')
        ->get();

        return view('public.resultados', compact('sorteos'));
    }
}