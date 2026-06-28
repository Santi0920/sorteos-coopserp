<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $sorteos = Sorteo::orderByDesc('fecha_sorteo')->get();

        $sorteoId = $request->get('sorteo_id') ?: optional($sorteos->first())->id;

        $sorteo = null;

        $totalEmitidas = 0;
        $totalAsignadas = 0;
        $totalPendientes = 0;
        $totalGanadoras = 0;
        $totalParticipantes = 0;
        $totalBoletasConfiguradas = 0;
        $totalDisponiblesPool = 0;

        $porAsociado = collect();
        $porAgencia = collect();
        $topAsociado = null;

        if ($sorteoId) {
            $sorteo = Sorteo::find($sorteoId);

            if ($sorteo) {
                /*
                |--------------------------------------------------------------------------
                | Pool total del sorteo
                |--------------------------------------------------------------------------
                */
                $totalEmitidas = (($sorteo->numero_fin ?? 0) - ($sorteo->numero_inicio ?? 0)) + 1;

                if ($totalEmitidas < 0) {
                    $totalEmitidas = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Total configurado desde la tabla pivote
                |--------------------------------------------------------------------------
                */
                $totalBoletasConfiguradas = (int) DB::table('sorteo_asociado')
                    ->where('sorteo_id', $sorteo->id)
                    ->sum('boletas_por_persona');

                /*
                |--------------------------------------------------------------------------
                | Total participantes del sorteo
                |--------------------------------------------------------------------------
                */
                $totalParticipantes = (int) DB::table('sorteo_asociado')
                    ->where('sorteo_id', $sorteo->id)
                    ->count();

                /*
                |--------------------------------------------------------------------------
                | Boletas reales generadas
                |--------------------------------------------------------------------------
                */
                $totalAsignadas = (int) Boleta::where('sorteo_id', $sorteo->id)
                    ->count();

                /*
                |--------------------------------------------------------------------------
                | Pendientes según la configuración del pivote
                |--------------------------------------------------------------------------
                */
                $totalPendientes = max(0, $totalBoletasConfiguradas - $totalAsignadas);

                /*
                |--------------------------------------------------------------------------
                | Disponibles dentro del pool total
                |--------------------------------------------------------------------------
                */
                $totalDisponiblesPool = max(0, $totalEmitidas - $totalAsignadas);

                /*
                |--------------------------------------------------------------------------
                | Boletas ganadoras
                |--------------------------------------------------------------------------
                */
                $totalGanadoras = (int) Boleta::where('sorteo_id', $sorteo->id)
                    ->where('ganadora', true)
                    ->count();

                /*
                |--------------------------------------------------------------------------
                | Top asociado por boletas generadas
                |--------------------------------------------------------------------------
                */
                $topAsociado = Boleta::query()
                    ->select('asociado_id', DB::raw('COUNT(*) as total'))
                    ->where('sorteo_id', $sorteo->id)
                    ->with('asociado')
                    ->groupBy('asociado_id')
                    ->orderByDesc('total')
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Subconsulta: boletas generadas por asociado
                |--------------------------------------------------------------------------
                */
                $boletasPorAsociadoSubquery = DB::table('boletas')
                    ->select(
                        'asociado_id',
                        DB::raw('COUNT(*) as total_generadas')
                    )
                    ->where('sorteo_id', $sorteo->id)
                    ->groupBy('asociado_id');

                /*
                |--------------------------------------------------------------------------
                | Reporte por asociado
                |--------------------------------------------------------------------------
                */
                $porAsociado = DB::table('sorteo_asociado')
                    ->join('asociados', 'asociados.id', '=', 'sorteo_asociado.asociado_id')
                    ->leftJoinSub($boletasPorAsociadoSubquery, 'bp', function ($join) {
                        $join->on('bp.asociado_id', '=', 'sorteo_asociado.asociado_id');
                    })
                    ->where('sorteo_asociado.sorteo_id', $sorteo->id)
                    ->select(
                        'asociados.id',
                        'asociados.documento',
                        'asociados.nombres',
                        'asociados.apellidos',
                        'sorteo_asociado.email',
                        'sorteo_asociado.telefono',
                        'sorteo_asociado.whatsapp',
                        'sorteo_asociado.cuenta',
                        'sorteo_asociado.agencia',
                        'sorteo_asociado.nomina',
                        'sorteo_asociado.coordinador',
                        'sorteo_asociado.dependencia',
                        'sorteo_asociado.boletas_por_persona as boletas_configuradas',
                        DB::raw('COALESCE(bp.total_generadas, 0) as total_generadas'),
                        DB::raw('COALESCE(bp.total_generadas, 0) as total')
                    )
                    ->orderByDesc('sorteo_asociado.boletas_por_persona')
                    ->paginate(10, ['*'], 'asociados_page')
                    ->withQueryString();

                /*
                |--------------------------------------------------------------------------
                | Subconsulta: participantes con agencia normalizada
                |--------------------------------------------------------------------------
                | Esto evita el error ONLY_FULL_GROUP_BY de MySQL.
                */
                $participantesAgenciaSubquery = DB::table('sorteo_asociado')
                    ->select(
                        'asociado_id',
                        'boletas_por_persona',
                        DB::raw("CASE 
                            WHEN agencia IS NULL OR agencia = '' THEN 'Sin agencia'
                            ELSE agencia
                        END as agencia_normalizada")
                    )
                    ->where('sorteo_id', $sorteo->id);

                /*
                |--------------------------------------------------------------------------
                | Reporte por agencia
                |--------------------------------------------------------------------------
                */
                $porAgencia = DB::query()
                    ->fromSub($participantesAgenciaSubquery, 'sa')
                    ->leftJoinSub($boletasPorAsociadoSubquery, 'bp', function ($join) {
                        $join->on('bp.asociado_id', '=', 'sa.asociado_id');
                    })
                    ->select(
                        'sa.agencia_normalizada as agencia',
                        DB::raw('COUNT(DISTINCT sa.asociado_id) as participantes'),
                        DB::raw('SUM(sa.boletas_por_persona) as boletas_configuradas'),
                        DB::raw('SUM(COALESCE(bp.total_generadas, 0)) as total_generadas'),
                        DB::raw('SUM(COALESCE(bp.total_generadas, 0)) as total')
                    )
                    ->groupBy('sa.agencia_normalizada')
                    ->orderByDesc('boletas_configuradas')
                    ->paginate(10, ['*'], 'agencias_page')
                    ->withQueryString();
            }
        }

        return view('admin.reportes.index', compact(
            'sorteos',
            'sorteo',
            'sorteoId',
            'totalEmitidas',
            'totalAsignadas',
            'totalPendientes',
            'totalGanadoras',
            'totalParticipantes',
            'totalBoletasConfiguradas',
            'totalDisponiblesPool',
            'porAsociado',
            'porAgencia',
            'topAsociado'
        ));
    }
}