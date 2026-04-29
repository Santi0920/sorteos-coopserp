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


Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->name('dashboard');
    Route::get('/debug-email/{credito}/{sorteo}', function ($creditoId, $sorteoId) {
        $credito = \App\Models\Credito::with('asociado')->findOrFail($creditoId);
        $sorteo  = \App\Models\Sorteo::findOrFail($sorteoId);
        $boletas = \App\Models\Boleta::where('credito_id', $creditoId)
                    ->where('sorteo_id', $sorteoId)
                    ->get();

        return new \App\Mail\BoletasPorCreditoMail($credito, $sorteo, $boletas);
    });
    Route::get('importar', [ImportController::class, 'form'])
        ->name('import.form');

    Route::post('importar', [ImportController::class, 'import'])
        ->name('import.store');

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
    Route::post('boletas/generar', [BoletaController::class, 'generate'])->name('boletas.generate');
    Route::delete('boletas/sorteo/{sorteo}', [BoletaController::class, 'destroyBySorteo'])->name('boletas.destroyBySorteo');

    Route::get('configuracion', [ConfiguracionGeneralController::class, 'edit'])->name('configuracion.edit');
    Route::put('configuracion', [ConfiguracionGeneralController::class, 'update'])->name('configuracion.update');

    Route::get('ganadores', [GanadorController::class, 'index'])->name('ganadores.index');
    Route::post('ganadores/asignar-premio', [GanadorController::class, 'asignarPremio'])->name('ganadores.asignarPremio');
    Route::delete('ganadores/premio/{premio}/limpiar', [GanadorController::class, 'limpiarPremio'])->name('ganadores.limpiarPremio');

    Route::resource('lineas', LineaCreditoController::class)->parameters([
        'lineas' => 'linea'
    ]);
    Route::get('boletas/{boleta}/pdf', [PdfBoletaController::class, 'generateFromBoleta'])->name('boletas.pdf');
    Route::get('pdf-boletas', [PdfBoletaController::class, 'form'])->name('pdf-boletas.form');
    Route::post('pdf-boletas', [PdfBoletaController::class, 'generate'])->name('pdf-boletas.generate');

    Route::get('mapa-boletas', [App\Http\Controllers\Admin\MapaBoletasController::class, 'index'])
        ->name('boletas.mapa');
});

Route::get('/consulta', [ConsultaBoletaController::class, 'form'])->name('consulta.boletas.form');
Route::post('/consulta', [ConsultaBoletaController::class, 'searchByDocumento'])->name('consulta.boletas.search');
Route::get('/consulta/{token}', [ConsultaBoletaController::class, 'showByToken'])->name('consulta.boletas.token');
Route::get('/consulta/{token}/pdf', [PdfBoletaController::class, 'publicDownload'])->name('consulta.boletas.pdf');
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/resultados', [ResultadoController::class, 'index'])->name('resultados.index');

Route::get('/informe-boletas', [InformeBoletasController::class, 'index'])->name('public.informe');
Route::get('/detalle-boletas', [InformeBoletasController::class, 'detalle'])->name('public.detalle');