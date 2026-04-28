<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;

class AsociadoController extends Controller
{
    public function index()
    {
        $asociados = Asociado::withCount('creditos')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.asociados.index', compact('asociados'));
    }

    public function creditos($id)
    {
        try {

            $asociado = Asociado::with([
                'creditos' => function ($q) {
                    $q->with('lineaCredito')
                      ->orderByDesc('id');
                }
            ])->findOrFail($id);

            $creditos = $asociado->creditos->map(function ($c) {
                return [
                    'numero_credito' => $c->numero_credito,
                    'monto' => (float) $c->monto,
                    'fecha_desembolso' => optional($c->fecha_desembolso)->format('Y-m-d'),
                    'linea' => $c->lineaCredito->nombre ?? null,
                ];
            });

            return response()->json($creditos);

        } catch (\Throwable $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}