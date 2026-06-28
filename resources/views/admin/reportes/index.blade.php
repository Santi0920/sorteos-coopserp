@extends('layouts.admin')

@php
    $title = 'Módulo de Reportes';
    $subtitle = 'Análisis y conciliación de boletas por sorteo.';

    $listaSorteos = $sorteos ?? collect();
    $sorteoActualId = request('sorteo_id', $sorteoId ?? null);

    if (!$sorteoActualId && $listaSorteos->count()) {
        $sorteoActualId = $listaSorteos->first()->id;
    }

    $avanceGeneracion = ($totalBoletasConfiguradas ?? 0) > 0
        ? round((($totalAsignadas ?? 0) / $totalBoletasConfiguradas) * 100)
        : 0;

    $avanceGeneracion = min(100, $avanceGeneracion);
@endphp

@section('topbar_actions')
    <div class="d-flex flex-wrap gap-2">
        @if($sorteo)
            <a href="{{ route('admin.boletas.mapa', $sorteo->id) }}" class="btn btn-warning rounded-pill px-4">
                <i class="bi bi-grid-3x3-gap me-1"></i>
                Mapa
            </a>

            <a href="{{ route('admin.boletas.index', ['sorteo_id' => $sorteo->id]) }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-ticket-perforated me-1"></i>
                Boletas
            </a>
        @endif
    </div>
@endsection

@section('content')



