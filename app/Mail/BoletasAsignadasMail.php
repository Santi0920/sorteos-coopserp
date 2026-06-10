<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoletasAsignadasMail extends Mailable
{
    use Queueable, SerializesModels;

    public $asociado;
    public $boletas;
    public $sorteo;
    public $pdf;

    public function __construct($asociado, $boletas, $sorteo, $pdf)
    {
        $this->asociado = $asociado;
        $this->boletas = $boletas;
        $this->sorteo = $sorteo;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this
            ->subject('🎟️ Boletas asignadas - ' . $this->sorteo->nombre)
            ->view('emails.boletas-asignadas')
            ->attachData($this->pdf, 'boletas.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}