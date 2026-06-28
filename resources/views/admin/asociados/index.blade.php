@extends('layouts.admin')

@php
    $title = 'Gestión de Asociados';
    $subtitle = 'Consulta y administra asociados vinculados a sorteos.';

    $listaSorteos = $sorteos ?? collect();

    $sorteoActualId = request('sorteo_id', $sorteoId ?? null);

    if (!$sorteoActualId && $listaSorteos->count()) {
        $sorteoActualId = $listaSorteos->first()->id;
    }

    $sorteoActual = $sorteoSeleccionado ?? null;

    if (!$sorteoActual && $listaSorteos->count()) {
        $sorteoActual = $listaSorteos->firstWhere('id', (int) $sorteoActualId);
    }

    if (!$sorteoActual && $listaSorteos->count()) {
        $sorteoActual = $listaSorteos->first();
        $sorteoActualId = $sorteoActual->id;
    }

    $haySorteos = $listaSorteos->count() > 0;

    $totalBoletasPagina = $asociados->getCollection()->sum(function ($asociado) {
        return (int) data_get($asociado, 'pivot.boletas_por_persona', 0);
    });
@endphp

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>

{{-- PANEL PRINCIPAL --}}
<div class="asociados-hero card border-0 rounded-4 shadow-sm mb-4">

    <div class="card-body p-4">

        <div class="row g-4 align-items-center">

            <div class="col-lg-7">

                <div class="d-flex align-items-start gap-3">

                    <div class="asociados-hero-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>

                        <div class="text-muted small mb-1">
                            Participantes del sorteo
                        </div>

                        @if($sorteoActual)
                            <h4 class="fw-bold mb-2">
                                {{ $sorteoActual->nombre }}
                            </h4>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $sorteoActual->fecha_sorteo ? $sorteoActual->fecha_sorteo->format('d/m/Y') : 'Sin fecha' }}
                                </span>

                                @if($sorteoActual->estado)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-flag me-1"></i>
                                        {{ ucfirst($sorteoActual->estado) }}
                                    </span>
                                @endif

                                @if($sorteoActual->boletas_generadas)
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
                                Primero crea un sorteo para poder vincular participantes.
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
                                Total participantes
                            </div>

                            <div class="mini-stat-value">
                                {{ $asociados->total() }}
                            </div>

                            <div class="mini-stat-sub">
                                En el sorteo seleccionado
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mini-stat">
                            <div class="mini-stat-label">
                                Boletas config.
                            </div>

                            <div class="mini-stat-value">
                                {{ $totalBoletasPagina }}
                            </div>

                            <div class="mini-stat-sub">
                                En esta página
                            </div>
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
            action="{{ route('admin.asociados.index') }}"
            class="row g-3 align-items-end"
        >

            {{-- SORTEO --}}
            <div class="col-lg-4 col-md-6">

                <label class="form-label small text-muted mb-1">
                    Ver participantes del sorteo
                </label>

                <div class="asociado-select-wrapper">

                    <div class="asociado-select-icon">
                        <i class="bi bi-calendar2-event"></i>
                    </div>

                    <select
                        name="sorteo_id"
                        class="form-select asociado-select"
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

                    <div class="asociado-select-arrow">
                        <i class="bi bi-chevron-down"></i>
                    </div>

                </div>

            </div>

            {{-- BUSCADOR --}}
            <div class="col-lg-4 col-md-6">

                <label class="form-label small text-muted mb-1">
                    Buscar participante
                </label>

                <div class="search-wrapper">

                    <div class="search-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="form-control search-input"
                        placeholder="Cédula, nombre, email, cuenta..."
                    >

                </div>

            </div>

            {{-- PAGINACIÓN --}}
            <div class="col-lg-2 col-md-6">

                <label class="form-label small text-muted mb-1">
                    Registros
                </label>

                <select
                    name="per_page"
                    class="form-select"
                    onchange="this.form.submit()"
                >
                    <option value="10"  {{ (int)($perPage ?? 10) === 10 ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ (int)($perPage ?? 10) === 25 ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ (int)($perPage ?? 10) === 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (int)($perPage ?? 10) === 100 ? 'selected' : '' }}>100</option>
                </select>

            </div>

            {{-- BOTÓN --}}
            <div class="col-lg-2 col-md-6">

                <label class="form-label small text-muted mb-1 d-block">
                    Acción
                </label>

                <button class="btn btn-outline-primary rounded-pill w-100" type="submit">
                    <i class="bi bi-search me-1"></i>
                    Buscar
                </button>

            </div>

        </form>

    </div>

