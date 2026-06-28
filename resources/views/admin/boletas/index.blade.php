@extends('layouts.admin')

@php
    $title = 'Gestión de Boletas';
    $subtitle = 'Genera, consulta y administra las boletas del sistema.';

    $listaSorteos = $sorteos ?? collect();
    $topBoletas = $topAsociado ?? null;

    $sorteoActualId = request('sorteo_id', $sorteoId ?? null);

    if (!$sorteoActualId && $listaSorteos->count()) {
        $sorteoActualId = $listaSorteos->first()->id;
    }

    $sorteoSeleccionado = $listaSorteos->firstWhere('id', (int) $sorteoActualId);

    if (!$sorteoSeleccionado && $listaSorteos->count()) {
        $sorteoSeleccionado = $listaSorteos->first();
        $sorteoActualId = $sorteoSeleccionado->id;
    }

    $haySorteos = $listaSorteos->count() > 0;
@endphp

@section('topbar_actions')
    @if($haySorteos)
        <button
            class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#generarBoletasModal"
        >
            <i class="bi bi-magic me-1"></i>
            Generar boletas
        </button>
    @else
        <button class="btn btn-primary rounded-pill px-4" disabled>
            <i class="bi bi-magic me-1"></i>
            Generar boletas
        </button>
    @endif
@endsection

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>

