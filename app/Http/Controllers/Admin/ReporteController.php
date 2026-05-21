<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use App\Models\Boleta;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $sorteoId = $request->get('sorteo_id');

        $sorteos = Sorteo::orderByDesc('fecha_sorteo')->get();
        $sorteo = null;

        $totalEmitidas = 0;
        $totalAsignadas = 0;
        $totalPendientes = 0;
        $totalGanadoras = 0;

        $porAsociado = collect();
        $porAgencia = collect();
        $topAsociado = null;

        if ($sorteoId) {

            $sorteo = Sorteo::findOrFail($sorteoId);

            // 🔥 Pool total del sorteo
            $totalEmitidas = ($sorteo->numero_fin - $sorteo->numero_inicio) + 1;

            // 🔥 Asignadas
            $totalAsignadas = Boleta::where('sorteo_id', $sorteoId)->count();

            // 🔥 Pendientes
            $totalPendientes = $totalEmitidas - $totalAsignadas;

            // 🔥 Ganadoras
            $totalGanadoras = Boleta::where('sorteo_id', $sorteoId)
                ->where('ganadora', true)
                ->count();

            // 🔥 Top asociado
            $topAsociado = Boleta::selectRaw('asociado_id, COUNT(*) as total')
                ->where('sorteo_id', $sorteoId)
                ->with('asociado')
                ->groupBy('asociado_id')
                ->orderByDesc('total')
                ->first();

            // 🔥 Por asociado
            $porAsociado = Boleta::join('asociados', 'boletas.asociado_id', '=', 'asociados.id')
                ->where('boletas.sorteo_id', $sorteoId)
                ->selectRaw('asociados.documento, asociados.nombres, COUNT(*) as total')
                ->groupBy('asociados.documento', 'asociados.nombres')
                ->orderByDesc('total')
                ->paginate(5, ['*'], 'asociados_page')
                ->withQueryString();

            // 🔥 Por agencia
            $porAgencia = Boleta::join('asociados', 'boletas.asociado_id', '=', 'asociados.id')
                ->where('boletas.sorteo_id', $sorteoId)
                ->selectRaw('asociados.agencia, COUNT(*) as total')
                ->groupBy('asociados.agencia')
                ->orderByDesc('total')
                ->paginate(5, ['*'], 'agencias_page')
                ->withQueryString();
        }

        return view('admin.reportes.index', compact(
            'sorteos',
            'sorteo',
            'sorteoId',
            'totalEmitidas',
            'totalAsignadas',
            'totalPendientes',
            'totalGanadoras',
            'porAsociado',
            'porAgencia',
            'topAsociado'
        ));
    }
}