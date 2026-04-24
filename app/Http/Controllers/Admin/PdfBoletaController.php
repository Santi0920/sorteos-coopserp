<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Boleta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfBoletaController extends Controller
{
    public function generateFromBoleta(Boleta $boleta)
    {
        $boleta->load(['asociado']);

        return $this->buildPdfAll($boleta->asociado);
    }

    public function publicDownload(string $token)
    {
        $asociado = Asociado::where('token_consulta', $token)
            ->where('activo', true)
            ->firstOrFail();

        return $this->buildPdfAll($asociado);
    }

    protected function buildPdfAll(Asociado $asociado)
    {
        $boletas = Boleta::with('sorteo')
            ->where('asociado_id', $asociado->id)
            ->orderBy('sorteo_id')
            ->orderBy('numero_boleta')
            ->get();

        if ($boletas->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'Ese asociado no tiene boletas registradas.');
        }

        $pdf = Pdf::loadView('pdf.boletas-individual', [
            'asociado' => $asociado,
            'boletas' => $boletas,
        ])->setPaper('a4', 'portrait');

        $filename = 'boletas-' . $asociado->documento . '.pdf';

        return $pdf->download($filename);
    }
}