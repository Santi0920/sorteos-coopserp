<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class SorteoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        $sorteos = Sorteo::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('loteria', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%");
            })
            ->orderBy('fecha_sorteo', 'asc')
            ->paginate(5)
            ->withQueryString();

        return view('admin.sorteos.index', compact('sorteos', 'search'));
    }

    public function create()
    {
        return view('admin.sorteos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'fecha_sorteo'    => ['required', 'date'],
            'loteria'         => ['nullable', 'string', 'max:255'],
            'estado'          => ['required', 'in:programado,ejecutado,cancelado'],
            'es_reprogramado' => ['required', 'boolean'],
            'observaciones'   => ['nullable', 'string'],
        ]);

        Sorteo::create($validated);

        return redirect()
            ->route('admin.sorteos.index')
            ->with('success', 'Sorteo creado correctamente.');
    }

    public function show(Sorteo $sorteo)
    {
        return view('admin.sorteos.show', compact('sorteo'));
    }

    public function edit(Sorteo $sorteo)
    {
        return view('admin.sorteos.edit', compact('sorteo'));
    }

    public function update(Request $request, Sorteo $sorteo)
    {
        $validated = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'fecha_sorteo'    => ['required', 'date'],
            'loteria'         => ['nullable', 'string', 'max:255'],
            'estado'          => ['required', 'in:programado,ejecutado,cancelado'],
            'es_reprogramado' => ['required', 'boolean'],
            'observaciones'   => ['nullable', 'string'],
        ]);

        $sorteo->update($validated);

        return redirect()
            ->route('admin.sorteos.index')
            ->with('success', 'Sorteo actualizado correctamente.');
    }

    public function destroy(Sorteo $sorteo)
    {
        $sorteo->delete();

        return redirect()
            ->route('admin.sorteos.index')
            ->with('success', 'Sorteo eliminado correctamente.');
    }
}