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
        $boletas = Boleta::where('asociado_id', $asociado->id)
            ->orderBy('numero_boleta')
            ->get();

        if ($boletas->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'Ese asociado no tiene boletas registradas.');
        }

        $premios = Premio::where('activo', true)
            ->orderBy('orden')
            ->get();

        $pdf = Pdf::loadView('pdf.boletas-individual', [
            'asociado' => $asociado,
            'boletas'  => $boletas,
            'premios'  => $premios,
        ])->setPaper('a4', 'portrait');

        $filename = 'boletas-' . $asociado->documento . '.pdf';

        return $pdf->download($filename);
    }
}