<?php

namespace App\Services;

use App\Mail\BoletasPorCreditoMail;
use App\Models\Boleta;
use App\Models\BoletaEnvio;
use App\Models\Credito;
use App\Models\Sorteo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreditoBoletaNotificationService
{
    public function sendEmailForCredito(Credito $credito, Sorteo $sorteo, Collection $boletas): array
    {
        $credito->loadMissing('asociado');

        if ($boletas->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No hay boletas nuevas para enviar.',
            ];
        }

        if (!$credito->asociado || !$credito->asociado->email) {
            foreach ($boletas as $boleta) {
                BoletaEnvio::create([
                    'boleta_id' => $boleta->id,
                    'canal' => 'correo',
                    'destino' => null,
                    'estado' => 'fallido',
                    'fecha_envio' => now(),
                    'respuesta' => 'El asociado no tiene correo registrado.',
                ]);
            }

            return [
                'success' => false,
                'message' => 'El asociado no tiene correo registrado.',
            ];
        }

        try {
            Mail::to($credito->asociado->email)->send(
                new BoletasPorCreditoMail($credito, $sorteo, $boletas)
            );

            foreach ($boletas as $boleta) {
                BoletaEnvio::create([
                    'boleta_id' => $boleta->id,
                    'canal' => 'correo',
                    'destino' => $credito->asociado->email,
                    'estado' => 'enviado',
                    'fecha_envio' => now(),
                    'respuesta' => 'Correo enviado por crédito correctamente.',
                ]);
            }

            return [
                'success' => true,
                'message' => 'Correo enviado correctamente.',
            ];
        } catch (Throwable $e) {
            foreach ($boletas as $boleta) {
                BoletaEnvio::create([
                    'boleta_id' => $boleta->id,
                    'canal' => 'correo',
                    'destino' => $credito->asociado->email,
                    'estado' => 'fallido',
                    'fecha_envio' => now(),
                    'respuesta' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Error enviando correo: ' . $e->getMessage(),
            ];
        }
    }
}