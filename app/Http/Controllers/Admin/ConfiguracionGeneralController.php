<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionGeneral;
use App\Models\LineaCredito;
use App\Models\Premio;
use Illuminate\Http\Request;

class ConfiguracionGeneralController extends Controller
{
    public function edit()
    {
        $config = ConfiguracionGeneral::pluck('valor', 'clave')->toArray();

        // NUEVO: métricas para módulos
        $lineas = LineaCredito::count();
        $lineasActivas = LineaCredito::where('activo', true)->count();
        $lineasParticipando = LineaCredito::where('participa_sorteo', true)->count();

        $premios = Premio::count();
        $premiosActivos = Premio::where('activo', true)->count();

        return view('admin.configuracion.edit', compact(
            'config',
            'lineas',
            'lineasActivas',
            'lineasParticipando',
            'premios',
            'premiosActivos'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'monto_por_boleta' => ['required', 'numeric', 'min:1'],
            'texto_promocional' => ['nullable', 'string'],
        ]);

        $descripciones = [
            'monto_por_boleta' => 'Monto necesario para asignar 1 boleta',
            'texto_promocional' => 'Texto principal promocional del sistema',
        ];

        foreach ($validated as $clave => $valor) {
            ConfiguracionGeneral::updateOrCreate(
                ['clave' => $clave],
                [
                    'valor' => (string) $valor,
                    'descripcion' => $descripciones[$clave] ?? null,
                ]
            );
        }

        return redirect()
            ->route('admin.configuracion.edit')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}