<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Boleta;
use App\Models\Premio;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfBoletaController extends Controller
{
    public function generateFromBoleta(Boleta $boleta)
    {
        $boleta->load([
            'asociado',
            'sorteo.design'
        ]);

        return $this->buildPdfAll(
            $boleta->asociado,
            $boleta->id // 👈 clave: enviar boleta específica
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
        ?int $boletaId = null // 👈 nuevo parámetro opcional
    ) {

        $query = Boleta::with([
                'sorteo',
                'sorteo.design'
            ])
            ->where('asociado_id', $asociado->id)
            ->orderBy('numero_boleta');

        // 👇 SI VIENE UNA BOLETA ESPECÍFICA, SOLO ESA
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

        // 🔥 IMPORTANTE:
        // Si es una sola boleta, usamos SU sorteo
        $primerSorteo = $boletas->first()->sorteo;

        $premios = Premio::where('activo', true)
            ->where('sorteo_id', $primerSorteo->id)
            ->orderBy('orden')
            ->get();

        $design = $primerSorteo->design;

        $pdf = Pdf::loadView(
            'pdf.boletas-individual',
            [
                'asociado' => $asociado,
                'boletas' => $boletas,
                'premios' => $premios,
                'design' => $design
            ]
        );

        $filename = 'boletas-' . $asociado->documento . '.pdf';

        return $pdf->stream($filename);
    }
}