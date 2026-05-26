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

        $validated =
            $request
            ->validate([

                'nombre'=>[
                    'required'
                ],

                'fecha_sorteo'=>[
                    'required',
                    'date'
                ],

                'loteria'=>[
                    'nullable'
                ],

                'estado'=>[
                    'required'
                ],

                'numero_inicio'=>[
                    'integer'
                ],

                'numero_fin'=>[
                    'required',
                    'integer'
                ],



                'boletas_por_persona'=>[
                    'required',
                    'integer',
                    'min:1'
                ],

                'es_reprogramado'=>[
                    'required'
                ],

                'observaciones'=>[
                    'nullable'
                ]

            ]);

        $validated['activo'] =
            $request
            ->boolean(
                'activo'
            );

        $sorteo =
            Sorteo::create(
                $validated
            );

        $service
            ->generarPool(
                $sorteo
            );

        return redirect()

            ->route(
                'admin.sorteos.index'
            )

            ->with(

                'success',

                'Sorteo creado'

            );
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

        $validated =
            $request
            ->validate([

                'nombre' => [
                    'required'
                ],

                'fecha_sorteo' => [
                    'required',
                    'date'
                ],

                'loteria' => [
                    'nullable'
                ],

                'estado' => [
                    'required'
                ],

                'numero_inicio' => [
                    'integer',
                    'min:0',
                ],

                'numero_fin' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'boletas_por_persona' => [
                    'required',
                    'integer',
                    'min:1'
                ],

                'es_reprogramado' => [
                    'required'
                ],

                'observaciones' => [
                    'nullable'
                ]

            ]);

        $validated['activo'] =
            $request
            ->boolean(
                'activo'
            );



        if (
            $sorteo
                ->boletas()
                ->exists()
        ) {

            unset(

                $validated[
                    'numero_inicio'
                ],

                $validated[
                    'numero_fin'
                ],

                $validated[
                    'boletas_por_persona'
                ]

            );

        }

        $sorteo
            ->update(
                $validated
            );

        return redirect()

            ->route(
                'admin.sorteos.index'
            )

            ->with(

                'success',

                'Sorteo actualizado correctamente'

            );

    }

    public function destroy(Sorteo $sorteo)
    {
        $sorteo->delete();

        return redirect()
            ->route('admin.sorteos.index')
            ->with('success', 'Sorteo eliminado correctamente.');
    }
}