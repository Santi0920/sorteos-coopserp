<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Sorteo;

class InformeBoletasController extends Controller
{
    public function index()
    {
        $totalEmitidas = Boleta::count();

        $asignadas = Boleta::whereNotNull('asociado_id')->count();

        $pendientes = Boleta::whereNull('asociado_id')->count();

        // Opcional: por sorteo
        $porSorteo = Sorteo::withCount([
            'boletas as total',
            'boletas as asignadas' => function ($q) {
                $q->whereNotNull('asociado_id');
            },
            'boletas as pendientes' => function ($q) {
                $q->whereNull('asociado_id');
            }
        ])->get();

        return view('public.informe-boletas', compact(
            'totalEmitidas',
            'asignadas',
            'pendientes',
            'porSorteo'
        ));
    }

    public function detalle()
    {
        $boletasAsignadas = \App\Models\Boleta::pluck('numero_boleta')
            ->map(fn($n) => str_pad($n, 4, '0', STR_PAD_LEFT))
            ->toArray();

        return view('public.detalle-boletas', compact('boletasAsignadas'));
    }
}