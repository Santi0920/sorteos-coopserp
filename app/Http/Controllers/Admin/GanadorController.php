<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Premio;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GanadorController extends Controller
{
    public function index(Request $request)
    {
        $sorteoId = $request->get('sorteo_id');

        $sorteos = Sorteo::orderBy('fecha_sorteo')->get();

        $sorteoSeleccionado = null;
        $boletas = collect();
        $premios = collect();

        if ($sorteoId) {
            $sorteoSeleccionado = Sorteo::with([
                'premios.boletaGanadora.asociado',
            ])->findOrFail($sorteoId);

            $boletas = Boleta::with(['asociado'])
                ->where('sorteo_id', $sorteoSeleccionado->id)
                ->orderBy('numero_boleta')
                ->get();

            $premios = $sorteoSeleccionado->premios()
                ->with('boletaGanadora.asociado')
                ->get();
        }

        return view('admin.ganadores.index', compact(
            'sorteos',
            'sorteoSeleccionado',
            'boletas',
            'premios'
        ));
    }

    public function asignarPremio(Request $request)
    {
        $validated = $request->validate([
            'premio_id' => ['required', 'exists:premios,id'],
            'boleta_id' => ['nullable', 'exists:boletas,id'],
        ]);

        $premio = Premio::with('sorteo')->findOrFail($validated['premio_id']);
        $boletaId = $validated['boleta_id'] ?? null;

        if ($boletaId) {
            $boleta = Boleta::findOrFail($boletaId);

            if ((int) $boleta->sorteo_id !== (int) $premio->sorteo_id) {
                return redirect()
                    ->route('admin.ganadores.index', ['sorteo_id' => $premio->sorteo_id])
                    ->with('error', 'La boleta seleccionada no pertenece al mismo sorteo del premio.');
            }

            $premioUsado = Premio::where('sorteo_id', $premio->sorteo_id)
                ->where('boleta_ganadora_id', $boletaId)
                ->where('id', '!=', $premio->id)
                ->first();

            if ($premioUsado) {
                return redirect()
                    ->route('admin.ganadores.index', ['sorteo_id' => $premio->sorteo_id])
                    ->with('error', 'Esa boleta ya está asignada a otro premio de este sorteo.');
            }
        }

        DB::transaction(function () use ($premio, $boletaId) {
            $premio->update([
                'boleta_ganadora_id' => $boletaId,
            ]);

            $this->syncEstadoSorteo($premio->sorteo_id);
            $this->syncBoletasGanadoras($premio->sorteo_id);
        });

        return redirect()
            ->route('admin.ganadores.index', ['sorteo_id' => $premio->sorteo_id])
            ->with('success', 'Premio actualizado correctamente.');
    }

    public function limpiarPremio(Premio $premio)
    {
        $sorteoId = $premio->sorteo_id;

        DB::transaction(function () use ($premio, $sorteoId) {
            $premio->update([
                'boleta_ganadora_id' => null,
            ]);

            $this->syncEstadoSorteo($sorteoId);
            $this->syncBoletasGanadoras($sorteoId);
        });

        return redirect()
            ->route('admin.ganadores.index', ['sorteo_id' => $sorteoId])
            ->with('success', 'Asignación del premio eliminada correctamente.');
    }

    protected function syncEstadoSorteo(int $sorteoId): void
    {
        $totalPremiosActivos = Premio::where('sorteo_id', $sorteoId)
            ->where('activo', true)
            ->count();

        $premiosAsignados = Premio::where('sorteo_id', $sorteoId)
            ->where('activo', true)
            ->whereNotNull('boleta_ganadora_id')
            ->count();

        $nuevoEstado = (
            $totalPremiosActivos > 0 &&
            $totalPremiosActivos === $premiosAsignados
        ) ? 'ejecutado' : 'programado';

        Sorteo::where('id', $sorteoId)->update([
            'estado' => $nuevoEstado,
        ]);
    }

    protected function syncBoletasGanadoras(int $sorteoId): void
    {
        // limpiamos todas
        Boleta::where('sorteo_id', $sorteoId)->update([
            'ganadora' => false,
        ]);

        // marcamos solo las que estén asociadas a premios
        $boletasGanadorasIds = Premio::where('sorteo_id', $sorteoId)
            ->where('activo', true)
            ->whereNotNull('boleta_ganadora_id')
            ->pluck('boleta_ganadora_id')
            ->toArray();

        if (!empty($boletasGanadorasIds)) {
            Boleta::whereIn('id', $boletasGanadorasIds)->update([
                'ganadora' => true,
            ]);
        }
    }
}