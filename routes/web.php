<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SorteoController;
use App\Http\Controllers\Admin\PremioController;
use App\Http\Controllers\Admin\ConfiguracionGeneralController;
use App\Http\Controllers\Admin\BoletaController;
use App\Http\Controllers\Public\ConsultaBoletaController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Admin\GanadorController;
use App\Http\Controllers\Public\ResultadoController;
use App\Http\Controllers\Admin\PdfBoletaController;
use App\Http\Controllers\Admin\LineaCreditoController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\AsociadoController;
use App\Http\Controllers\Public\InformeBoletasController;
use App\Http\Controllers\Admin\MapaBoletasController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\BoletaDesignController;


Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->name('dashboard');
    Route::get('/debug-email/{credito}', function ($creditoId) {
        $credito = \App\Models\Credito::with('asociado')->findOrFail($creditoId);
        $boletas = \App\Models\Boleta::where('credito_id', $creditoId)
                    ->get();

        return new \App\Mail\BoletasPorCreditoMail($credito, $boletas);
    });
    Route::get('sorteos/{sorteo}/importar', [ImportController::class, 'form'])
        ->name('sorteos.import.form');

    Route::post('sorteos/{sorteo}/importar', [ImportController::class, 'import'])
        ->name('sorteos.import.store');
    Route::get('importar/plantilla', [ImportController::class, 'template'])
    ->name('import.template');
    
    Route::get('importar/plantilla-excel', [ImportController::class, 'templateExcel'])
    ->name('import.template.excel');
    Route::resource('sorteos', SorteoController::class);
    
    Route::resource('premios', PremioController::class);

    Route::get('asociados', [AsociadoController::class, 'index'])
    ->name('asociados.index');

    Route::get('asociados/{id}/creditos', [AsociadoController::class, 'creditos'])
        ->name('asociados.creditos');

    Route::get('boletas', [BoletaController::class, 'index'])->name('boletas.index');
    Route::get('boletas/sorteo/{sorteo}', [BoletaController::class, 'index'])
    ->name('boletas.sorteo');
    Route::post('boletas/generar', [BoletaController::class, 'generate'])->name('boletas.generate');
    Route::delete('boletas/sorteo/{sorteo}', [BoletaController::class, 'destroyBySorteo'])->name('boletas.destroyBySorteo');

    Route::get('sorteos/{sorteo}/kpi', [SorteoKpiController::class, 'show'])
        ->name('admin.sorteos.kpi');


    Route::get('ganadores', [GanadorController::class, 'index'])->name('ganadores.index');
    Route::post('ganadores/asignar-premio', [GanadorController::class, 'asignarPremio'])->name('ganadores.asignarPremio');
    Route::delete('ganadores/premio/{premio}/limpiar', [GanadorController::class, 'limpiarPremio'])->name('ganadores.limpiarPremio');
    Route::post('ganadores/{sorteo}/resultado', 
        [GanadorController::class, 'registrarResultado']
    )->name('ganadores.registrarResultado');
    Route::post('ganadores/guardar', [GanadorController::class, 'guardarResultado'])
    ->name('ganadores.guardar');
    Route::get('boletas/lookup/{numero}', [\App\Http\Controllers\Admin\BoletaController::class, 'lookupAsociado'])
    ->name('boletas.lookup');

    Route::resource('lineas', LineaCreditoController::class)->parameters([
        'lineas' => 'linea'
    ]);
    Route::get('boletas/{boleta}/pdf', [PdfBoletaController::class, 'generateFromBoleta'])->name('boletas.pdf');
    Route::get('pdf-boletas', [PdfBoletaController::class, 'form'])->name('pdf-boletas.form');
    Route::post('pdf-boletas', [PdfBoletaController::class, 'generate'])->name('pdf-boletas.generate');

    Route::get('boletas/mapa/{sorteo?}', [MapaBoletasController::class, 'index'])
        ->name('boletas.mapa');

    Route::get('reportes', [ReporteController::class, 'index'])
    ->name('reportes.index');

    Route::get('{sorteo}', [BoletaDesignController::class, 'edit'])
        ->name('boleta.design.edit');

    Route::post('{sorteo}', [BoletaDesignController::class, 'update'])
        ->name('boleta.design.update');

});

Route::get('/consulta', [ConsultaBoletaController::class, 'form'])->name('consulta.boletas.form');
Route::post('/consulta', [ConsultaBoletaController::class, 'searchByDocumento'])->name('consulta.boletas.search');
Route::get('/consulta/{token}', [ConsultaBoletaController::class, 'showByToken'])->name('consulta.boletas.token');
Route::get('/consulta/{token}/pdf', [PdfBoletaController::class, 'publicDownload'])->name('consulta.boletas.pdf');
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/resultados', [ResultadoController::class, 'index'])->name('resultados.index');

Route::get('/informe-boletas', [InformeBoletasController::class, 'index'])->name('public.informe');
