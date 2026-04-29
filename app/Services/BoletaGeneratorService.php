<?php

namespace App\Services;

use App\Models\Boleta;
use App\Models\ConfiguracionGeneral;
use App\Models\Credito;
use Illuminate\Support\Facades\DB;

class BoletaGeneratorService
{
    public function __construct(
        protected CreditoBoletaNotificationService $notificationService
    ) {
    }

    public function generateForSorteo(): array
    {
        $montoPorBoleta = (float) $this->getConfig('monto_por_boleta', 1000000);
        $longitud       = (int) $this->getConfig('longitud_numero_boleta', 4);
        $rangoDesde     = (int) $this->getConfig('rango_boleta_desde', 0);
        $rangoHasta     = (int) $this->getConfig('rango_boleta_hasta', 9999);

        // Solo créditos cuya línea tenga participa_sorteo = true y esté activa
        $creditos = Credito::query()
            ->with(['asociado', 'lineaCredito'])
            ->where('participa_sorteo', true)
            ->whereHas('lineaCredito', function ($q) {
                $q->where('participa_sorteo', true)
                  ->where('activo', true);
            })
            ->whereHas('asociado', function ($q) {
                $q->where('activo', true);
            })
            ->orderBy('id')
            ->get();

        if ($creditos->isEmpty()) {
            return [
                'success'       => false,
                'message'       => 'No hay créditos válidos para generar boletas.',
                'generated'     => 0,
                'asociados'     => 0,
                'emails_sent'   => 0,
                'emails_failed' => 0,
            ];
        }

        $numerosUsados             = Boleta::pluck('numero_boleta')->toArray();
        $generadas                 = 0;
        $asociadosConNuevasBoletas = [];
        $boletasNuevasPorCredito   = [];

        DB::transaction(function () use (
            $creditos,
            $montoPorBoleta,
            $longitud,
            $rangoDesde,
            $rangoHasta,
            &$numerosUsados,
            &$generadas,
            &$asociadosConNuevasBoletas,
            &$boletasNuevasPorCredito
        ) {
            foreach ($creditos as $credito) {
                $cantidadQueDeberiaTener = (int) floor(((float) $credito->monto) / $montoPorBoleta);

                if ($cantidadQueDeberiaTener <= 0) continue;

                $yaGeneradas   = Boleta::where('credito_id', $credito->id)->count();
                $nuevasBoletas = $cantidadQueDeberiaTener - $yaGeneradas;

                if ($nuevasBoletas <= 0) continue;

                $asociadosConNuevasBoletas[$credito->asociado_id] = true;

                for ($i = 0; $i < $nuevasBoletas; $i++) {
                    $numeroGenerado = $this->generateUniqueNumber(
                        $numerosUsados, $rangoDesde, $rangoHasta, $longitud
                    );

                    $boleta = Boleta::create([
                        'asociado_id'    => $credito->asociado_id,
                        'credito_id'     => $credito->id,
                        'numero_boleta'  => $numeroGenerado,
                        'monto_base'     => (float) $credito->monto,
                        'bloque_boletas' => $cantidadQueDeberiaTener,
                        'ganadora'       => false,
                    ]);

                    $boletasNuevasPorCredito[$credito->id][] = $boleta;
                    $numerosUsados[] = $numeroGenerado;
                    $generadas++;
                }
            }
        });

        $emailsSent   = 0;
        $emailsFailed = 0;

        foreach ($boletasNuevasPorCredito as $creditoId => $boletasCredito) {
            $credito = $creditos->firstWhere('id', $creditoId);
            if (!$credito) continue;

            $result = $this->notificationService->sendEmailForCredito(
                $credito,
                collect($boletasCredito)
            );

            $result['success'] ? $emailsSent++ : $emailsFailed++;
        }

        if ($generadas === 0) {
            return [
                'success'       => true,
                'message'       => 'No hay nuevas boletas por generar. Los créditos actuales ya tienen sus boletas asignadas.',
                'generated'     => 0,
                'asociados'     => 0,
                'emails_sent'   => 0,
                'emails_failed' => 0,
            ];
        }

        return [
            'success'       => true,
            'message'       => 'Boletas generadas correctamente.',
            'generated'     => $generadas,
            'asociados'     => count($asociadosConNuevasBoletas),
            'emails_sent'   => $emailsSent,
            'emails_failed' => $emailsFailed,
        ];
    }

    protected function getConfig(string $clave, $default = null)
    {
        return ConfiguracionGeneral::where('clave', $clave)->value('valor') ?? $default;
    }

    protected function generateUniqueNumber(array $usedNumbers, int $desde, int $hasta, int $length): string
    {
        $maxIntentos = 20000;
        $intentos    = 0;

        do {
            $numero     = random_int($desde, $hasta);
            $formateado = str_pad((string) $numero, $length, '0', STR_PAD_LEFT);
            $intentos++;
        } while (in_array($formateado, $usedNumbers) && $intentos < $maxIntentos);

        if ($intentos >= $maxIntentos) {
            throw new \RuntimeException('No fue posible generar más números únicos.');
        }

        return $formateado;
    }
}