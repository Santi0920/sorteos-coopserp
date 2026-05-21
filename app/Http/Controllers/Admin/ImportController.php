<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Sorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function form(Sorteo $sorteo)
    {
        return view('admin.importar', compact('sorteo'));
    }

    public function import(Request $request, Sorteo $sorteo)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx'
        ]);

        $rows = $this->readFile($request->file('file'));

        if (count($rows) < 2) {
            return back()->with('error', 'Archivo vacío o inválido.');
        }

        // 🔥 HEADER MAPPING
        $headers = array_map(fn($h) => strtolower(trim($h)), $rows[0]);
        unset($rows[0]);

        $map = array_flip($headers);

        // 🔥 VALIDACIÓN MÍNIMA
        if (!isset($map['cedula']) || !isset($map['nombre'])) {
            return back()->with('error', 'El archivo debe tener columnas: cedula, nombre');
        }

        DB::beginTransaction();

        try {

            foreach ($rows as $index => $row) {
                $this->processRow($row, $map, $sorteo, $index + 2);
            }

            DB::commit();

            return redirect()
                ->route('admin.sorteos.index')
                ->with('success', 'Importación completada correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    private function readFile($file)
    {
        $ext = $file->getClientOriginalExtension();

        if (in_array($ext, ['csv', 'txt'])) {

            $handle = fopen($file->getRealPath(), 'r');

            $rows = [];
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }

            fclose($handle);

            return $rows;
        }

        if ($ext === 'xlsx') {
            return Excel::toArray([], $file)[0];
        }

        return [];
    }

    private function processRow($row, $map, $sorteo, $fila)
    {
        $cedula  = $row[$map['cedula']] ?? null;
        $nombre  = $row[$map['nombre']] ?? null;

        if (!$cedula || !$nombre) {
            throw new \Exception("Fila $fila: cédula o nombre obligatorio.");
        }

        $credito = isset($map['credito'])
            ? (float) ($row[$map['credito']] ?? 0)
            : 0;

        $asociado = Asociado::updateOrCreate(
            ['documento' => $cedula],
            [
                'nombres'  => $nombre,
                'agencia'  => $row[$map['agencia']] ?? null,
                'cuenta'   => $row[$map['cuenta']] ?? null,
                'nomina'   => $row[$map['nomina']] ?? null,
                'email'    => $row[$map['email']] ?? null,
            ]
        );

        // 🔥 CÁLCULO DE BOLETAS
        $boletas = 1;

        if ($sorteo->tipo_asignacion === 'por_valor') {

            if ($sorteo->monto_por_boleta > 0) {
                $boletas = max(1, floor($credito / $sorteo->monto_por_boleta));
            }
        }

        // 🔥 RELACIÓN
        $sorteo->asociados()->syncWithoutDetaching([
            $asociado->id => [
                'credito' => $credito,
                'boletas_asignadas' => $boletas,
            ]
        ]);
    }

    // 📥 DESCARGA PLANTILLA
    public function template()
    {
        return response()->streamDownload(function () {
            echo "cedula,nombre,agencia,cuenta,nomina,email,credito\n";
            echo "12345678,Juan Perez,Quibdo,1001,Nomina1,juan@email.com,5000000\n";
        }, 'plantilla_asociados.csv');
    }
}