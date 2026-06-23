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
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <h6 class="fw-bold mb-2">Errores de validación</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger rounded-4">
                            <h6 class="fw-bold mb-2">Error general</h6>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif



                    @if (session('import_errors') && count(session('import_errors')))
                        <div class="alert alert-warning rounded-4">
                            <h6 class="fw-bold mb-2">
                                Filas con error: {{ count(session('import_errors')) }}
                            </h6>

                            <div style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-bordered mb-0 bg-white">
                                    <thead>
                                        <tr>
                                            <th>Fila</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('import_errors') as $error)
                                            <tr>
                                                <td>{{ $error['row'] ?? '—' }}</td>
                                                <td>{{ $error['error'] ?? 'Error desconocido' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
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


                    </div>

                    <div class="alert alert-info">

                        <h6 class="fw-bold mb-3">
                            📌 Columnas del archivo
                        </h6>

                        <div class="row">

                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><strong>1.</strong> Documento</li>
                                        <li><strong>2.</strong> Nombres</li>
                                        <li><strong>3.</strong> Apellidos</li>
                                        <li><strong>4.</strong> Correo Electrónico</li>
                                        <li><strong>5.</strong> Teléfono</li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><strong>6.</strong> Cuenta</li>
                                        <li><strong>7.</strong> Boletas por Persona</li>
                                        <li><strong>8.</strong> Agencia</li>
                                        <li><strong>9.</strong> Nómina</li>
                                        <li><strong>10.</strong> Coordinador</li>
                                        <li><strong>11.</strong> Dependencia</li>
                                    </ul>
                                </div>
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


@endsection