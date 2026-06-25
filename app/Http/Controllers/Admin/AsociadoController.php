<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class AsociadoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');


        $sorteos = Sorteo::orderByDesc('id')->get();

  
        $sorteoId = $request->get('sorteo_id') ?: optional($sorteos->first())->id;

        $sorteoSeleccionado = null;


        $perPage = (int) $request->get('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }


        if ($sorteoId) {
            $sorteoSeleccionado = Sorteo::find($sorteoId);

            if ($sorteoSeleccionado) {
                $query = $sorteoSeleccionado->asociados()
                    ->withCount('creditos')
                    ->when($search, function ($q) use ($search) {
                        $q->where(function ($qq) use ($search) {
                            $qq->where('asociados.documento', 'like', "%{$search}%")
                                ->orWhere('asociados.nombres', 'like', "%{$search}%")
                                ->orWhere('asociados.apellidos', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.email', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.telefono', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.cuenta', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.agencia', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.nomina', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.coordinador', 'like', "%{$search}%")
                                ->orWhere('sorteo_asociado.dependencia', 'like', "%{$search}%");
                        });
                    })
                    ->orderByDesc('asociados.id');

                $asociados = $query
                    ->paginate($perPage)
                    ->withQueryString();
            } else {
                $asociados = Asociado::whereRaw('1 = 0')
                    ->paginate($perPage)
                    ->withQueryString();
            }
        } else {
            $asociados = Asociado::whereRaw('1 = 0')
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('admin.asociados.index', compact(
            'asociados',
            'sorteos',
            'sorteoId',
            'sorteoSeleccionado',
            'search',
            'perPage'
        ));
    }

    public function creditos($id)
    {
        return response()->json([]);
    }
}