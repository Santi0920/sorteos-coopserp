<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoletaDesign;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class BoletaDesignController extends Controller
{
    public function edit($sorteoId)
    {
        $sorteo = Sorteo::with('design')->findOrFail($sorteoId);

        return view('admin.boleta-design.edit', compact('sorteo'));
    }

    public function update(Request $request, $sorteoId)
    {
        $request->validate([
            'titulo' => 'nullable',
            'logo' => 'nullable|image',
        ]);

        $design = BoletaDesign::firstOrNew([
            'sorteo_id' => $sorteoId
        ]);

        if ($request->hasFile('logo')) {
            $design->logo = $request->file('logo')->store('boleta', 'public');
        }

        $design->titulo = $request->titulo;
        $design->subtitulo = $request->subtitulo;
        $design->descripcion = $request->descripcion;
        $design->terminos = $request->terminos;
        $design->url_consulta_ganador = $request->url_consulta_ganador;
        $design->texto_coljuegos = $request->texto_coljuegos;

        $design->save();

        return back()->with('success', 'Diseño actualizado correctamente');
    }
}