{{-- PANEL PRINCIPAL --}}
<div class="boletas-hero card border-0 rounded-4 shadow-sm mb-4">

    <div class="card-body p-4">

        <div class="row g-4 align-items-center">

            <div class="col-lg-7">

                <div class="d-flex align-items-start gap-3">

                    <div class="boletas-hero-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>

                    <div>

                        <div class="text-muted small mb-1">
                            Sorteo seleccionado
                        </div>

                        @if($sorteoSeleccionado)
                            <h4 class="fw-bold mb-2">
                                {{ $sorteoSeleccionado->nombre }}
                            </h4>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $sorteoSeleccionado->fecha_sorteo ? $sorteoSeleccionado->fecha_sorteo->format('d/m/Y') : 'Sin fecha' }}
                                </span>

                                @if($sorteoSeleccionado->numero_inicio !== null && $sorteoSeleccionado->numero_fin !== null)
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        <i class="bi bi-hash me-1"></i>
                                        {{ $sorteoSeleccionado->numero_inicio }} - {{ $sorteoSeleccionado->numero_fin }}
                                    </span>
                                @endif

                                @if($sorteoSeleccionado->boletas_generadas)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Boletas generadas
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        Pendiente por generar
                                    </span>
                                @endif

                            </div>
                        @else
                            <h4 class="fw-bold mb-2">
                                No hay sorteos disponibles
                            </h4>

                            <p class="text-muted mb-0">
                                Primero crea un sorteo para poder generar boletas.
                            </p>
                        @endif

                    </div>

                </div>

            </div>

            <div class="col-lg-5">

                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="mini-stat">
                            <div class="mini-stat-label">
                                Total boletas
                            </div>

                            <div class="mini-stat-value">
                                {{ $boletas->total() }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mini-stat">
                            <div class="mini-stat-label">
                                Top asociado
                            </div>

                            @if($topBoletas && $topBoletas->asociado)
                                <div class="mini-stat-name">
                                    {{ $topBoletas->asociado->nombre_completo }}
                                </div>

                                <div class="mini-stat-sub">
                                    {{ $topBoletas->total_boletas }} boletas
                                </div>
                            @else
                                <div class="mini-stat-name">
                                    Sin datos
                                </div>

                                <div class="mini-stat-sub">
                                    Aún no generado
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- FILTROS --}}
<div class="content-card card mb-4">

    <div class="card-body p-3">

        <form
            method="GET"
            action="{{ route('admin.boletas.index') }}"
            class="row g-2 align-items-center"
        >

            <div class="col-lg-4 col-md-6">
                <label class="form-label small text-muted mb-1">
                    Ver boletas del sorteo
                </label>

                <select
                    name="sorteo_id"
                    class="form-select"
                    onchange="this.form.submit()"
                    {{ !$haySorteos ? 'disabled' : '' }}
                >
                    @foreach($listaSorteos as $s)
                        <option
                            value="{{ $s->id }}"
                            {{ (int) $sorteoActualId === (int) $s->id ? 'selected' : '' }}
                        >
                            {{ $s->nombre }}
                            @if($s->fecha_sorteo)
                                — {{ $s->fecha_sorteo->format('d/m/Y') }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 col-md-6">
                <label class="form-label small text-muted mb-1">
                    Buscar
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    class="form-control"
                    placeholder="Boleta, asociado o documento"
                >
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label small text-muted mb-1">
                    Registros
                </label>

                <select
                    name="per_page"
                    class="form-select"
                    onchange="this.form.submit()"
                >
                    <option value="10"  {{ (int)($perPage ?? 10) === 10  ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ (int)($perPage ?? 10) === 25  ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ (int)($perPage ?? 10) === 50  ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (int)($perPage ?? 10) === 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6 d-flex gap-2 align-items-end">
                <div class="w-100">
                    <label class="form-label small text-muted mb-1 d-block">
                        Acción
                    </label>

                    <button class="btn btn-outline-primary rounded-pill w-100" type="submit">
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>

{{-- LISTADO --}}
<div class="content-card card">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Listado de boletas
            </h5>

            <small class="text-muted">
                Consulta las boletas generadas para el sorteo seleccionado.
            </small>
        </div>

        @if($haySorteos)
            <button
                type="button"
                class="btn btn-primary rounded-pill px-4"
                data-bs-toggle="modal"
                data-bs-target="#generarBoletasModal"
            >
                <i class="bi bi-magic me-1"></i>
                Generar boletas
            </button>
        @endif

    </div>

    <div class="card-body">

        @if($boletas->count())

            <div class="table-responsive">

                <table class="table align-middle boletas-table">

                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Asociado</th>
                            <th>Documento</th>
                            <th>Sorteo</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($boletas as $boleta)

                        @php
                            $maxDigits = max(1, strlen((string) ($boleta->sorteo->numero_fin ?? $boleta->numero_boleta)));
                        @endphp

                        <tr>

                            <td>
                                <span class="ticket-number">
                                    {{ str_pad($boleta->numero_boleta, $maxDigits, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $boleta->asociado?->nombre_completo ?? '—' }}
                                </div>
                            </td>

                            <td>
                                {{ $boleta->asociado?->documento ?? '—' }}
                            </td>

                            <td>
                                <div class="fw-semibold small">
                                    {{ $boleta->sorteo?->nombre ?? '—' }}
                                </div>

                                <div class="text-muted small">
                                    {{ $boleta->sorteo?->fecha_sorteo ? $boleta->sorteo->fecha_sorteo->format('d/m/Y') : 'Sin fecha' }}
                                </div>
                            </td>

                            <td>
                                @if($boleta->ganadora)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="bi bi-trophy me-1"></i>
                                        Ganadora
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        No ganadora
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">
                                <button
                                    class="btn btn-sm btn-outline-danger rounded-pill abrirPdf"
                                    data-url="{{ route('admin.boletas.pdf', $boleta) }}"
                                    title="Ver PDF"
                                >
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    PDF
                                </button>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">

                <div class="text-muted small">
                    Mostrando
                    {{ $boletas->firstItem() }}
                    a
                    {{ $boletas->lastItem() }}
                    de
                    {{ $boletas->total() }}
                    registros
                </div>

                <div>
                    {{ $boletas->links() }}
                </div>

            </div>

        @else

            <div class="empty-state text-center py-5">

                <div class="empty-icon mx-auto mb-3">
                    <i class="bi bi-ticket-perforated"></i>
                </div>

                <h5 class="fw-bold">
                    No hay boletas generadas
                </h5>

                @if($sorteoSeleccionado)
                    <p class="text-muted mb-4">
                        Aún no existen boletas asignadas para el sorteo
                        <strong>{{ $sorteoSeleccionado->nombre }}</strong>.
                    </p>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#generarBoletasModal"
                    >
                        <i class="bi bi-magic me-1"></i>
                        Generar boletas de este sorteo
                    </button>
                @else
                    <p class="text-muted">
                        No hay sorteos disponibles para generar boletas.
                    </p>
                @endif

            </div>

        @endif

    </div>

</div>

{{-- MODAL GENERAR BOLETAS --}}
<div class="modal fade" id="generarBoletasModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">

            <div class="modal-header modal-header-admin border-0">

                <div class="d-flex align-items-center gap-3">

                    <div class="modal-title-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            Generar boletas
                        </h5>

                        <div class="small modal-subtitle">
                            Selecciona el sorteo destino y confirma la generación.
                        </div>
                    </div>

                </div>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

            </div>

            <form action="{{ route('admin.boletas.generate') }}" method="POST" id="generarBoletasForm">
                @csrf

                <div class="modal-body p-4">

                    <div id="generarBoletasContenido">

                        <div class="row g-4">

                            <div class="col-lg-7">

                                <div class="step-label mb-3">
                                    <span>1</span>
                                    Selecciona el sorteo
                                </div>

                                <label class="form-label fw-semibold">
                                    Sorteo destino
                                </label>

                                <div class="sorteo-select-wrapper">

                                    <div class="sorteo-select-icon">
                                        <i class="bi bi-calendar2-event"></i>
                                    </div>

                                    <select
                                        name="sorteo_id"
                                        id="selectSorteoGenerar"
                                        class="form-select sorteo-select"
                                        required
                                    >
                                        <option value="" disabled {{ !$sorteoSeleccionado ? 'selected' : '' }}>
                                            Selecciona un sorteo
                                        </option>

                                        @foreach($listaSorteos as $sorteo)
                                            <option
                                                value="{{ $sorteo->id }}"
                                                data-nombre="{{ $sorteo->nombre }}"
                                                data-fecha="{{ $sorteo->fecha_sorteo ? $sorteo->fecha_sorteo->format('d/m/Y') : 'Sin fecha definida' }}"
                                                data-estado="{{ $sorteo->estado ?? 'Sin estado' }}"
                                                data-generadas="{{ $sorteo->boletas_generadas ? '1' : '0' }}"
                                                data-inicio="{{ $sorteo->numero_inicio }}"
                                                data-fin="{{ $sorteo->numero_fin }}"
                                                {{ (int) $sorteoActualId === (int) $sorteo->id ? 'selected' : '' }}
                                            >
                                                {{ $sorteo->nombre }}
                                                @if($sorteo->fecha_sorteo)
                                                    — {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>

                                    <div class="sorteo-select-arrow">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>

                                </div>

                                <div class="sorteo-select-help mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Las boletas se generarán únicamente para el sorteo seleccionado.
                                </div>

                                <div class="alert alert-primary rounded-4 border-0 mt-4 mb-0">

                                    <div class="d-flex gap-3">

                                        <div class="fs-4">
                                            <i class="bi bi-info-circle"></i>
                                        </div>

                                        <div>
                                            <div class="fw-bold mb-1">
                                                ¿Qué hará el sistema?
                                            </div>

                                            <div class="small">
                                                Generará las boletas faltantes según la cantidad configurada para cada participante en este sorteo.
                                                No reemplazará boletas existentes y no reutilizará números marcados como usados.
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="alert alert-warning rounded-4 border-0 mt-3 mb-0 d-none" id="alertaYaGeneradas">

                                    <div class="d-flex gap-3">

                                        <div class="fs-4">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </div>

                                        <div>
                                            <div class="fw-bold mb-1">
                                                Este sorteo ya tiene boletas generadas
                                            </div>

                                            <div class="small">
                                                Si agregaste nuevos participantes o aumentaste la cantidad de boletas,
                                                el sistema solo generará las faltantes.
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-5">

                                <div class="step-label mb-3">
                                    <span>2</span>
                                    Verifica el resumen
                                </div>

                                <div class="generation-preview-card">

                                    <div class="generation-preview-icon">
                                        <i class="bi bi-ticket-detailed"></i>
                                    </div>

                                    <h5 class="fw-bold mb-1" id="previewSorteoNombre">
                                        —
                                    </h5>

                                    <div class="text-muted small mb-3" id="previewSorteoFecha">
                                        —
                                    </div>

                                    <div class="d-grid gap-2">

                                        <div class="preview-row">
                                            <span>Rango de números</span>
                                            <strong id="previewSorteoRango">—</strong>
                                        </div>

                                        <div class="preview-row">
                                            <span>Estado</span>
                                            <strong id="previewSorteoEstadoTexto">—</strong>
                                        </div>

                                    </div>

                                    <div class="mt-3">
                                        <span class="badge rounded-pill px-3 py-2" id="previewSorteoBadge">
                                            —
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- PROGRESO --}}
                    <div id="generarBoletasProgreso" class="d-none text-center py-5">

                        <div class="spinner-border text-primary mb-4" style="width: 4rem; height: 4rem;"></div>

                        <h5 class="fw-bold mb-2">
                            Generando boletas
                        </h5>

                        <p class="text-muted mb-4" id="textoProgresoBoletas">
                            Preparando asignación...
                        </p>

                        <div class="progress rounded-pill mx-auto" style="height: 12px; max-width: 420px;">
                            <div
                                id="barraProgresoBoletas"
                                class="progress-bar progress-bar-striped progress-bar-animated"
                                style="width: 15%;"
                            ></div>
                        </div>

                        <div class="text-muted small mt-3">
                            No cierres esta ventana mientras se procesa.
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4" id="generarBoletasFooter">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        id="btnConfirmarGeneracion"
                    >
                        Continuar
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- MODAL CONFIRMACIÓN --}}
<div class="modal fade" id="confirmarGeneracionModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">

                <div>
                    <h5 class="modal-title fw-bold text-danger">
                        Confirmar generación
                    </h5>

                    <div class="text-muted small">
                        Revisa el sorteo antes de continuar.
                    </div>
                </div>

                <button class="btn-close" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <div class="confirm-box mb-3">

                    <div class="text-muted small mb-1">
                        Sorteo seleccionado
                    </div>

                    <div class="fw-bold fs-5" id="confirmSorteoNombre">
                        —
                    </div>

                    <div class="text-muted small" id="confirmSorteoFecha">
                        —
                    </div>

                </div>

                <div class="alert alert-danger rounded-4 border-0 small">
                    <strong>Importante:</strong>
                    esta acción generará boletas para el sorteo seleccionado.
                    No se eliminarán ni reemplazarán boletas ya existentes.
                </div>

                <div class="form-check mt-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        id="confirmarCheckGeneracion"
                    >

                    <label class="form-check-label" for="confirmarCheckGeneracion">
                        Confirmo que seleccioné el sorteo correcto.
                    </label>
                </div>

            </div>

            <div class="modal-footer border-0">

                <button
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    class="btn btn-danger rounded-pill px-4"
                    id="btnConfirmarFinal"
                    disabled
                >
                    <i class="bi bi-magic me-1"></i>
                    Sí, generar
                </button>

            </div>

        </div>

    </div>

</div>

{{-- MODAL PREVIEW PDF --}}
<div class="modal fade" id="pdfPreviewModal" tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content rounded-4 border-0 shadow-lg">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
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
                    style="width:100%; height:80vh; border:none;"
                ></iframe>

            </div>

            <div class="modal-footer">

                <a
                    id="btnDescargarPdf"
                    class="btn btn-danger rounded-pill px-4"
                    target="_blank"
                >
                    <i class="bi bi-download me-1"></i>
                    Descargar PDF
                </a>

            </div>

        </div>

    </div>

</div>

{{-- OVERLAY --}}
<div id="loadingBoletasOverlay">

    <div class="loading-card">

        <div class="loading-spinner"></div>

        <h4>
            Generando boletas...
        </h4>

        <p>
            Estamos asignando números disponibles.
            Por favor espera unos segundos.
        </p>

    </div>

</div>

<style>
    .boletas-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .boletas-hero-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: #0d6efd;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex: 0 0 auto;
        box-shadow: 0 12px 30px rgba(13, 110, 253, .25);
    }

    .mini-stat {
        background: #fff;
        border: 1px solid #eef1f5;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
    }

    .mini-stat-label {
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .mini-stat-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    .mini-stat-name {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.2;
    }

    .mini-stat-sub {
        color: #6c757d;
        font-size: 13px;
        margin-top: 4px;
    }

    .boletas-table tbody tr {
        transition: background .15s ease;
    }

    .boletas-table tbody tr:hover {
        background: #f8fbff;
    }

    .ticket-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eef4ff;
        color: #0d6efd;
        font-weight: 800;
        letter-spacing: .5px;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        border-radius: 24px;
        background: #f1f3f5;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .modal-header-admin {
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 32%),
            linear-gradient(135deg, #1d4ed8 0%, #2563eb 48%, #111827 100%);
        color: #fff;
        padding: 26px 28px;
    }

    .modal-title-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 12px 30px rgba(0,0,0,.18);
    }

    .modal-subtitle {
        color: rgba(255,255,255,.78);
    }

    .step-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #111827;
    }

    .step-label span {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .sorteo-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        transition: all .18s ease;
        overflow: hidden;
    }

    .sorteo-select-wrapper:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .10);
    }

    .sorteo-select-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .sorteo-select-icon {
        width: 56px;
        height: 56px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .sorteo-select {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 1rem 3.2rem 1rem 1rem !important;
        font-weight: 700;
        color: #111827;
        background-color: transparent;
        min-height: 56px;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .sorteo-select:focus {
        box-shadow: none !important;
    }

    .sorteo-select-arrow {
        position: absolute;
        right: 18px;
        color: #6b7280;
        pointer-events: none;
        font-size: 16px;
    }

    .sorteo-select-help {
        color: #6b7280;
        font-size: .86rem;
    }

    .sorteo-select.is-invalid {
        border: 0 !important;
        background-image: none !important;
    }

    .sorteo-select-wrapper.has-error {
        border-color: #dc3545;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, .12);
    }

    .generation-preview-card {
        border: 1px solid #e9ecef;
        border-radius: 22px;
        padding: 22px;
        background: #fff;
        min-height: 100%;
    }

    .generation-preview-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        margin-bottom: 16px;
    }

    .preview-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px dashed #e9ecef;
        font-size: 13px;
    }

    .preview-row span {
        color: #6c757d;
    }

    .preview-row strong {
        text-align: right;
    }

    .confirm-box {
        background: #f8f9fa;
        border-radius: 18px;
        padding: 18px;
        border: 1px solid #e9ecef;
    }

    #loadingBoletasOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.75);
        z-index: 999999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .loading-card {
        background: #fff;
        padding: 42px;
        border-radius: 26px;
        text-align: center;
        min-width: 360px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        animation: zoomIn .3s ease;
    }

    .loading-card h4 {
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: 800;
    }

    .loading-card p {
        color: #6c757d;
        margin: 0;
    }

    .loading-spinner {
        width: 82px;
        height: 82px;
        border: 8px solid #e9ecef;
        border-top: 8px solid #0d6efd;
        border-radius: 50%;
        margin: auto;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(.86);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('generarBoletasForm');
        const selectSorteo = document.getElementById('selectSorteoGenerar');

        const btnOpenConfirm = document.getElementById('btnConfirmarGeneracion');
        const btnFinal = document.getElementById('btnConfirmarFinal');
        const checkConfirm = document.getElementById('confirmarCheckGeneracion');

        const generarModalElement = document.getElementById('generarBoletasModal');
        const confirmarModalElement = document.getElementById('confirmarGeneracionModal');

        const modalConfirm = new bootstrap.Modal(confirmarModalElement);

        const contenido = document.getElementById('generarBoletasContenido');
        const progreso = document.getElementById('generarBoletasProgreso');
        const footer = document.getElementById('generarBoletasFooter');

        const texto = document.getElementById('textoProgresoBoletas');
        const barra = document.getElementById('barraProgresoBoletas');

        const alertaYaGeneradas = document.getElementById('alertaYaGeneradas');

        const previewSorteoNombre = document.getElementById('previewSorteoNombre');
        const previewSorteoFecha = document.getElementById('previewSorteoFecha');
        const previewSorteoRango = document.getElementById('previewSorteoRango');
        const previewSorteoEstadoTexto = document.getElementById('previewSorteoEstadoTexto');
        const previewSorteoBadge = document.getElementById('previewSorteoBadge');

        const confirmSorteoNombre = document.getElementById('confirmSorteoNombre');
        const confirmSorteoFecha = document.getElementById('confirmSorteoFecha');

        function getSelectedOption() {
            if (!selectSorteo) {
                return null;
            }

            return selectSorteo.options[selectSorteo.selectedIndex] || null;
        }

        function markSelectError() {
            if (!selectSorteo) {
                return;
            }

            selectSorteo.classList.add('is-invalid');
            selectSorteo.closest('.sorteo-select-wrapper')?.classList.add('has-error');
        }

        function clearSelectError() {
            if (!selectSorteo) {
                return;
            }

            selectSorteo.classList.remove('is-invalid');
            selectSorteo.closest('.sorteo-select-wrapper')?.classList.remove('has-error');
        }

        function resetGenerationModal() {
            if (contenido) {
                contenido.classList.remove('d-none');
            }

            if (footer) {
                footer.classList.remove('d-none');
            }

            if (progreso) {
                progreso.classList.add('d-none');
            }

            if (texto) {
                texto.textContent = 'Preparando asignación...';
            }

            if (barra) {
                barra.style.width = '15%';
            }

            if (checkConfirm) {
                checkConfirm.checked = false;
            }

            if (btnFinal) {
                btnFinal.disabled = true;
            }

            clearSelectError();

            const overlay = document.getElementById('loadingBoletasOverlay');

            if (overlay) {
                overlay.style.display = 'none';
            }
        }

        function updateSorteoPreview() {
            const option = getSelectedOption();

            if (!option || !option.value) {
                previewSorteoNombre.textContent = 'Selecciona un sorteo';
                previewSorteoFecha.textContent = '—';
                previewSorteoRango.textContent = '—';
                previewSorteoEstadoTexto.textContent = '—';
                previewSorteoBadge.textContent = 'Sin selección';
                previewSorteoBadge.className = 'badge bg-light text-dark rounded-pill px-3 py-2';

                confirmSorteoNombre.textContent = '—';
                confirmSorteoFecha.textContent = '—';

                if (alertaYaGeneradas) {
                    alertaYaGeneradas.classList.add('d-none');
                }

                return;
            }

            const nombre = option.dataset.nombre || option.textContent.trim();
            const fecha = option.dataset.fecha || 'Sin fecha definida';
            const generadas = option.dataset.generadas === '1';
            const inicio = option.dataset.inicio || '';
            const fin = option.dataset.fin || '';

            previewSorteoNombre.textContent = nombre;
            previewSorteoFecha.textContent = 'Fecha: ' + fecha;

            if (inicio && fin) {
                previewSorteoRango.textContent = inicio + ' - ' + fin;
            } else {
                previewSorteoRango.textContent = 'No definido';
            }

            if (generadas) {
                previewSorteoEstadoTexto.textContent = 'Ya tiene boletas';
                previewSorteoBadge.textContent = 'Generará solo faltantes';
                previewSorteoBadge.className = 'badge bg-success-subtle text-success rounded-pill px-3 py-2';

                if (alertaYaGeneradas) {
                    alertaYaGeneradas.classList.remove('d-none');
                }
            } else {
                previewSorteoEstadoTexto.textContent = 'Pendiente';
                previewSorteoBadge.textContent = 'Listo para generar';
                previewSorteoBadge.className = 'badge bg-warning-subtle text-warning rounded-pill px-3 py-2';

                if (alertaYaGeneradas) {
                    alertaYaGeneradas.classList.add('d-none');
                }
            }

            confirmSorteoNombre.textContent = nombre;
            confirmSorteoFecha.textContent = 'Fecha: ' + fecha;
        }

        if (generarModalElement) {
            generarModalElement.addEventListener('show.bs.modal', function () {
                resetGenerationModal();
                updateSorteoPreview();
            });
        }

        if (selectSorteo) {
            selectSorteo.addEventListener('change', function () {
                clearSelectError();
                updateSorteoPreview();
            });

            updateSorteoPreview();
        }

        if (checkConfirm) {
            checkConfirm.addEventListener('change', function () {
                btnFinal.disabled = !checkConfirm.checked;
            });
        }

        if (btnOpenConfirm) {
            btnOpenConfirm.addEventListener('click', function () {
                const option = getSelectedOption();

                if (!option || !option.value) {
                    markSelectError();
                    return;
                }

                clearSelectError();

                if (checkConfirm) {
                    checkConfirm.checked = false;
                }

                if (btnFinal) {
                    btnFinal.disabled = true;
                }

                updateSorteoPreview();
                modalConfirm.show();
            });
        }

        if (btnFinal) {
            btnFinal.addEventListener('click', function () {

                if (checkConfirm && !checkConfirm.checked) {
                    return;
                }

                modalConfirm.hide();

                if (contenido) {
                    contenido.classList.add('d-none');
                }

                if (footer) {
                    footer.classList.add('d-none');
                }

                if (progreso) {
                    progreso.classList.remove('d-none');
                }

                if (texto) {
                    texto.textContent = 'Validando participantes del sorteo...';
                }

                if (barra) {
                    barra.style.width = '35%';
                }

                setTimeout(() => {
                    if (texto) {
                        texto.textContent = 'Buscando números disponibles...';
                    }

                    if (barra) {
                        barra.style.width = '58%';
                    }
                }, 400);

                setTimeout(() => {
                    if (texto) {
                        texto.textContent = 'Asignando boletas faltantes...';
                    }

                    if (barra) {
                        barra.style.width = '82%';
                    }
                }, 800);

                setTimeout(() => {
                    if (texto) {
                        texto.textContent = 'Finalizando generación...';
                    }

                    if (barra) {
                        barra.style.width = '96%';
                    }

                    document.getElementById('loadingBoletasOverlay').style.display = 'flex';
                }, 1100);

                setTimeout(() => {
                    form.submit();
                }, 1400);
            });
        }

        document
            .querySelectorAll('.abrirPdf')
            .forEach(btn => {
                btn.addEventListener('click', function () {

                    const url = this.dataset.url;

                    document.getElementById('pdfFrame').src = url;
                    document.getElementById('btnDescargarPdf').href = url;

                    new bootstrap.Modal(
                        document.getElementById('pdfPreviewModal')
                    ).show();

                });
            });

    });
</script>
@endpush