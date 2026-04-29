<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Credito;
use App\Models\Sorteo;
use App\Services\BoletaGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoletaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));
        $perPage = (int) $request->get('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $boletas = Boleta::with(['asociado', 'credito'])  // ← quitar 'sorteo'
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numero_boleta', 'like', "%{$search}%")
                        ->orWhereHas('asociado', function ($sub) use ($search) {
                            $sub->where('nombres', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%")
                                ->orWhere('documento', 'like', "%{$search}%");
                        });
                        // ← quitar el orWhereHas('sorteo')
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $topAsociado = Boleta::query()
            ->select('asociado_id', DB::raw('COUNT(*) as total_boletas'))
            ->with('asociado')
            ->groupBy('asociado_id')
            ->orderByDesc('total_boletas')
            ->first();

        $totalMontoCreditos = Credito::query()
            ->where('participa_sorteo', true)
            ->whereHas('asociado', function ($q) {
                $q->where('activo', true);
            })
            ->sum('monto');

        return view('admin.boletas.index', compact(
            'boletas',
            'search',
            'perPage',
            'topAsociado',
            'totalMontoCreditos'
        ));
    }


    public function generate(Request $request, BoletaGeneratorService $service)
    {
        try {
            $result = $service->generateForSorteo();

            $message = $result['message'];

            if (isset($result['generated'])) {
                $message .= ' Total nuevas: ' . $result['generated'] . '.';
            }

            if (isset($result['emails_sent'])) {
                $message .= ' Correos enviados: ' . $result['emails_sent'] . '.';
            }

            if (isset($result['emails_failed'])) {
                $message .= ' Correos fallidos: ' . $result['emails_failed'] . '.';
            }

            return redirect()
                ->route('admin.boletas.index')
                ->with($result['success'] ? 'success' : 'error', $message);

        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.boletas.index')
                ->with('error', 'Error al generar boletas: ' . $e->getMessage());
        }
    }

    public function destroyBySorteo(Sorteo $sorteo)
    {
        $count = $sorteo->boletas()->count();

        if ($count === 0) {
            return redirect()
                ->route('admin.boletas.index')
                ->with('error', 'Ese sorteo no tiene boletas generadas.');
        }

        $sorteo->boletas()->delete();

        return redirect()
            ->route('admin.boletas.index')
            ->with('success', "Se eliminaron {$count} boletas del sorteo {$sorteo->nombre}.");
    }
}