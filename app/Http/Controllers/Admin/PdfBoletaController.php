<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Boleta;
use App\Models\Premio;
use App\Models\Sorteo;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfBoletaController extends Controller
{
    public function generateFromBoleta(Boleta $boleta)
    {
        $boleta->load([
            'asociado',
            'sorteo.design',
        ]);

        return $this->buildPdfAll(
            $boleta->asociado,
            $boleta->id
        );
    }

    public function publicDownload(string $token)
    {
        $asociado = Asociado::where('token_consulta', $token)
            ->where('activo', true)
            ->firstOrFail();

        return $this->buildPdfAll($asociado);
    }

    protected function buildPdfAll(
        Asociado $asociado,
        ?int $boletaId = null
    ) {
        $query = Boleta::with([
                'sorteo',
                'sorteo.design',
            ])
            ->where('asociado_id', $asociado->id)
            ->orderBy('sorteo_id')
            ->orderBy('numero_boleta');

        if ($boletaId) {
            $query->where('id', $boletaId);
        }

        $boletas = $query->get();

        if ($boletas->isEmpty()) {
            return back()->with(
                'error',
                'No se encontraron boletas para generar el PDF.'
            );
        }

   
        $sorteoIds = $boletas
            ->pluck('sorteo_id')
            ->unique()
            ->values();

    
        $participacionesPorSorteo = collect();

        $sorteos = Sorteo::whereIn('id', $sorteoIds)->get();

        foreach ($sorteos as $sorteo) {
            $participacion = $sorteo->asociados()
                ->where('asociados.id', $asociado->id)
                ->first();

            if ($participacion) {
                $participacionesPorSorteo->put($sorteo->id, $participacion);
            }
        }


        $premiosPorSorteo = Premio::where('activo', true)
            ->whereIn('sorteo_id', $sorteoIds)
            ->orderBy('orden')
            ->get()
            ->groupBy('sorteo_id');


        $primerSorteo = $boletas->first()->sorteo;

        $premios = $premiosPorSorteo->get($primerSorteo->id, collect());

        $design = $primerSorteo->design;

        $pdf = Pdf::loadView(
            'pdf.boletas-individual',
            [
                'asociado' => $asociado,
                'boletas' => $boletas,
                'premios' => $premios,
                'premiosPorSorteo' => $premiosPorSorteo,
                'design' => $design,
                'participacionesPorSorteo' => $participacionesPorSorteo,
            ]
        );

        $filename = 'boletas-' . $asociado->documento . '.pdf';

        return $pdf->stream($filename);
    }
}