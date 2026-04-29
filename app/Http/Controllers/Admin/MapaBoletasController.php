<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;

class MapaBoletasController extends Controller
{
    public function index()
    {
        $boletas = Boleta::with('asociado')
            ->get()
            ->keyBy('numero_boleta');

        return view('admin.boletas.mapa', compact('boletas'));
    }
}