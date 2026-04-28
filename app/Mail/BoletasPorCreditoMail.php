<?php

namespace App\Mail;

use App\Models\Credito;
use App\Models\Sorteo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class BoletasPorCreditoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Credito $credito;
    public Sorteo $sorteo;
    public Collection $boletas;

    public function __construct(Credito $credito, Sorteo $sorteo, Collection $boletas)
    {
        $this->credito = $credito;
        $this->sorteo = $sorteo;
        $this->boletas = $boletas;
    }

    public function build()
    {
        $premios = \App\Models\Premio::where('sorteo_id', $this->sorteo->id)
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return $this->subject('COOPSERP - BOLETAS SORTEO MOTOS: ' . $this->sorteo->nombre)
            ->view('emails.boletas-por-credito')
            ->with([
                'premios' => $premios,
            ]);
    }
}