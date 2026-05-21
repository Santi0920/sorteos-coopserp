<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use App\Services\SorteoNumeroService;
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
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sorteos.index', compact('sorteos', 'search'));
    }

    public function create()
    {
        return view('admin.sorteos.create');
    }

    public function store(Request $request, SorteoNumeroService $service)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'fecha_sorteo' => ['required', 'date'],
            'loteria' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:programado,ejecutado,cancelado'],

            'numero_inicio' => [
                'integer',
                'min:0',
                'max:9999'
            ],

            'numero_fin' => [
                'required',
                'integer',
                'min:0',
                'max:9999'
            ],

            'tipo_asignacion' => ['required', 'in:equitativo,monto'],
            'monto_por_boleta' => ['nullable', 'numeric', 'min:0'],
            'es_reprogramado' => ['required', 'boolean'],
            'observaciones' => ['nullable', 'string'],
        ], [
            'numero_inicio.integer' => 'El número inicial debe ser un número válido.',
            'numero_fin.integer' => 'El número final debe ser un número válido.',
            'numero_fin.max' => 'El número final no puede ser mayor a 9999.',
        ]);

        $validated['activo'] = $request->has('activo');

        // 🔥 1. CREAR EL SORTEO PRIMERO
        $sorteo = Sorteo::create($validated);

        // 🔥 2. GENERAR POOL AUTOMÁTICAMENTE
        $service->generarPool($sorteo);

        return redirect()
            ->route('admin.sorteos.index')
            ->with('success', 'Sorteo creado correctamente y pool generado.');
    }

    public function show(Sorteo $sorteo)
    {
        $sorteo->load(['premios', 'boletas']);

        return view('admin.sorteos.show', compact('sorteo'));
    }

    public function edit(Sorteo $sorteo)
    {
        return view('admin.sorteos.edit', compact('sorteo'));
    }

    public function update(Request $request, Sorteo $sorteo)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],

            'fecha_sorteo' => ['required', 'date'],

            'loteria' => ['nullable', 'string', 'max:255'],

            'estado' => ['required', 'in:programado,ejecutado,cancelado'],

            'numero_inicio' => [
                'integer',
                'min:0',
                'max:9999'
            ],

            'numero_fin' => [
                'required',
                'integer',
                'min:0',
                'max:9999'
            ],

            'tipo_asignacion' => ['required', 'in:equitativo,monto'],

            'monto_por_boleta' => ['nullable', 'numeric', 'min:0'],

            'es_reprogramado' => ['required', 'boolean'],

            'observaciones' => ['nullable', 'string'],
        ], [
            'numero_inicio.integer' => 'El número inicial debe ser un número válido.',
            'numero_fin.integer' => 'El número final debe ser un número válido.',
            'numero_fin.max' => 'El número final no puede ser mayor a 9999.',
        ]);

        $validated['activo'] = $request->has('activo');

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