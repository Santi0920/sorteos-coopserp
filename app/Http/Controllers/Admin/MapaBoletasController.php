<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Sorteo;

class MapaBoletasController extends Controller
{
    public function index(Sorteo $sorteo)
    {
        $boletas = Boleta::with('asociado')
            ->where('sorteo_id', $sorteo->id)
            ->get()
            ->keyBy('numero_boleta');

        return view('admin.boletas.mapa', compact('sorteo', 'boletas'));
    }
}