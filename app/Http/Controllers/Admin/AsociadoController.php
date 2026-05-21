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
        $sorteoId = $request->get('sorteo_id');

        // 🔥 FIX IMPORTANTE
        $perPage = (int) $request->get('per_page', 10);

        $asociados = Asociado::withCount('creditos')
            ->when($sorteoId, function ($q) use ($sorteoId) {
                $q->whereHas('sorteos', function ($q2) use ($sorteoId) {
                    $q2->where('sorteos.id', $sorteoId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('documento', 'like', "%$search%")
                    ->orWhere('nombres', 'like', "%$search%")
                    ->orWhere('apellidos', 'like', "%$search%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $sorteos = \App\Models\Sorteo::orderByDesc('id')->get();

        return view('admin.asociados.index', compact(
            'asociados',
            'sorteos',
            'sorteoId',
            'search',
            'perPage' // ✔ ahora sí existe
        ));
    }
    public function creditos($id)
    {
        return response()->json([]);
    }
}