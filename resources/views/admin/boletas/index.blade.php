@extends('layouts.admin')

@php
    $title = 'Gestión de Boletas';
    $subtitle = 'Genera, consulta y administra las boletas del sistema.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.boletas.mapa') }}"
    class="btn btn-warning fw-bold me-2 fs-5">
        <i class="bi bi-grid-3x3-gap"></i> Ver mapa
    </a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generarBoletasModal">
        <i class="bi bi-magic me-1"></i> Generar boletas
    </button>
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="stats-box">
                <p>Total boletas</p>
                <h3>{{ $boletas->total() }}</h3>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-muted small mb-2">Persona con más boletas</div>

                    @if($topAsociado && $topAsociado->asociado)
                        <div class="fw-bold fs-5">
                            {{ $topAsociado->asociado->nombre_completo }}
                        </div>
                        <div class="text-muted">
                            {{ $topAsociado->asociado->documento }}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                {{ $topAsociado->total_boletas }} boletas
                            </span>
                        </div>
                    @else
                        <div class="fw-semibold">Sin datos</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card card h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-muted small mb-2">Suma total de créditos</div>
                    <div class="fw-bold fs-3">
                        ${{ number_format((float) $totalMontoCreditos, 0, ',', '.') }}
                    </div>
                    <div class="text-muted small mt-1">
                        Créditos únicos acumulados de asociados activos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Listado de boletas</h5>
                <small class="text-muted">Consulta las boletas generadas por asociado y crédito.</small>
            </div>

            <form method="GET" action="{{ route('admin.boletas.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Buscar boleta o asociado"
                        style="min-width: 280px;"
                    >
                </div>

                <div class="col-auto">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="10"  {{ (int)$perPage === 10  ? 'selected' : '' }}>10</option>
                        <option value="25"  {{ (int)$perPage === 25  ? 'selected' : '' }}>25</option>
                        <option value="50"  {{ (int)$perPage === 50  ? 'selected' : '' }}>50</option>
                        <option value="100" {{ (int)$perPage === 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($boletas->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Asociado</th>
                                <th>Documento</th>
                                <th>Crédito</th>
                                <th>Monto base</th>
                                <th>Ganadora</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($boletas as $boleta)
                                <tr>
                                    <td>{{ $boleta->id }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                            {{ $boleta->numero_boleta }}
                                        </span>
                                    </td>
                                    <td>{{ $boleta->asociado?->nombre_completo ?? '—' }}</td>
                                    <td>{{ $boleta->asociado?->documento ?? '—' }}</td>
                                    <td>{{ $boleta->credito?->numero_credito ?? '—' }}</td>
                                    <td>${{ number_format((float)$boleta->monto_base, 0, ',', '.') }}</td>
                                    <td>
                                        @if($boleta->ganadora)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Sí</span>
                                        @else
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">No</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.boletas.pdf', $boleta) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <div class="text-muted small">
                        Mostrando {{ $boletas->firstItem() }} a {{ $boletas->lastItem() }} de {{ $boletas->total() }} registros
                    </div>
                    <div>
                        {{ $boletas->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated fs-1 text-muted"></i>
                    <h5 class="mt-3 fw-bold">No hay boletas generadas</h5>
                    <p class="text-muted">Genera boletas para comenzar.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL GENERAR BOLETAS -->
    <div class="modal fade" id="generarBoletasModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Generar boletas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" id="cerrarGenerarBoletasBtn"></button>
                </div>

                <form action="{{ route('admin.boletas.generate') }}" method="POST" id="generarBoletasForm">
                    @csrf

                    <div class="modal-body">

                        <!-- CAMPOS NORMALES -->
                        <div id="generarBoletasCampos">
                            <div class="alert alert-warning rounded-4 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Puedes generar boletas varias veces. El sistema solo agregará nuevas boletas a los créditos que todavía no tengan las que les corresponden.
                            </div>
                        </div>

                        <!-- PROGRESO -->
                        <div id="generarBoletasProgreso" class="d-none text-center py-3">
                            <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;" role="status"></div>

                            <h5 class="fw-bold mb-2">Procesando generación</h5>
                            <p class="text-muted mb-4" id="textoProgresoBoletas">Iniciando...</p>

                            <div class="progress rounded-pill" style="height: 10px;">
                                <div id="barraProgresoBoletas"
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                    role="progressbar"
                                    style="width: 5%; transition: width 0.8s ease;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" id="generarBoletasFooter">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitGenerarBoletas">
                            <i class="bi bi-magic me-1"></i> Generar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form      = document.getElementById('generarBoletasForm');
        const campos    = document.getElementById('generarBoletasCampos');
        const progreso  = document.getElementById('generarBoletasProgreso');
        const footer    = document.getElementById('generarBoletasFooter');
        const texto     = document.getElementById('textoProgresoBoletas');
        const barra     = document.getElementById('barraProgresoBoletas');
        const cerrarBtn = document.getElementById('cerrarGenerarBoletasBtn');

        if (!form) return;

        form.addEventListener('submit', function () {
            campos.classList.add('d-none');
            progreso.classList.remove('d-none');
            footer.classList.add('d-none');

            if (cerrarBtn) cerrarBtn.style.display = 'none';

            texto.textContent = 'Calculando boletas por crédito...';
            barra.style.width = '15%';

            setTimeout(() => {
                texto.textContent = 'Generando números únicos...';
                barra.style.width = '35%';
            }, 1000);

            setTimeout(() => {
                texto.textContent = 'Guardando boletas en la base de datos...';
                barra.style.width = '55%';
            }, 2500);

            setTimeout(() => {
                texto.textContent = 'Enviando correos electrónicos a los asociados...';
                barra.style.width = '75%';
            }, 4000);

            setTimeout(() => {
                texto.textContent = 'Finalizando proceso, por favor espera...';
                barra.style.width = '92%';
            }, 6000);
        });
    });
</script>
@endpush