{{-- FILTRO PROFESIONAL --}}
<div class="content-card card mb-4">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Seleccionar sorteo
            </h5>

            <small class="text-muted">
                Busca y selecciona el sorteo para consultar sus reportes.
            </small>
        </div>

    </div>

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.reportes.index') }}"
            id="formSeleccionarSorteoReporte"
        >

            <input
                type="hidden"
                name="sorteo_id"
                id="reporteSorteoIdInput"
                value="{{ $sorteoActualId }}"
            >

            <div class="row g-4 align-items-end">

                <div class="col-lg-8">

                    <label class="form-label small text-muted mb-1">
                        Buscar sorteo
                    </label>

                    <div class="reporte-picker" id="reportePicker">

                        <button
                            type="button"
                            class="reporte-picker-trigger"
                            id="reportePickerTrigger"
                        >

                            <div class="reporte-picker-icon">
                                <i class="bi bi-calendar2-event"></i>
                            </div>

                            <div class="reporte-picker-current">

                                @if($sorteo)
                                    <div class="reporte-picker-title">
                                        {{ $sorteo->nombre }}
                                    </div>

                                    <div class="reporte-picker-subtitle">
                                        {{ $sorteo->fecha_sorteo ? $sorteo->fecha_sorteo->format('d/m/Y') : 'Sin fecha' }}
                                        ·
                                        {{ ucfirst($sorteo->estado ?? 'Sin estado') }}
                                    </div>
                                @else
                                    <div class="reporte-picker-title">
                                        Selecciona un sorteo
                                    </div>

                                    <div class="reporte-picker-subtitle">
                                        Escribe para buscar por nombre, fecha o estado
                                    </div>
                                @endif

                            </div>

                            <div class="reporte-picker-arrow">
                                <i class="bi bi-chevron-down"></i>
                            </div>

                        </button>

                        <div class="reporte-picker-dropdown" id="reportePickerDropdown">

                            <div class="reporte-picker-search">

                                <i class="bi bi-search"></i>

                                <input
                                    type="text"
                                    id="reporteSearchInput"
                                    placeholder="Buscar sorteo..."
                                    autocomplete="off"
                                >

                            </div>

                            <div class="reporte-picker-count">
                                <span id="reporteSearchCount">
                                    {{ $listaSorteos->count() }}
                                </span>
                                sorteos disponibles
                            </div>

                            <div class="reporte-picker-list" id="reportePickerList">

                                @foreach($listaSorteos as $item)

                                    @php
                                        $fechaTexto = $item->fecha_sorteo
                                            ? $item->fecha_sorteo->format('d/m/Y')
                                            : 'Sin fecha';

                                        $estadoTexto = ucfirst($item->estado ?? 'Sin estado');

                                        $searchText = strtolower(
                                            $item->nombre . ' ' . $fechaTexto . ' ' . $estadoTexto
                                        );

                                        $isSelected = (string) $sorteoActualId === (string) $item->id;
                                    @endphp

                                    <button
                                        type="button"
                                        class="reporte-picker-option {{ $isSelected ? 'active' : '' }}"
                                        data-id="{{ $item->id }}"
                                        data-title="{{ $item->nombre }}"
                                        data-subtitle="{{ $fechaTexto }} · {{ $estadoTexto }}"
                                        data-search="{{ $searchText }}"
                                    >

                                        <div class="reporte-option-icon">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>

                                        <div class="reporte-option-content">

                                            <div class="reporte-option-title">
                                                {{ $item->nombre }}
                                            </div>

                                            <div class="reporte-option-meta">
                                                <span>
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $fechaTexto }}
                                                </span>

                                                <span>
                                                    <i class="bi bi-flag me-1"></i>
                                                    {{ $estadoTexto }}
                                                </span>
                                            </div>

                                        </div>

                                        @if($isSelected)
                                            <div class="reporte-option-check">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </div>
                                        @endif

                                    </button>

                                @endforeach

                                <div class="reporte-picker-empty d-none" id="reportePickerEmpty">
                                    <i class="bi bi-search fs-3 text-muted"></i>

                                    <div class="fw-bold mt-2">
                                        No se encontraron sorteos
                                    </div>

                                    <div class="text-muted small">
                                        Intenta buscar con otro nombre, fecha o estado.
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <button class="btn btn-primary rounded-pill w-100" type="submit">
                        <i class="bi bi-bar-chart-line me-1"></i>
                        Ver reporte
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@if($sorteo)

    {{-- ACCIONES --}}
    <div class="content-card card mb-4 border-0 shadow-sm">

        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <h5 class="fw-bold mb-1">
                    Acciones rápidas
                </h5>

                <div class="text-muted small">
                    Navega a los módulos relacionados del sorteo seleccionado.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.boletas.mapa', $sorteo->id) }}"
                   class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Mapa de boletas
                </a>

                <a href="{{ route('admin.boletas.index', ['sorteo_id' => $sorteo->id]) }}"
                   class="btn btn-outline-dark rounded-pill px-4">
                    <i class="bi bi-ticket-perforated me-1"></i>
                    Boletas
                </a>

                <a href="{{ route('admin.asociados.index', ['sorteo_id' => $sorteo->id]) }}"
                   class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-people me-1"></i>
                    Participantes
                </a>

            </div>

        </div>

    </div>

    {{-- KPIS --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="kpi-card kpi-primary">
                <div class="kpi-icon">
                    <i class="bi bi-collection"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Pool total
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalEmitidas ?? 0) }}
                    </div>

                    <div class="kpi-sub">
                        Rango del sorteo
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon soft-blue">
                    <i class="bi bi-clipboard-data"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Configuradas
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalBoletasConfiguradas ?? 0) }}
                    </div>

                    <div class="kpi-sub">
                        Desde tabla pivote
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon soft-green">
                    <i class="bi bi-ticket-perforated"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Generadas
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalAsignadas ?? 0) }}
                    </div>

                    <div class="kpi-sub">
                        Boletas reales
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon soft-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Pendientes
                    </div>

                    <div class="kpi-value">
                        {{ number_format($totalPendientes ?? 0) }}
                    </div>

                    <div class="kpi-sub">
                        Configuradas - generadas
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CONCILIACIÓN --}}
    <div class="content-card card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="fw-bold mb-1">
                    Conciliación de boletas
                </h5>

                <small class="text-muted">
                    Compara boletas configuradas contra boletas realmente generadas.
                </small>
            </div>

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                {{ $avanceGeneracion }}% generado
            </span>

        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">
                    Avance de generación
                </span>

                <strong class="small">
                    {{ number_format($totalAsignadas ?? 0) }}
                    /
                    {{ number_format($totalBoletasConfiguradas ?? 0) }}
                </strong>
            </div>

            <div class="progress rounded-pill" style="height: 12px;">
                <div
                    class="progress-bar"
                    style="width: {{ $avanceGeneracion }}%;"
                ></div>
            </div>

            <div class="row g-3 mt-3">

                <div class="col-md-3">
                    <div class="conciliation-box">
                        <span>Participantes</span>
                        <strong>{{ number_format($totalParticipantes ?? 0) }}</strong>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="conciliation-box">
                        <span>Configuradas</span>
                        <strong>{{ number_format($totalBoletasConfiguradas ?? 0) }}</strong>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="conciliation-box">
                        <span>Pendientes</span>
                        <strong>{{ number_format($totalPendientes ?? 0) }}</strong>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="conciliation-box">
                        <span>Pool disponible</span>
                        <strong>{{ number_format($totalDisponiblesPool ?? 0) }}</strong>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- TOP ASOCIADO --}}
    <div class="content-card card mb-4">

        <div class="card-header">

            <h5 class="fw-bold mb-1">
                Top asociado
            </h5>

            <small class="text-muted">
                Asociado con más boletas generadas en este sorteo.
            </small>

        </div>

        <div class="card-body">

            @if($topAsociado && $topAsociado->asociado)

                <div class="top-associate-card">

                    <div class="top-associate-avatar">
                        {{ strtoupper(substr($topAsociado->asociado->nombres ?? 'A', 0, 1) . substr($topAsociado->asociado->apellidos ?? '', 0, 1)) }}
                    </div>

                    <div style="min-width: 0;">
                        <div class="fw-bold fs-5">
                            {{ $topAsociado->asociado->nombre_completo }}
                        </div>

                        <div class="text-muted">
                            {{ $topAsociado->asociado->documento }}
                        </div>

                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mt-2">
                            {{ number_format($topAsociado->total) }} boletas generadas
                        </span>
                    </div>

                </div>

            @else

                <div class="empty-state text-center py-4">
                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-person"></i>
                    </div>

                    <h6 class="fw-bold">
                        Sin datos
                    </h6>

                    <p class="text-muted mb-0">
                        Aún no hay boletas generadas para calcular un top asociado.
                    </p>
                </div>

            @endif

        </div>

    </div>

    {{-- POR ASOCIADO --}}
    <div class="content-card card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="fw-bold mb-1">
                    Reporte por asociado
                </h5>

                <small class="text-muted">
                    Configuración desde pivote y boletas reales generadas.
                </small>
            </div>

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                Participantes
            </span>

        </div>

        <div class="card-body">

            @if($porAsociado->count())

                <div class="table-responsive">

                    <table class="table align-middle reportes-table">

                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Asociado</th>
                                <th>Agencia</th>
                                <th>Cuenta</th>
                                <th class="text-center">Config.</th>
                                <th class="text-center">Generadas</th>
                                <th class="text-center">Pendientes</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($porAsociado as $a)

                            @php
                                $nombreCompleto = trim(($a->nombres ?? '') . ' ' . ($a->apellidos ?? ''));
                                $configuradas = (int) ($a->boletas_configuradas ?? 0);
                                $generadas = (int) ($a->total_generadas ?? 0);
                                $pendientes = max(0, $configuradas - $generadas);
                            @endphp

                            <tr>

                                <td>
                                    <span class="document-badge">
                                        {{ $a->documento }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $nombreCompleto ?: '—' }}
                                    </div>

                                    @if($a->email)
                                        <div class="text-muted small">
                                            {{ $a->email }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $a->agencia ?: 'Sin agencia' }}
                                </td>

                                <td>
                                    <span class="account-badge">
                                        {{ $a->cuenta ?: '—' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="config-badge">
                                        {{ number_format($configuradas) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="generated-badge">
                                        {{ number_format($generadas) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($pendientes > 0)
                                        <span class="pending-badge">
                                            {{ number_format($pendientes) }}
                                        </span>
                                    @else
                                        <span class="ok-badge">
                                            OK
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $porAsociado->appends(request()->query())->links() }}
                </div>

            @else

                <div class="empty-state text-center py-5">
                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-people"></i>
                    </div>

                    <h5 class="fw-bold">
                        Sin asociados
                    </h5>

                    <p class="text-muted mb-0">
                        No hay participantes vinculados a este sorteo.
                    </p>
                </div>

            @endif

        </div>

    </div>

    {{-- POR AGENCIA --}}
    <div class="content-card card">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="fw-bold mb-1">
                    Reporte por agencia
                </h5>

                <small class="text-muted">
                    Agrupado desde la columna agencia de la tabla pivote.
                </small>
            </div>

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                Agencias
            </span>

        </div>

        <div class="card-body">

            @if($porAgencia->count())

                <div class="table-responsive">

                    <table class="table align-middle reportes-table">

                        <thead>
                            <tr>
                                <th>Agencia</th>
                                <th class="text-center">Participantes</th>
                                <th class="text-center">Config.</th>
                                <th class="text-center">Generadas</th>
                                <th class="text-center">Pendientes</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($porAgencia as $a)

                            @php
                                $configuradasAgencia = (int) ($a->boletas_configuradas ?? 0);
                                $generadasAgencia = (int) ($a->total_generadas ?? 0);
                                $pendientesAgencia = max(0, $configuradasAgencia - $generadasAgencia);
                            @endphp

                            <tr>

                                <td>
                                    <span class="agency-badge">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $a->agencia ?? 'Sin agencia' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="document-badge">
                                        {{ number_format($a->participantes ?? 0) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="config-badge">
                                        {{ number_format($configuradasAgencia) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="generated-badge">
                                        {{ number_format($generadasAgencia) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($pendientesAgencia > 0)
                                        <span class="pending-badge">
                                            {{ number_format($pendientesAgencia) }}
                                        </span>
                                    @else
                                        <span class="ok-badge">
                                            OK
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $porAgencia->appends(request()->query())->links() }}
                </div>

            @else

                <div class="empty-state text-center py-5">
                    <div class="empty-icon mx-auto mb-3">
                        <i class="bi bi-building"></i>
                    </div>

                    <h5 class="fw-bold">
                        Sin agencias
                    </h5>

                    <p class="text-muted mb-0">
                        No hay información de agencias para este sorteo.
                    </p>
                </div>

            @endif

        </div>

    </div>

@else

    <div class="empty-state text-center py-5">
        <div class="empty-icon mx-auto mb-3">
            <i class="bi bi-bar-chart-line"></i>
        </div>

        <h5 class="fw-bold">
            Selecciona un sorteo
        </h5>

        <p class="text-muted mb-0">
            Elige un sorteo para ver reportes, análisis y conciliación de boletas.
        </p>
    </div>

@endif

<style>
    .reportes-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .reportes-hero-icon {
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

    .kpi-card {
        background: #fff;
        border: 1px solid #eef1f5;
        border-radius: 22px;
        padding: 22px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
    }

    .kpi-primary {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        box-shadow: 0 18px 35px rgba(37, 99, 235, .28);
    }

    .kpi-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        background: rgba(255,255,255,.16);
        color: inherit;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        flex: 0 0 auto;
    }

    .kpi-icon.soft-blue {
        background: #eef4ff;
        color: #2563eb;
    }

    .kpi-icon.soft-green {
        background: #dcfce7;
        color: #166534;
    }

    .kpi-icon.soft-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .kpi-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .kpi-primary .kpi-label,
    .kpi-primary .kpi-sub {
        color: rgba(255,255,255,.78);
    }

    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    .kpi-sub {
        color: #6c757d;
        font-size: 13px;
        margin-top: 5px;
    }

    .conciliation-box {
        border: 1px solid #e9ecef;
        border-radius: 18px;
        padding: 16px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .conciliation-box span {
        color: #6c757d;
        font-size: .9rem;
    }

    .conciliation-box strong {
        font-size: 1.15rem;
    }

    .top-associate-card {
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid #e9ecef;
        background: #f8fbff;
        border-radius: 22px;
        padding: 22px;
    }

    .top-associate-avatar {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1rem;
        flex: 0 0 auto;
        box-shadow: 0 12px 26px rgba(37, 99, 235, .22);
    }

    .reportes-table tbody tr {
        transition: background .15s ease;
    }

    .reportes-table tbody tr:hover {
        background: #f8fbff;
    }

    .document-badge,
    .account-badge,
    .agency-badge,
    .config-badge,
    .generated-badge,
    .pending-badge,
    .ok-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 12px;
        border-radius: 999px;
        font-weight: 800;
        font-size: .85rem;
    }

    .document-badge {
        background: #f1f5f9;
        color: #334155;
    }

    .account-badge,
    .agency-badge {
        background: #eef4ff;
        color: #1d4ed8;
    }

    .config-badge {
        background: #f1f5f9;
        color: #334155;
    }

    .generated-badge,
    .ok-badge {
        background: #dcfce7;
        color: #166534;
    }

    .pending-badge {
        background: #fef3c7;
        color: #92400e;
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

    .reporte-picker {
        position: relative;
        width: 100%;
    }

    .reporte-picker-trigger {
        width: 100%;
        min-height: 72px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        border-radius: 20px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: left;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        transition: all .18s ease;
    }

    .reporte-picker-trigger:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .10);
    }

    .reporte-picker.open .reporte-picker-trigger {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .reporte-picker-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex: 0 0 auto;
    }

    .reporte-picker-current {
        min-width: 0;
        flex: 1;
    }

    .reporte-picker-title {
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reporte-picker-subtitle {
        color: #6b7280;
        font-size: .88rem;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reporte-picker-arrow {
        color: #6b7280;
        transition: transform .18s ease;
    }

    .reporte-picker.open .reporte-picker-arrow {
        transform: rotate(180deg);
    }

    .reporte-picker-dropdown {
        position: absolute;
        z-index: 50;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, .18);
        padding: 14px;
        display: none;
    }

    .reporte-picker.open .reporte-picker-dropdown {
        display: block;
    }

    .reporte-picker-search {
        height: 48px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 14px;
        margin-bottom: 10px;
    }

    .reporte-picker-search i {
        color: #2563eb;
    }

    .reporte-picker-search input {
        border: 0;
        outline: 0;
        background: transparent;
        width: 100%;
        font-weight: 650;
        color: #111827;
    }

    .reporte-picker-count {
        font-size: .82rem;
        color: #6b7280;
        margin: 0 4px 10px;
    }

    .reporte-picker-list {
        max-height: 340px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .reporte-picker-list::-webkit-scrollbar {
        width: 7px;
    }

    .reporte-picker-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .reporte-picker-option {
        width: 100%;
        border: 0;
        background: transparent;
        border-radius: 16px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-align: left;
        transition: background .15s ease;
    }

    .reporte-picker-option:hover {
        background: #f8fbff;
    }

    .reporte-picker-option.active {
        background: #eff6ff;
    }

    .reporte-option-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eef4ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 18px;
    }

    .reporte-picker-option.active .reporte-option-icon {
        background: #2563eb;
        color: #ffffff;
    }

    .reporte-option-content {
        min-width: 0;
        flex: 1;
    }

    .reporte-option-title {
        font-weight: 800;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reporte-option-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: #6b7280;
        font-size: .82rem;
        margin-top: 3px;
    }

    .reporte-option-check {
        color: #2563eb;
        font-size: 18px;
    }

    .reporte-picker-empty {
        padding: 34px 16px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .reportes-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .reporte-picker-dropdown {
            position: fixed;
            left: 16px;
            right: 16px;
            top: 120px;
            max-height: calc(100vh - 150px);
        }

        .reporte-picker-list {
            max-height: calc(100vh - 260px);
        }
    }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.getElementById('reportePicker');
    const trigger = document.getElementById('reportePickerTrigger');
    const searchInput = document.getElementById('reporteSearchInput');
    const hiddenInput = document.getElementById('reporteSorteoIdInput');
    const form = document.getElementById('formSeleccionarSorteoReporte');
    const options = Array.from(document.querySelectorAll('.reporte-picker-option'));
    const empty = document.getElementById('reportePickerEmpty');
    const count = document.getElementById('reporteSearchCount');

    if (!picker || !trigger || !searchInput || !hiddenInput || !form) {
        return;
    }

    function openPicker() {
        picker.classList.add('open');

        setTimeout(() => {
            searchInput.focus();
            searchInput.select();
        }, 80);
    }

    function closePicker() {
        picker.classList.remove('open');
        searchInput.value = '';
        filterOptions('');
    }

    function filterOptions(query) {
        const cleanQuery = query.toLowerCase().trim();

        let visible = 0;

        options.forEach(option => {
            const text = option.dataset.search || '';

            if (!cleanQuery || text.includes(cleanQuery)) {
                option.classList.remove('d-none');
                visible++;
            } else {
                option.classList.add('d-none');
            }
        });

        if (count) {
            count.textContent = visible;
        }

        if (empty) {
            empty.classList.toggle('d-none', visible > 0);
        }
    }

    trigger.addEventListener('click', function () {
        if (picker.classList.contains('open')) {
            closePicker();
        } else {
            openPicker();
        }
    });

    searchInput.addEventListener('input', function () {
        filterOptions(this.value);
    });

    options.forEach(option => {
        option.addEventListener('click', function () {
            const id = this.dataset.id;
            const title = this.dataset.title;
            const subtitle = this.dataset.subtitle;

            hiddenInput.value = id;

            trigger.querySelector('.reporte-picker-title').textContent = title;
            trigger.querySelector('.reporte-picker-subtitle').textContent = subtitle;

            options.forEach(item => {
                item.classList.remove('active');

                const oldCheck = item.querySelector('.reporte-option-check');

                if (oldCheck) {
                    oldCheck.remove();
                }
            });

            this.classList.add('active');

            const check = document.createElement('div');
            check.className = 'reporte-option-check';
            check.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
            this.appendChild(check);

            closePicker();

            form.submit();
        });
    });

    document.addEventListener('click', function (event) {
        if (!picker.contains(event.target)) {
            closePicker();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePicker();
        }
    });
});
</script>
@endpush