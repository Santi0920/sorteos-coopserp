<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PlantillaAsociadosExport;
use App\Http\Controllers\Controller;
use App\Models\Asociado;
use App\Models\Sorteo;
use App\Models\Import;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'file' => 'required|file|mimes:xlsx,csv,txt'
        ]);

        $file = $request->file('file');

        $path = $file->store('imports/sorteos', 'public');

        $rows = $this->readFile($file);

        if (count($rows) < 2) {
            return back()->with('error', 'Archivo vacío');
        }

        $headers = collect($rows[0])
            ->map(fn ($v) => (string) Str::of($v)->lower()->trim())
            ->toArray();

        unset($rows[0]);

        $map = array_flip($headers);

        $requiredColumns = [
            'documento',
            'nombres',
            'apellidos',
            'boletas por persona'
        ];

        foreach ($requiredColumns as $col) {
            if (!isset($map[$col])) {
                return back()->with('error', "Falta la columna obligatoria: $col");
            }
        }

        DB::beginTransaction();

        $import = Import::create([
            'sorteo_id' => $sorteo->id,
            'file_path' => $path,
            'rows_total' => count($rows),
            'rows_success' => 0,
            'rows_failed' => 0,
            'errors' => []
        ]);

        $success = 0;
        $failed = 0;
        $errors = [];

        try {

            foreach ($rows as $i => $row) {

                try {

                    $this->processRow($row, $map, $sorteo, $i + 2);
                    $success++;

                } catch (\Exception $e) {

                    $failed++;

                    $errors[] = [
                        'row' => $i + 2,
                        'error' => $e->getMessage()
                    ];
                }
            }

            $import->update([
                'rows_success' => $success,
                'rows_failed' => $failed,
                'errors' => $errors
            ]);

            DB::commit();

            return redirect()
                ->route('admin.sorteos.import.form', $sorteo)
                ->with('success', "Importación finalizada: $success OK, $failed errores")
                ->with('import_errors', $errors);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e);

            return back()->with('error', $e->getMessage());
        }
    }

    private function readFile($file)
    {
        $ext = $file->getClientOriginalExtension();

        if (in_array($ext, ['csv', 'txt'])) {

            $rows = [];
            $handle = fopen($file->getRealPath(), 'r');

            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }

            fclose($handle);

            return $rows;
        }

        return Excel::toArray([], $file)[0];
    }

    private function processRow($row, $map, $sorteo, $fila)
    {
        $documento = trim($this->cell($row, $map, 'documento'));

        if (!$documento) {
            throw new \Exception("Fila {$fila}: Documento obligatorio");
        }

        $boletasPorPersona = (int) $this->cell($row, $map, 'boletas por persona', 1);

        if ($boletasPorPersona < 1) {
            $boletasPorPersona = 1;
        }

        // Datos globales del asociado
        $asociado = Asociado::updateOrCreate(
            [
                'documento' => $documento,
            ],
            [
                'nombres' => $this->cell($row, $map, 'nombres'),
                'apellidos' => $this->cell($row, $map, 'apellidos'),
            ]
        );

        // Datos específicos de este sorteo
        $pivotData = [
            'boletas_por_persona' => $boletasPorPersona,
            'email' => $this->cell($row, $map, 'email'),
            'telefono' => $this->cell($row, $map, 'telefono'),
            'whatsapp' => $this->cell($row, $map, 'whatsapp'),
            'cuenta' => $this->cell($row, $map, 'cuenta'),
            'agencia' => $this->cell($row, $map, 'agencia'),
            'nomina' => $this->cell($row, $map, 'nomina'),
            'coordinador' => $this->cell($row, $map, 'coordinador'),
            'dependencia' => $this->cell($row, $map, 'dependencia'),
        ];

        $yaExisteEnSorteo = $sorteo->asociados()
            ->where('asociados.id', $asociado->id)
            ->exists();

        if ($yaExisteEnSorteo) {
            $sorteo->asociados()->updateExistingPivot($asociado->id, $pivotData);
        } else {
            $sorteo->asociados()->attach($asociado->id, $pivotData);
        }
    }

    private function cell($row, $map, string $column, $default = null)
    {
        if (!isset($map[$column])) {
            return $default;
        }

        $value = $row[$map[$column]] ?? $default;

        if (is_string($value)) {
            $value = trim($value);
        }

        return $value === '' ? $default : $value;
    }

    public function template()
    {
        return Excel::download(
            new PlantillaAsociadosExport(),
            'plantilla_importacion_asociados.xlsx'
        );
    }
}