</div>

{{-- LISTADO --}}
<div class="content-card card">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Listado de participantes
            </h5>

            <small class="text-muted">
                Consulta la información específica de cada asociado en el sorteo seleccionado.
            </small>
        </div>

        @if($sorteoActual)
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-calendar2-event me-1"></i>
                    {{ $sorteoActual->nombre }}
                </span>
            </div>
        @endif

    </div>

    <div class="card-body">

        @if($asociados->count())

            <div class="table-responsive">

                <table class="table align-middle asociados-table">

                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Participante</th>
                            <th>Email</th>
                            <th>Agencia</th>
                            <th>Cuenta</th>
                            <th class="text-center">Boletas</th>
                            <th>Nómina</th>
                            <th>Coordinador</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($asociados as $asociado)

                        @php
                            $email = data_get($asociado, 'pivot.email') ?: '—';
                            $agencia = data_get($asociado, 'pivot.agencia') ?: '—';
                            $cuenta = data_get($asociado, 'pivot.cuenta') ?: '—';
                            $boletasPorPersona = data_get($asociado, 'pivot.boletas_por_persona') ?: 0;
                            $nomina = data_get($asociado, 'pivot.nomina') ?: '—';
                            $coordinador = data_get($asociado, 'pivot.coordinador') ?: '—';
                            $iniciales = strtoupper(substr($asociado->nombres ?? 'A', 0, 1) . substr($asociado->apellidos ?? '', 0, 1));
                        @endphp

                        <tr>

                            <td>
                                <span class="document-badge">
                                    {{ $asociado->documento }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="participant-avatar">
                                        {{ $iniciales }}
                                    </div>

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $asociado->nombre_completo }}
                                        </div>

                                        <div class="text-muted small">
                                            ID asociado: {{ $asociado->id }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <span class="text-muted small">
                                    {{ $email }}
                                </span>
                            </td>

                            <td>
                                {{ $agencia }}
                            </td>

                            <td>
                                <span class="account-badge">
                                    {{ $cuenta }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="ticket-count">
                                    {{ $boletasPorPersona }}
                                </span>
                            </td>

                            <td>
                                {{ $nomina }}
                            </td>

                            <td>
                                {{ $coordinador }}
                            </td>

                            <td>
                                @if($asociado->activo)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">

                <div class="text-muted small">
                    Mostrando
                    {{ $asociados->firstItem() }}
                    a
                    {{ $asociados->lastItem() }}
                    de
                    {{ $asociados->total() }}
                    registros
                </div>

                <div>
                    {{ $asociados->links() }}
                </div>

            </div>

        @else

            <div class="empty-state text-center py-5">

                <div class="empty-icon mx-auto mb-3">
                    <i class="bi bi-people"></i>
                </div>

                <h5 class="fw-bold">
                    No hay participantes
                </h5>

                @if($sorteoActual)
                    <p class="text-muted mb-0">
                        No existen asociados vinculados al sorteo
                        <strong>{{ $sorteoActual->nombre }}</strong>.
                    </p>
                @else
                    <p class="text-muted mb-0">
                        Selecciona o crea un sorteo para consultar participantes.
                    </p>
                @endif

            </div>

        @endif

    </div>

</div>

<style>
    .asociados-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .asociados-hero-icon {
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

    .asociado-select-wrapper,
    .search-wrapper {
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

    .asociado-select-wrapper:hover,
    .search-wrapper:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .09);
    }

    .asociado-select-wrapper:focus-within,
    .search-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .asociado-select-icon,
    .search-icon {
        width: 52px;
        height: 52px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .asociado-select,
    .search-input {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: .95rem 3rem .95rem 1rem !important;
        font-weight: 600;
        color: #111827;
        background-color: transparent;
        min-height: 52px;
    }

    .asociado-select {
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .asociado-select:focus,
    .search-input:focus {
        box-shadow: none !important;
    }

    .asociado-select-arrow {
        position: absolute;
        right: 18px;
        color: #6b7280;
        pointer-events: none;
        font-size: 15px;
    }

    .asociados-table tbody tr {
        transition: background .15s ease;
    }

    .asociados-table tbody tr:hover {
        background: #f8fbff;
    }

    .document-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        font-size: .85rem;
    }

    .participant-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .85rem;
        flex: 0 0 auto;
        box-shadow: 0 10px 22px rgba(37, 99, 235, .20);
    }

    .account-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: .85rem;
    }

    .ticket-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-weight: 800;
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
        .asociados-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .participant-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
        }
    }
</style>

@endsection