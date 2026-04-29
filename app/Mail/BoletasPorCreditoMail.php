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
    public Collection $boletas;

    public function __construct(Credito $credito, Collection $boletas)  // ← sin Sorteo
    {
        $this->credito = $credito;
        $this->boletas = $boletas;
    }

    public function build()
    {
        return $this->subject('COOPSERP - BOLETAS SORTEO MOTOS')
            ->view('emails.boletas-por-credito')
            ->with([
                'premios' => \App\Models\Premio::where('activo', true)
                                ->orderBy('orden')
                                ->get(),
            ]);
    }
}
