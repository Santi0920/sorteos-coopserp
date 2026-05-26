@extends('layouts.admin')

@section('content')
<div class="">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>
<div class="container py-4">

    <div class="text-center mb-1">
        <h1 class="fw-bold text-primary mt-2" style="font-size: 2.5rem;">
            🏆 {{ $sorteo->nombre ?? 'Sorteo sin nombre' }} 🏆
        </h1>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-4">

                    <h4 class="fw-bold mb-2">
                        📥 Importación de asociados
                    </h4>

                    <p class="text-muted mb-4">
                        Sube Excel o CSV con la estructura requerida.
                    </p>

                    <div class="alert alert-primary">

                        <h6 class="fw-bold">
                            🎟️ Configuración del sorteo
                        </h6>

                        <p class="mb-0">
                            Boletas por persona:
                            <b>{{ $sorteo->boletas_por_persona }}</b>
                        </p>

                    </div>

                    <div class="alert alert-info">

                        <h6 class="fw-bold mb-3">
                            📌 Columnas del archivo
                        </h6>

                        <div class="row">

                            <div class="col-md-6">
                                <ul>
                                    <li><b>Documento</b></li>
                                    <li>Nombres</li>
                                    <li>Apellidos</li>
                                    <li>Email</li>
                                    <li>Telefono</li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <ul>
                                    <li>Cuenta</li>
                                    <li>Agencia</li>
                                    <li>Nomina</li>
                                    <li>Coordinador</li>
                                    <li>Monto</li>
                                    <li>Dependencia</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <a href="{{ route('admin.import.template') }}"
                       class="btn btn-success w-100 mb-3">
                        📊 Descargar plantilla Excel
                    </a>

                    <form method="POST"
                          action="{{ route('admin.sorteos.import.store', $sorteo) }}"
                          enctype="multipart/form-data">

                        @csrf

                        <input type="file"
                               name="file"
                               class="form-control mb-3"
                               accept=".xlsx,.csv"
                               required>

                        <button class="btn btn-primary w-100 fw-bold">
                            🚀 Importar asociados
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- 🚨 MODAL DE ERRORES --}}
{{-- ========================= --}}

@if(session('import_errors') && count(session('import_errors')) > 0)

<div class="modal fade" id="importErrorsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    ⚠️ Errores de importación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p class="text-muted">
                    Se encontraron errores en las siguientes filas:
                </p>

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Fila</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('import_errors') as $error)
                            <tr>
                                <td>{{ $error['row'] }}</td>
                                <td class="text-danger">{{ $error['error'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = new bootstrap.Modal(document.getElementById('importErrorsModal'));
    modal.show();
});
</script>

@endif

@endsection