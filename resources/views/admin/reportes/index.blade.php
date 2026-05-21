@extends('layouts.admin')

@php
    $title = 'Módulo de Reportes';
    $subtitle = 'Análisis y conciliación de boletas por sorteo';
@endphp

@section('content')

<!-- FILTRO -->
<div class="content-card card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">

            <div class="col-md-6">
                <select name="sorteo_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Selecciona un sorteo</option>
                    @foreach($sorteos as $s)
                        <option value="{{ $s->id }}" {{ $sorteoId == $s->id ? 'selected' : '' }}>
                            {{ $s->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>
</div>

@if($sorteo)
<div class="content-card card mb-4 border-0 shadow-sm">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="fw-bold mb-1">
                {{ $sorteo->nombre }}
            </h5>

            <div class="text-muted small">
                Reportes, análisis y visualización del sorteo activo
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <!-- MAPA -->
            <a href="{{ route('admin.boletas.mapa', $sorteo->id) }}"
               class="btn btn-warning d-flex align-items-center gap-2 px-3 rounded-3 shadow-sm">

                <i class="bi bi-grid-3x3-gap"></i>
                <span>Mapa de boletas</span>
            </a>

            <!-- BOLETAS -->
            <a href="{{ route('admin.boletas.index', ['sorteo_id' => $sorteo->id]) }}"
               class="btn btn-outline-dark d-flex align-items-center gap-2 px-3 rounded-3">

                <i class="bi bi-ticket-perforated"></i>
                <span>Boletas</span>
            </a>

            <!-- PARTICIPANTES -->
            <a href="{{ route('admin.asociados.index', ['sorteo_id' => $sorteo->id]) }}"
               class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 rounded-3">

                <i class="bi bi-people"></i>
                <span>Participantes</span>
            </a>

        </div>

    </div>
</div>
<!-- KPI -->
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stats-box">
            <p>Boletas emitidas</p>
            <h3>{{ $totalEmitidas }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-box">
            <p>Asignadas</p>
            <h3>{{ $totalAsignadas }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-box">
            <p>Pendientes</p>
            <h3>{{ $totalPendientes }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-box">
            <p>Ganadoras</p>
            <h3>{{ $totalGanadoras }}</h3>
        </div>
    </div>

</div>

<!-- TOP ASOCIADO -->
<div class="content-card card mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">🏆 Top asociado</h5>

        @if($topAsociado)
            <div>
                <strong>{{ $topAsociado->asociado->nombre_completo }}</strong><br>
                <small class="text-muted">{{ $topAsociado->asociado->documento }}</small><br>
                <span class="badge bg-primary mt-2">
                    {{ $topAsociado->total }} boletas
                </span>
            </div>
        @else
            <p class="text-muted">Sin datos</p>
        @endif

    </div>
</div>

<!-- POR ASOCIADO -->
<div class="content-card card mb-4">
    <div class="card-body">
        <h5 class="fw-bold">Boletas por asociado</h5>

        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($porAsociado as $a)
                    <tr>
                        <td>{{ $a->documento }}</td>
                        <td>{{ $a->nombres }}</td>
                        <td><span class="badge bg-dark">{{ $a->total }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $porAsociado->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- POR AGENCIA -->
<div class="content-card card">
    <div class="card-body">
        <h5 class="fw-bold">Boletas por agencia</h5>

        <table class="table">
            <thead>
                <tr>
                    <th>Agencia</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($porAgencia as $a)
                    <tr>
                        <td>{{ $a->agencia ?? 'Sin agencia' }}</td>
                        <td><span class="badge bg-primary">{{ $a->total }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $porAgencia->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@else

<div class="text-center py-5">
    <h5>Selecciona un sorteo para ver reportes</h5>
</div>

@endif

@endsection