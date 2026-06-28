@extends('layouts.admin')

@php
    $title = 'Gestión de Ganadores';
    $subtitle = 'Asigna boletas ganadoras a los premios de cada sorteo.';

    $listaSorteos = $sorteos ?? collect();

    $sorteoActualId = request('sorteo_id', $sorteoSeleccionado?->id ?? null);

    $premiosCollection = collect($premios ?? []);
    $boletasCollection = collect($boletas ?? []);

    $totalPremios = $premiosCollection->count();
    $premiosAsignados = $premiosCollection->filter(fn ($premio) => filled($premio->boleta_ganadora_id))->count();
    $premiosPendientes = max(0, $totalPremios - $premiosAsignados);
    $totalBoletas = $boletasCollection->count();

    $porcentajeAsignacion = $totalPremios > 0
        ? round(($premiosAsignados / $totalPremios) * 100)
        : 0;
@endphp

@section('topbar_actions')
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.sorteos.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-calendar2-event me-1"></i>
            Sorteos
        </a>

        <a href="{{ route('admin.premios.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>
            Nuevo premio
        </a>
    </div>
@endsection

@section('content')


{{-- SELECTOR DE SORTEO --}}
<div class="content-card card mb-4">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Seleccionar sorteo
            </h5>

            <small class="text-muted">
                Elige el sorteo al que deseas registrar resultado y asignar ganadores.
            </small>
        </div>

    </div>

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.ganadores.index') }}"
            class="row g-3 align-items-end"
        >

            <div class="col-lg-8">

                <label class="form-label small text-muted mb-1">
                    Sorteo
                </label>

                <div class="ganador-select-wrapper">

                    <div class="ganador-select-icon">
                        <i class="bi bi-calendar2-event"></i>
                    </div>

                    <select
                        name="sorteo_id"
                        class="form-select ganador-select"
                        required
                    >
                        <option value="">
                            Selecciona un sorteo
                        </option>

                        @foreach($listaSorteos as $sorteo)
                            <option
                                value="{{ $sorteo->id }}"
                                {{ (string) $sorteoActualId === (string) $sorteo->id ? 'selected' : '' }}
                            >
                                {{ $sorteo->nombre }}
                                @if($sorteo->fecha_sorteo)
                                    — {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <div class="ganador-select-arrow">
                        <i class="bi bi-chevron-down"></i>
                    </div>

                </div>

            </div>

            <div class="col-lg-4">
                <button class="btn btn-primary rounded-pill w-100">
                    <i class="bi bi-search me-1"></i>
                    Consultar sorteo
                </button>
            </div>

        </form>

    </div>

</div>

@if($sorteoSeleccionado)

    {{-- RESUMEN --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <div class="content-card card h-100">

                <div class="card-header">

                    <h5 class="mb-1 fw-bold">
                        Sorteo seleccionado
                    </h5>

                    <small class="text-muted">
                        Información general del sorteo.
                    </small>

                </div>

                <div class="card-body">

                    <div class="info-row">
                        <span>Nombre</span>
                        <strong>{{ $sorteoSeleccionado->nombre }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Fecha</span>
                        <strong>
                            {{ $sorteoSeleccionado->fecha_sorteo ? $sorteoSeleccionado->fecha_sorteo->format('d/m/Y') : '—' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Estado</span>
                        <strong>{{ ucfirst($sorteoSeleccionado->estado ?? 'Sin estado') }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Total boletas</span>
                        <strong>{{ number_format($totalBoletas) }}</strong>
                    </div>

                    <div class="mt-4">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">
                                Avance asignación
                            </span>

                            <strong class="small">
                                {{ $porcentajeAsignacion }}%
                            </strong>
                        </div>

                        <div class="progress rounded-pill" style="height: 10px;">
                            <div
                                class="progress-bar"
                                style="width: {{ $porcentajeAsignacion }}%;"
                            ></div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="content-card card h-100">

                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h5 class="mb-1 fw-bold">
                            Resumen de premios
                        </h5>

                        <small class="text-muted">
                            Cada premio puede tener una boleta ganadora distinta.
                        </small>
                    </div>

                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                        {{ $premiosAsignados }} / {{ $totalPremios }} asignados
                    </span>

                </div>

                <div class="card-body">

                    @if($premiosCollection->count())

                        <div class="row g-3">

                            @foreach($premiosCollection as $premio)

                                <div class="col-md-6">

                                    <div class="premio-summary-card">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="premio-summary-icon">
                                                <i class="bi bi-gift"></i>
                                            </div>

                                            <div style="min-width: 0;">

                                                <div class="fw-semibold text-truncate">
                                                    {{ $premio->titulo }}
                                                </div>

                                                <div class="text-muted small mb-2">
                                                    Orden: {{ $premio->orden }}
                                                </div>

                                                @if($premio->boletaGanadora)
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-2">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Boleta {{ $premio->boletaGanadora->numero_boleta }}
                                                    </span>

                                                    <div class="small text-muted text-truncate">
                                                        {{ $premio->boletaGanadora->asociado?->nombre_completo ?? '—' }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                        <i class="bi bi-hourglass-split me-1"></i>
                                                        Sin asignar
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="empty-state text-center py-4">
                            <div class="empty-icon mx-auto mb-3">
                                <i class="bi bi-gift"></i>
                            </div>

                            <h6 class="fw-bold">
                                No hay premios creados para este sorteo
                            </h6>

                            <p class="text-muted mb-0">
                                Primero crea los premios del sorteo.
                            </p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- RESULTADO LOTERÍA --}}
    <div class="content-card card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="fw-bold mb-1">
                    Resultado de lotería
                </h5>

                <small class="text-muted">
                    Registra el número ganador y adjunta evidencia del resultado.
                </small>
            </div>

            @if($sorteoSeleccionado?->numero_resultado)
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i>
                    Resultado registrado
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="bi bi-hourglass-split me-1"></i>
                    Pendiente
                </span>
            @endif

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.ganadores.guardar') }}"
                enctype="multipart/form-data"
            >

                @csrf

                <input
                    type="hidden"
                    name="sorteo_id"
                    value="{{ $sorteoSeleccionado->id ?? '' }}"
                >

                <div class="row g-4">

                    <div class="col-lg-4">

                        <label class="form-label">
                            Número ganador
                        </label>

                        <div class="winner-input-wrapper">
                            <div class="winner-input-icon">
                                <i class="bi bi-hash"></i>
                            </div>

                            <input
                                type="text"
                                name="numero_resultado"
                                id="numeroGanador"
                                class="form-control winner-input"
                                placeholder="Ej: 0123"
                                value="{{ $sorteoSeleccionado?->numero_resultado }}"
                            >
                        </div>

                        <div class="form-text">
                            Escribe el número ganador publicado por la lotería.
                        </div>

                    </div>

                    <div class="col-lg-4">


                        <div id="resultadoAsociado" class="winner-preview-box">
                            <span class="text-muted">
                                Ingresa un número para consultar.
                            </span>
                        </div>

                    </div>

                    <div class="col-lg-4 d-flex align-items-end">

                        <button class="btn btn-primary rounded-pill w-100">
                            <i class="bi bi-save me-1"></i>
                            Guardar resultado
                        </button>

                    </div>

                </div>

                @if(!$sorteoSeleccionado?->soporte_resultado)

                    <div class="mt-4">

                        <label class="form-label">
                            Soporte del resultado
                        </label>

                        <input
                            type="file"
                            name="soporte_resultado"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <div class="form-text">
                            Puedes subir imagen o PDF como evidencia.
                        </div>

                    </div>

                @endif

            </form>

            @if($sorteoSeleccionado?->soporte_resultado)

                @php
                    $file = $sorteoSeleccionado->soporte_resultado;
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $url = asset('storage/' . $file);
                @endphp

                <hr class="my-4">

                <div>
                    <div class="text-muted small mb-2">
                        Soporte del resultado actual
                    </div>

                    @if(in_array($ext, ['jpg','jpeg','png','webp']))

                        <div class="soporte-preview-image-wrapper">
                            <img
                                src="{{ $url }}"
                                class="soporte-preview-image"
                                onclick="openSoporteModal('{{ $url }}', 'image')"
                                alt="Soporte resultado"
                            >
                        </div>

                    @elseif($ext === 'pdf')

                        <div class="soporte-pdf-box">
                            <iframe src="{{ $url }}" width="100%" height="100%"></iframe>
                        </div>

                        <a
                            href="{{ $url }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary rounded-pill mt-2"
                        >
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Abrir PDF
                        </a>

                    @else

                        <a
                            href="{{ $url }}"
                            target="_blank"
                            class="btn btn-outline-secondary btn-sm rounded-pill"
                        >
                            <i class="bi bi-download me-1"></i>
                            Descargar archivo
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

    {{-- ASIGNACIÓN DE PREMIOS --}}
    <div class="content-card card">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="mb-1 fw-bold">
                    Asignación de premios a boletas
                </h5>

                <small class="text-muted">
                    Selecciona la boleta ganadora de cada premio.
                </small>
            </div>

            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                <i class="bi bi-trophy me-1"></i>
                Ganadores
            </span>

        </div>

        <div class="card-body">

            @if($premiosCollection->count() && $boletasCollection->count())

                <div class="row g-4">

                    @foreach($premiosCollection as $premio)

                        @php
                            $boletaActual = $premio->boletaGanadora ?? null;
                        @endphp

                        <div class="col-12">

                            <div class="winner-prize-card">

                                <div class="row g-4 align-items-start">

                                    <div class="col-lg-4">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="winner-prize-icon">
                                                <i class="bi bi-gift"></i>
                                            </div>

                                            <div style="min-width: 0;">

                                                <div class="fw-bold fs-5">
                                                    {{ $premio->titulo }}
                                                </div>

                                                <div class="text-muted small mb-2">
                                                    Orden: {{ $premio->orden }}
                                                </div>

                                                <div class="text-muted">
                                                    {{ $premio->descripcion ?: 'Sin descripción.' }}
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-5">

                                        <form
                                            action="{{ route('admin.ganadores.asignarPremio') }}"
                                            method="POST"
                                            class="winner-form"
                                        >

                                            @csrf

                                            <input
                                                type="hidden"
                                                name="premio_id"
                                                value="{{ $premio->id }}"
                                            >

                                            <label class="form-label">
                                                Boleta ganadora
                                            </label>

                                            <div class="winner-select-wrapper">

                                                <div class="winner-select-icon">
                                                    <i class="bi bi-ticket-perforated"></i>
                                                </div>

                                                <select
                                                    name="boleta_id"
                                                    class="form-select winner-select"
                                                    required
                                                >
                                                    <option value="">
                                                        Selecciona una boleta
                                                    </option>

                                                    @foreach($boletasCollection as $boleta)
                                                        <option
                                                            value="{{ $boleta->id }}"
                                                            {{ (int) old('boleta_id', $premio->boleta_ganadora_id) === (int) $boleta->id ? 'selected' : '' }}
                                                        >
                                                            {{ $boleta->numero_boleta }}
                                                            —
                                                            {{ $boleta->asociado?->nombre_completo ?? '—' }}
                                                            —
                                                            {{ $boleta->asociado?->documento ?? '—' }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="winner-select-arrow">
                                                    <i class="bi bi-chevron-down"></i>
                                                </div>

                                            </div>

                                            <button
                                                class="btn btn-success rounded-pill w-100 mt-3"
                                                type="submit"
                                            >
                                                <i class="bi bi-check2-circle me-1"></i>
                                                Guardar asignación
                                            </button>

                                        </form>

                                    </div>

                                    <div class="col-lg-3">

                                        <div class="current-winner-box">

                                            <div class="text-muted small mb-2">
                                                Asignación actual
                                            </div>

                                            @if($boletaActual)

                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-2">
                                                    Boleta {{ $boletaActual->numero_boleta }}
                                                </span>

                                                <div class="fw-semibold">
                                                    {{ $boletaActual->asociado?->nombre_completo ?? '—' }}
                                                </div>

                                                <div class="text-muted small mb-3">
                                                    {{ $boletaActual->asociado?->documento ?? '—' }}
                                                </div>

                                                <form
                                                    action="{{ route('admin.ganadores.limpiarPremio', $premio) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Seguro que deseas quitar la boleta asignada a este premio?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-outline-danger rounded-pill w-100" type="submit">
                                                        <i class="bi bi-eraser me-1"></i>
                                                        Limpiar premio
                                                    </button>
                                                </form>

                                            @else

                                                <div class="text-muted">
                                                    Sin boleta asignada.
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @elseif(!$premiosCollection->count())

                <div class="empty-state text-center py-5">

                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-gift"></i>
                    </div>

                    <h5 class="fw-bold">
                        No hay premios para este sorteo
                    </h5>

                    <p class="text-muted mb-0">
                        Primero debes crear los premios.
                    </p>

                </div>

            @else

                <div class="empty-state text-center py-5">

                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>

                    <h5 class="fw-bold">
                        No hay boletas generadas
                    </h5>

                    <p class="text-muted mb-0">
                        Primero debes generar boletas para este sorteo.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endif

{{-- MODAL SOPORTE --}}
<div class="modal fade" id="soporteModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">

            <div class="modal-header soporte-modal-header">

                <div>
                    <h5 class="modal-title fw-bold mb-1">
                        Visualización de soporte
                    </h5>

                    <div class="small opacity-75">
                        Evidencia del resultado de lotería.
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body text-center soporte-modal-body">

                <img
                    id="soporteModalImg"
                    class="img-fluid rounded-4 d-none"
                    style="max-height: 80vh;"
                    alt="Soporte resultado"
                >

                <iframe
                    id="soporteModalPdf"
                    class="w-100 d-none rounded-4"
                    style="height:80vh;"
                    frameborder="0"
                ></iframe>

            </div>

        </div>

    </div>

</div>

<style>
    .ganadores-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .ganadores-hero-icon {
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

    .mini-stat-sub {
        color: #6c757d;
        font-size: 13px;
        margin-top: 4px;
    }

    .ganador-select-wrapper,
    .winner-input-wrapper,
    .winner-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        transition: all .18s ease;
        overflow: hidden;
    }

    .ganador-select-wrapper:hover,
    .winner-input-wrapper:hover,
    .winner-select-wrapper:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .09);
    }

    .ganador-select-wrapper:focus-within,
    .winner-input-wrapper:focus-within,
    .winner-select-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .ganador-select-icon,
    .winner-input-icon,
    .winner-select-icon {
        width: 54px;
        height: 54px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .ganador-select,
    .winner-input,
    .winner-select {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: .95rem 3rem .95rem 1rem !important;
        font-weight: 700;
        color: #111827;
        background-color: transparent;
        min-height: 54px;
    }

    .ganador-select,
    .winner-select {
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .ganador-select:focus,
    .winner-input:focus,
    .winner-select:focus {
        box-shadow: none !important;
    }

    .ganador-select-arrow,
    .winner-select-arrow {
        position: absolute;
        right: 18px;
        color: #6b7280;
        pointer-events: none;
        font-size: 15px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-row span {
        color: #6c757d;
    }

    .info-row strong {
        text-align: right;
    }

    .premio-summary-card,
    .winner-prize-card {
        border: 1px solid #e9ecef;
        border-radius: 22px;
        padding: 18px;
        background: #fff;
        height: 100%;
        transition: background .15s ease, box-shadow .15s ease, transform .15s ease;
    }

    .premio-summary-card:hover,
    .winner-prize-card:hover {
        background: #f8fbff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
    }

    .premio-summary-icon,
    .winner-prize-icon {
        width: 44px;
        height: 44px;
        border-radius: 15px;
        background: #eef4ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex: 0 0 auto;
    }

    .winner-preview-box {
        min-height: 54px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #f8fafc;
        padding: 12px 16px;
        display: flex;
        align-items: center;
    }

    .soporte-preview-image-wrapper {
        width: fit-content;
        max-width: 100%;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .10);
    }

    .soporte-preview-image {
        max-height: 280px;
        max-width: 100%;
        cursor: pointer;
        display: block;
    }

    .soporte-pdf-box {
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        overflow: hidden;
        height: 300px;
        box-shadow: 0 14px 32px rgba(15, 23, 42, .08);
    }

    .current-winner-box {
        border: 1px solid #e9ecef;
        background: #f8fafc;
        border-radius: 22px;
        padding: 18px;
        height: 100%;
    }

    .soporte-modal-header {
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 32%),
            linear-gradient(135deg, #1d4ed8 0%, #2563eb 48%, #111827 100%);
        color: #fff;
        border: 0;
        padding: 22px 24px;
    }

    .soporte-modal-body {
        background: #0f172a;
        min-height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    @media (max-width: 768px) {
        .ganadores-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .winner-prize-card,
        .premio-summary-card {
            padding: 16px;
        }
    }
</style>

@endsection

@push('scripts')
<script>
    function openSoporteModal(url, type) {
        const img = document.getElementById('soporteModalImg');
        const pdf = document.getElementById('soporteModalPdf');

        if (!img || !pdf) {
            return;
        }

        img.classList.add('d-none');
        pdf.classList.add('d-none');

        img.src = '';
        pdf.src = '';

        if (type === 'image') {
            img.src = url;
            img.classList.remove('d-none');
        } else {
            pdf.src = url;
            pdf.classList.remove('d-none');
        }

        new bootstrap.Modal(document.getElementById('soporteModal')).show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const numeroGanador = document.getElementById('numeroGanador');
        const box = document.getElementById('resultadoAsociado');

        if (!numeroGanador || !box) {
            return;
        }

        numeroGanador.addEventListener('input', function () {
            let numero = this.value.trim();

            if (numero.length < 2) {
                box.innerHTML = '<span class="text-muted">Ingresa un número para consultar.</span>';
                return;
            }

            box.innerHTML = '<span class="text-muted">Consultando...</span>';

            fetch(`{{ url('/admin/boletas/lookup') }}/${numero}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.ok) {
                        box.innerHTML = '<span class="text-danger fw-semibold">No encontrado</span>';
                        return;
                    }

                    box.innerHTML = `
                        <div>
                            <div class="fw-bold">${data.nombre}</div>
                            <div class="text-muted small">${data.documento ?? ''}</div>
                            ${data.agencia ? `<div class="text-muted small">Agencia: ${data.agencia}</div>` : ''}
                        </div>
                    `;
                })
                .catch(() => {
                    box.innerHTML = '<span class="text-danger fw-semibold">Error consultando</span>';
                });
        });
    });
</script>
@endpush