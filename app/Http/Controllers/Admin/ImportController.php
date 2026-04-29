<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asociado;
use App\Models\Credito;
use App\Models\LineaCredito;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

    use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlantillaImportExport;
class ImportController extends Controller
{
    public function form()
    {
        return view('admin.importar');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx'
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $errores = [];
        $filaNumero = 1;

        DB::beginTransaction();

        try {

            // =========================================
            // 📌 CASO 1: CSV o TXT
            // =========================================
            if (in_array($extension, ['csv', 'txt'])) {

                $handle = fopen($file->getRealPath(), 'r');

                $header = fgetcsv($handle); // saltar encabezado

                while (($row = fgetcsv($handle)) !== false) {

                    $filaNumero++;

                    $this->procesarFila($row, $filaNumero, $errores);
                }

                fclose($handle);
            }

            // =========================================
            // 📌 CASO 2: EXCEL (.xlsx)
            // =========================================
            if ($extension === 'xlsx') {

                $rows = Excel::toArray([], $file)[0]; // primera hoja

                foreach ($rows as $index => $row) {

                    if ($index === 0) continue; // saltar encabezado

                    $filaNumero = $index + 1;

                    $this->procesarFila($row, $filaNumero, $errores);
                }
            }

            DB::commit();

            return back()->with('success',
                "Importación completada. Errores: " . count($errores) .
                (count($errores) ? " → " . implode(' | ', $errores) : '')
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    private function procesarFila($row, &$filaNumero, &$errores)
    {
        try {

            // Rellenar hasta 9 columnas por si email viene vacío
            $row = array_pad($row, 9, null);

            if (count($row) < 9) {
                $errores[] = "Fila $filaNumero: columnas incompletas";
                return;
            }

            [
                $cuenta,
                $agencia,
                $nomina,
                $cedula,
                $nombreCompleto,
                $lineaCodigo,
                $numeroCredito,
                $monto,
                $email
            ] = $row;

            // LIMPIEZA
            $cedula        = trim($cedula ?? '');
            $nombreCompleto = trim($nombreCompleto ?? '');
            $numeroCredito  = trim($numeroCredito ?? '');
            $lineaCodigo    = trim($lineaCodigo ?? '');
            $cuenta         = trim($cuenta ?? '');
            $agencia        = trim($agencia ?? '');
            $nomina         = trim($nomina ?? '');

            // EMAIL: validar formato, si no es válido guardar null
            $emailLimpio = trim($email ?? '');
            $emailFinal  = filter_var($emailLimpio, FILTER_VALIDATE_EMAIL)
                ? $emailLimpio
                : null;

            // VALIDACIONES OBLIGATORIAS
            if (!$cedula || !$nombreCompleto || !$numeroCredito || !$monto) {
                $errores[] = "Fila $filaNumero: datos vacíos";
                return;
            }

            // LIMPIAR MONTO
            $montoLimpio = (float) str_replace(['$', '.', ','], ['', '', '.'], $monto);

            if (!is_numeric($montoLimpio) || $montoLimpio <= 0) {
                $errores[] = "Fila $filaNumero: monto inválido ($monto)";
                return;
            }

            // SEPARAR NOMBRES Y APELLIDOS
            $partes    = explode(' ', $nombreCompleto);
            $nombres   = array_shift($partes);
            $apellidos = implode(' ', $partes);

            // ASOCIADO: crear o actualizar por cédula
            $asociado = Asociado::updateOrCreate(
                ['documento' => $cedula],
                [
                    'nombres'        => $nombres,
                    'apellidos'      => $apellidos,
                    'cuenta'         => $cuenta,
                    'agencia'        => $agencia,
                    'nomina'         => $nomina,
                    'email'          => $emailFinal,
                    'activo'         => true,
                    'token_consulta' => Str::uuid(),
                ]
            );

            // LÍNEA DE CRÉDITO
            $linea = LineaCredito::where('codigo', $lineaCodigo)->first();

            if (!$linea) {
                $errores[] = "Fila $filaNumero: línea no existe ($lineaCodigo)";
                return;
            }

            // EVITAR CRÉDITO DUPLICADO
            if (Credito::where('numero_credito', $numeroCredito)->exists()) {
                $errores[] = "Fila $filaNumero: crédito duplicado ($numeroCredito)";
                return;
            }

            // CREAR CRÉDITO
            Credito::create([
                'asociado_id'      => $asociado->id,
                'linea_credito_id' => $linea->id,
                'numero_credito'   => $numeroCredito,
                'monto'            => $montoLimpio,
                'fecha_desembolso' => now(),
                'participa_sorteo' => true,
            ]);

        } catch (\Exception $e) {
            $errores[] = "Fila $filaNumero: error → " . $e->getMessage();
        }
    }

    public function template()
    {
        $headers = [
            'Cuenta',
            'Agencia',
            'Nomina',
            'Cedula',
            'Nombre',
            'Linea',
            'Credito',
            'Monto',
            'Email'
        ];

        $example = [
            '125463',
            'Quibdo',
            'Beneficencia',
            '12356489',
            'Juan Perez',
            '99',
            '15489',
            '3900000',
            'juan@coopserp.com'
        ];

        $filename = "plantilla_importacion.csv";

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        fputcsv($handle, $example);
        rewind($handle);

        return response()->streamDownload(function () use ($handle) {
            fpassthru($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }


    public function templateExcel()
    {
        return Excel::download(new PlantillaImportExport, 'plantilla_importacion.xlsx');
    }
}