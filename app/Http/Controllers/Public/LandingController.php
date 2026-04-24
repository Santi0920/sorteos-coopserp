<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionGeneral;
use App\Models\Premio;
use App\Models\Sorteo;

class LandingController extends Controller
{
    public function index()
    {
        $textoPromocional = ConfiguracionGeneral::where('clave', 'texto_promocional')->value('valor')
            ?? 'Participa en nuestros sorteos y gana premios increíbles.';

        $sorteos = Sorteo::query()
            ->where('estado', 'programado')
            ->orderBy('fecha_sorteo', 'asc')
            ->get();

        $premios = Premio::query()
            ->with('sorteo')
            ->where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderByDesc('id')
            ->get();

        return view('public.landing', compact('textoPromocional', 'sorteos', 'premios'));
    }
}