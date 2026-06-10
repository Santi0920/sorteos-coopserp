<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use App\Mail\BoletasAsignadasMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BoletaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));
        $perPage = (int) $request->get('per_page', 10);
        $sorteoId = $request->get('sorteo_id');

        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = Boleta::with(['asociado', 'credito', 'sorteo']);

        if ($sorteoId) {
            $query->where('sorteo_id', $sorteoId);
        }

        $boletas = $query
            ->when($search, function ($q) use ($search) {
                $q->where('numero_boleta', 'like', "%{$search}%")
                    ->orWhereHas('asociado', function ($sub) use ($search) {
                        $sub->where('nombres', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%")
                            ->orWhere('documento', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $topAsociado = Boleta::selectRaw('asociado_id, COUNT(*) as total_boletas')
            ->where('sorteo_id', $sorteoId) 
            ->with('asociado')
            ->groupBy('asociado_id')
            ->orderByDesc('total_boletas')
            ->first();
        $totalMontoCreditos = Boleta::sum('monto_base');
        $sorteos = Sorteo::orderBy('fecha_sorteo', 'desc')->get();

        return view('admin.boletas.index', compact(
            'boletas',
            'search',
            'perPage',
            'sorteos',
            'sorteoId',
            'topAsociado',
            'totalMontoCreditos'
        ));
    }

    public function generate(Request $request, \App\Services\BoletaGeneratorService $service)
    {
        $request->validate([
            'sorteo_id' => 'required|exists:sorteos,id'
        ]);

        $sorteo = \App\Models\Sorteo::findOrFail($request->sorteo_id);

        $result = $service->generateForSorteo($sorteo);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        /*
        ==========================================
        ENVIAR CORREOS POR ASOCIADO (1 SOLO EMAIL)
        ==========================================
        */

        foreach ($result['boletasPorAsociado'] as $asociadoId => $boletas) {

            $asociado = $boletas->first()->asociado;
            $sorteo = $boletas->first()->sorteo;

            if (!$asociado || !$asociado->email) {
                continue;
            }

            /*
            ==========================================
            GENERAR PDF MULTIPLE
            ==========================================
            */

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.boletas-multiples', [
                'boletas' => $boletas,
                'asociado' => $asociado,
                'premios' => $sorteo->premios ?? [],
                'design' => $sorteo->design ?? null,
            ])->output();

            /*
            ==========================================
            ENVIAR EMAIL
            ==========================================
            */

            \Illuminate\Support\Facades\Mail::to($asociado->email)->send(
                new \App\Mail\BoletasAsignadasMail(
                    $asociado,
                    $boletas,
                    $sorteo,
                    $pdf
                )
            );
        }

        return back()->with('success', $result['message']);
    }

    public function destroyBySorteo(Sorteo $sorteo)
    {
        $count = $sorteo->boletas()->count();

        if ($count === 0) {
            return back()->with('error', 'No hay boletas.');
        }

        $sorteo->boletas()->delete();

        return back()->with('success', "Se eliminaron {$count} boletas.");
    }
}