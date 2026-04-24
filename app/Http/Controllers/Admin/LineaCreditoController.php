<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LineaCredito;
use Illuminate\Http\Request;

class LineaCreditoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));
        $perPage = (int) $request->get('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $lineas = LineaCredito::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('codigo', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%");
                });
            })
            ->orderBy('codigo')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.lineas.index', compact('lineas', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.lineas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:20', 'unique:lineas_credito,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'participa_sorteo' => ['required', 'boolean'],
            'activo' => ['required', 'boolean'],
        ]);

        LineaCredito::create($validated);

        return redirect()
            ->route('admin.lineas.index')
            ->with('success', 'Línea de crédito creada correctamente.');
    }

    public function show(LineaCredito $linea)
    {
        return view('admin.lineas.show', compact('linea'));
    }

    public function edit(LineaCredito $linea)
    {
        return view('admin.lineas.edit', compact('linea'));
    }

    public function update(Request $request, LineaCredito $linea)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:20', 'unique:lineas_credito,codigo,' . $linea->id],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'participa_sorteo' => ['required', 'boolean'],
            'activo' => ['required', 'boolean'],
        ]);

        $linea->update($validated);

        return redirect()
            ->route('admin.lineas.index')
            ->with('success', 'Línea de crédito actualizada correctamente.');
    }

    public function destroy(LineaCredito $linea)
    {
        if ($linea->creditos()->count() > 0) {
            return redirect()
                ->route('admin.lineas.index')
                ->with('error', 'No puedes eliminar esta línea porque tiene créditos asociados.');
        }
        $linea->delete();

        return redirect()
            ->route('admin.lineas.index')
            ->with('success', 'Línea de crédito eliminada correctamente.');
    }
}