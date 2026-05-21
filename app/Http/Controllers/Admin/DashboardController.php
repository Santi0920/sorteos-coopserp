<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Sorteo;
use App\Models\Premio;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosibles = 10000;

        // 📊 BOLETAS
        $boletasAsignadas = Boleta::count();
        $boletasPendientes = max(0, $totalPosibles - $boletasAsignadas);

        $porcentajeOcupacion = $totalPosibles > 0
            ? round(($boletasAsignadas / $totalPosibles) * 100, 2)
            : 0;

        // 🎯 SORTEOS
        $sorteosActivos = Sorteo::where('estado', 'programado')->count();

        // 🏆 GANADORES
        $ultimosGanadores = Premio::with('boletaGanadora.asociado', 'sorteo')
            ->whereNotNull('boleta_ganadora_id')
            ->latest()
            ->take(5)
            ->get();

        // 📈 EVOLUCIÓN DE BOLETAS (últimos 7 días)
        $boletasPorDia = Boleta::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->take(7)
            ->get();

        // 🏆 RANKING
        $rankingAsociados = Boleta::select(
                'asociado_id',
                'sorteo_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('asociado_id', 'sorteo_id')
            ->orderByDesc('total')
            ->with(['asociado', 'sorteo'])
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'boletasAsignadas',
            'boletasPendientes',
            'porcentajeOcupacion',
            'sorteosActivos',
            'ultimosGanadores',
            'boletasPorDia',
            'rankingAsociados'
        ));
    }
}