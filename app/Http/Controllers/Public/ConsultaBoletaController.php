<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ConsultaBoletaController extends Controller
{
    public function showByToken(string $token)
    {
        $asociado = Asociado::with([
            'boletas' => function ($query) {
                $query->with('sorteo')->orderByDesc('id');
            }
        ])->where('token_consulta', $token)
          ->where('activo', true)
          ->firstOrFail();

        return view('public.consulta-boletas', compact('asociado'));
    }

    public function form()
    {
        return view('public.consulta-form');
    }

    public function searchByDocumento(Request $request)
    {
        $validated = $request->validate([
            'documento' => ['required', 'string', 'max:30'],
            'consentimiento' => ['accepted'] // 🔥 OBLIGATORIO
        ]);

        $asociado = Asociado::where('documento', $validated['documento'])
            ->where('activo', true)
            ->first();

        if (!$asociado) {
            return back()->with('error', 'No se encontró un asociado activo con ese documento.');
        }

        // 🔥 REGISTRO LEGAL DE CONSENTIMIENTO (RECOMENDADO)
        if (is_null($asociado->consentimiento_datos_at)) {

            $asociado->update([
                'consentimiento_datos_at' => Carbon::now('America/Bogota')
            ]);
     
        }

        return redirect()->route('consulta.boletas.token', $asociado->token_consulta);
    }
}