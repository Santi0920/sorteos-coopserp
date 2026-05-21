<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Premio;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PremioController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));
        $perPage = (int) $request->get('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $premios = Premio::with([
                'boletaGanadora',
                'sorteo'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%")
                      ->orWhereHas('sorteo', function ($sub) use ($search) {
                          $sub->where('nombre', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('orden')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.premios.index', compact('premios', 'search', 'perPage'));
    }

    public function create()
    {
        $sorteos = Sorteo::orderBy('fecha_sorteo')->get();

        return view('admin.premios.create', compact('sorteos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sorteo_id' => ['required', 'exists:sorteos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'orden' => ['required', 'integer', 'min:1'],
            'activo' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('premios', 'public');
        }

        Premio::create($validated);

        return redirect()
            ->route('admin.premios.index')
            ->with('success', 'Premio creado correctamente.');
    }

    public function show(Premio $premio)
    {
        $premio->load('sorteo');

        return view('admin.premios.show', compact('premio'));
    }

    public function edit(Premio $premio)
    {
        $sorteos = Sorteo::orderBy('fecha_sorteo')->get();

        return view('admin.premios.edit', compact('premio', 'sorteos'));
    }

    public function update(Request $request, Premio $premio)
    {
        $validated = $request->validate([
            'sorteo_id' => ['required', 'exists:sorteos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'orden' => ['required', 'integer', 'min:1'],
            'activo' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('imagen')) {
            if ($premio->imagen && Storage::disk('public')->exists($premio->imagen)) {
                Storage::disk('public')->delete($premio->imagen);
            }

            $validated['imagen'] = $request->file('imagen')->store('premios', 'public');
        }

        $premio->update($validated);

        return redirect()
            ->route('admin.premios.index')
            ->with('success', 'Premio actualizado correctamente.');
    }

    public function destroy(Premio $premio)
    {
        if ($premio->imagen && Storage::disk('public')->exists($premio->imagen)) {
            Storage::disk('public')->delete($premio->imagen);
        }

        $premio->delete();

        return redirect()
            ->route('admin.premios.index')
            ->with('success', 'Premio eliminado correctamente.');
    }
}