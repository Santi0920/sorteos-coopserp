@extends('layouts.admin')

@php
    $title = 'Gestión de Boletas';
    $subtitle = 'Genera, consulta y administra las boletas del sistema.';
@endphp

@section('topbar_actions')
    <button class="btn btn-warning fw-bold me-2"
            data-bs-toggle="modal"
            data-bs-target="#mapaModal">
        <i class="bi bi-grid-3x3-gap"></i> Ver mapa
    </button>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generarBoletasModal">
        <i class="bi bi-magic me-1"></i> Generar boletas
    </button>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>
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


    </div>

    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Listado de boletas</h5>
                <small class="text-muted">Consulta las boletas generadas por asociado y crédito.</small>
            </div>

            <form method="GET" action="{{ route('admin.boletas.index') }}" class="row g-2 align-items-center">

                <div class="col-auto">
                    <select name="sorteo_id" class="form-select" onchange="this.form.submit()">
                        @foreach($sorteos as $s)
                            <option value="{{ $s->id }}" {{ request('sorteo_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           class="form-control"
                           placeholder="Buscar boleta o asociado"
                           style="min-width: 280px;">
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
                            <th>Número</th>
                            <th>Asociado</th>
                            <th>Documento</th>
                            <th>Ganadora</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($boletas as $boleta)
                            <tr>

                                @php
                                    $maxDigits = strlen((string) $boleta->sorteo->numero_fin);
                                @endphp

                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        {{ str_pad($boleta->numero_boleta, $maxDigits, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td>{{ $boleta->asociado?->nombre_completo ?? '—' }}</td>
                                <td>{{ $boleta->asociado?->documento ?? '—' }}</td>

                                <td>
                                    @if($boleta->ganadora)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Sí</span>
                                    @else
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">No</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <button
                                        class="btn btn-sm btn-outline-danger abrirPdf"
                                        data-url="{{ route('admin.boletas.pdf', $boleta) }}"
                                    >

                                        <i class="bi bi-file-earmark-pdf"></i>

                                    </button>
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
                    <p class="text-muted">
                        Aún no existen boletas asignadas para este sorteo.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL GENERAR BOLETAS -->
    <div class="modal fade" id="generarBoletasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title">Generar boletas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.boletas.generate') }}" method="POST" id="generarBoletasForm">
                    @csrf

                    <div class="modal-body" id="generarBoletasCampos">

                        <div class="mb-3">
                            <label class="form-label">Sorteo</label>

                            <select name="sorteo_id" class="form-select" required>
                                <option value="" disabled selected>Selecciona un sorteo</option>

                                @foreach($sorteos as $sorteo)
                                    <option value="{{ $sorteo->id }}">
                                        {{ $sorteo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info small">
                            <strong>Importante:</strong>
                            Cada vez que generes boletas, el sistema asignará únicamente números que aún no hayan sido utilizados.
                            Las boletas existentes no serán modificadas ni reemplazadas.
                        </div>

                        <!-- PROGRESO -->
                        <div id="generarBoletasProgreso" class="d-none text-center py-3">
                            <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;"></div>

                            <h5 class="fw-bold mb-2">Procesando generación</h5>
                            <p class="text-muted mb-4" id="textoProgresoBoletas">Iniciando...</p>

                            <div class="progress rounded-pill" style="height: 10px;">
                                <div id="barraProgresoBoletas"
                                     class="progress-bar progress-bar-striped progress-bar-animated"
                                     style="width: 5%;">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer" id="generarBoletasFooter">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnConfirmarGeneracion">
                            <i class="bi bi-magic me-1"></i> Generar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- MODAL CONFIRMACIÓN -->
    <div class="modal fade" id="confirmarGeneracionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirmar generación</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fw-semibold">
                        Estás a punto de generar las boletas del sorteo.
                    </p>

                    <p class="text-muted">
                        Se generarán nuevas boletas utilizando únicamente números disponibles.<br>
                        Las boletas ya asignadas permanecerán intactas.<br>
                        El sistema nunca reutilizará números marcados como usados.
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" id="btnConfirmarFinal">
                        Sí, generar boletas
                    </button>
                </div>

            </div>
        </div>
    </div>
<!-- MODAL PREVIEW PDF -->

<div
    class="modal fade"
    id="pdfPreviewModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Vista previa boleta
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body p-0">

                <iframe
                    id="pdfFrame"
                    style="
                        width:100%;
                        height:80vh;
                        border:none;
                    "
                ></iframe>

            </div>

            <div class="modal-footer">

                <a
                    id="btnDescargarPdf"
                    class="btn btn-danger"
                    target="_blank"
                >
                    Descargar PDF
                </a>

            </div>

        </div>

    </div>

</div>


<!-- LOADING GENERAR BOLETAS -->
<div id="loadingBoletasOverlay">

    <div class="loading-card">

        <div class="loading-spinner"></div>

        <h4>Generando boletas...</h4>

        <p>
            Estamos asignando números disponibles.
            Por favor espera unos segundos.
        </p>

    </div>

</div>

<style>
        #loadingBoletasOverlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.75);
            z-index:999999;
            align-items:center;
            justify-content:center;
            backdrop-filter:blur(4px);
        }

        .loading-card{
            background:#fff;
            padding:40px;
            border-radius:20px;
            text-align:center;
            min-width:350px;
            box-shadow:0 20px 50px rgba(0,0,0,.25);
            animation:zoomIn .3s ease;
        }

        .loading-card h4{
            margin-top:20px;
            margin-bottom:10px;
            font-weight:700;
        }

        .loading-card p{
            color:#6c757d;
            margin:0;
        }

        .loading-spinner{
            width:80px;
            height:80px;
            border:8px solid #e9ecef;
            border-top:8px solid #0d6efd;
            border-radius:50%;
            margin:auto;
            animation:spin 1s linear infinite;
        }

        @keyframes spin{
            100%{
                transform:rotate(360deg);
            }
        }

        @keyframes zoomIn{
            from{
                opacity:0;
                transform:scale(.8);
            }
            to{
                opacity:1;
                transform:scale(1);
            }
        }
</style>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('generarBoletasForm');
        const btnOpenConfirm = document.getElementById('btnConfirmarGeneracion');
        const btnFinal = document.getElementById('btnConfirmarFinal');

        const modalConfirm = new bootstrap.Modal(document.getElementById('confirmarGeneracionModal'));

        const campos = document.getElementById('generarBoletasCampos');
        const progreso = document.getElementById('generarBoletasProgreso');
        const footer = document.getElementById('generarBoletasFooter');

        const texto = document.getElementById('textoProgresoBoletas');
        const barra = document.getElementById('barraProgresoBoletas');

        // abrir confirmación
        btnOpenConfirm.addEventListener('click', function () {
            modalConfirm.show();
        });

        // confirmar final


        btnFinal.addEventListener('click', function () {

            modalConfirm.hide();

            document.getElementById('loadingBoletasOverlay').style.display = 'flex';

            setTimeout(() => {
                form.submit();
            }, 300);

        });




    });

    document
    .querySelectorAll('.abrirPdf')
    .forEach(btn => {

        btn.addEventListener('click', function(){

            const url =
                this.dataset.url;

            document
                .getElementById('pdfFrame')
                .src = url;

            document
                .getElementById(
                    'btnDescargarPdf'
                )
                .href = url;

            new bootstrap.Modal(
                document.getElementById(
                    'pdfPreviewModal'
                )
            ).show();

        });

    });
</script>
@endpush