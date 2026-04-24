<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionGeneral;
use Illuminate\Http\Request;

class ConfiguracionGeneralController extends Controller
{
    public function edit()
    {
        $config = ConfiguracionGeneral::pluck('valor', 'clave')->toArray();

        return view('admin.configuracion.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'monto_por_boleta' => ['required', 'numeric', 'min:1'],

            'texto_promocional' => ['nullable', 'string'],
        ]);

        $descripciones = [
            'monto_por_boleta' => 'Por cada este monto acumulado se asigna 1 boleta',
            'max_lineas_participantes' => 'Máximo de líneas de crédito participantes',
            'longitud_numero_boleta' => 'Cantidad de dígitos de la boleta',
            'rango_boleta_desde' => 'Número inicial permitido para boletas',
            'rango_boleta_hasta' => 'Número final permitido para boletas',
            'texto_promocional' => 'Texto principal promocional',
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