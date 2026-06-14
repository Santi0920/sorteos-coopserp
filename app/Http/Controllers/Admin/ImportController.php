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
                ->route('admin.sorteos.index')
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
        $documento = trim($row[$map['documento']] ?? '');

        if (!$documento) {
            throw new \Exception("Fila {$fila}: Documento obligatorio");
        }

        $nuevasBoletas = intval($row[$map['boletas por persona']] ?? 1);

        $asociado = Asociado::where('documento', $documento)->first();

        if ($asociado) {

            $asociado->update([
                'nombres' => $row[$map['nombres']] ?? $asociado->nombres,
                'apellidos' => $row[$map['apellidos']] ?? $asociado->apellidos,
                'email' => $row[$map['email']] ?? $asociado->email,
                'telefono' => $row[$map['telefono']] ?? $asociado->telefono,
                'cuenta' => $row[$map['cuenta']] ?? $asociado->cuenta,
                'agencia' => $row[$map['agencia']] ?? $asociado->agencia,
                'nomina' => $row[$map['nomina']] ?? $asociado->nomina,
                'coordinador' => $row[$map['coordinador']] ?? $asociado->coordinador,
                'dependencia' => $row[$map['dependencia']] ?? $asociado->dependencia,

                // SUMA LAS BOLETAS EXISTENTES
                'boletas_por_persona' => $asociado->boletas_por_persona + $nuevasBoletas,
            ]);

        } else {

            $asociado = Asociado::create([
                'documento' => $documento,
                'nombres' => $row[$map['nombres']] ?? null,
                'apellidos' => $row[$map['apellidos']] ?? null,
                'email' => $row[$map['email']] ?? null,
                'telefono' => $row[$map['telefono']] ?? null,
                'cuenta' => $row[$map['cuenta']] ?? null,
                'boletas_por_persona' => $nuevasBoletas,
                'agencia' => $row[$map['agencia']] ?? null,
                'nomina' => $row[$map['nomina']] ?? null,
                'coordinador' => $row[$map['coordinador']] ?? null,
                'dependencia' => $row[$map['dependencia']] ?? null,
            ]);
        }

        $sorteo->asociados()->syncWithoutDetaching([
            $asociado->id
        ]);
    }

    public function template()
    {
        return Excel::download(
            new PlantillaAsociadosExport(),
            'plantilla_importacion_asociados.xlsx'
        );
    }
}