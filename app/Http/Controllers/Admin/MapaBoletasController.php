<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use App\Models\Boleta;

class MapaBoletasController extends Controller
{
    public function index(Sorteo $sorteo = null)
    {
        $sorteos = Sorteo::orderBy('fecha_sorteo', 'desc')->get();

        if (!$sorteo) {
            $sorteo = $sorteos->first();
        }

        $boletas = Boleta::with('asociado')
            ->where('sorteo_id', $sorteo->id)
            ->get()
            ->keyBy(function ($b) {
                return (int) $b->numero_boleta; // 🔥 CLAVE NORMALIZADA
            });

        return view('admin.boletas.mapa', compact('boletas', 'sorteos', 'sorteo'));
    }
}