@extends('layouts.admin')

@php
    $title = 'Dashboard';
    $subtitle = 'Panel inteligente del sistema de sorteos.';
@endphp

@section('content')

<!-- KPIs -->
<div class="row g-4 mb-4">

    <div class="col-lg-3">
        <div class="stats-box">
            <p>Boletas asignadas</p>
            <h3>{{ number_format($boletasAsignadas) }}</h3>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="content-card card h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Boletas pendientes</div>
                <h3 class="fw-bold text-danger">
                    {{ number_format($boletasPendientes) }}
                </h3>
                <small class="text-muted">
                    Faltan por asignar
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="content-card card h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Ocupación</div>
                <h3 class="fw-bold">{{ $porcentajeOcupacion }}%</h3>

                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ $porcentajeOcupacion }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="content-card card h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Sorteos activos</div>
                <h3 class="fw-bold">{{ $sorteosActivos }}</h3>
            </div>
        </div>
    </div>

</div>

<!-- GRÁFICA + RANKING -->
<div class="row g-4">

    <!-- 📈 EVOLUCIÓN -->
    <div class="col-lg-7">
        <div class="content-card card h-100">
            <div class="card-header">
                <h5 class="fw-bold mb-1">Evolución de boletas</h5>
                <small class="text-muted">Últimos días</small>
            </div>
            <div class="card-body">
                <canvas id="chartEvolucion"></canvas>
            </div>
        </div>
    </div>

    <!-- 🏆 RANKING -->
    <div class="col-lg-5">
        <div class="content-card card h-100">
            <div class="card-header">
                <h5 class="fw-bold mb-1">Top asociados</h5>
            </div>

            <div class="card-body">
                @forelse($rankingAsociados as $index => $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>#{{ $index + 1 }}</strong>
                            {{ $item->asociado->nombre_completo ?? '—' }}
                        </div>
                        <span class="badge bg-primary">
                            {{ $item->total }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted">Sin datos</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

<!-- GANADORES -->
<div class="content-card card mt-4">
    <div class="card-header">
        <h5 class="fw-bold mb-1">Últimos ganadores</h5>
    </div>

    <div class="card-body">
        @if($ultimosGanadores->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Boleta</th>
                        <th>Asociado</th>
                        <th>Sorteo</th>
                        <th>Premio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimosGanadores as $premio)
                        <tr>
                            <td>{{ $premio->boletaGanadora->numero_boleta }}</td>
                            <td>{{ $premio->boletaGanadora->asociado->nombre_completo ?? '-' }}</td>
                            <td>{{ $premio->sorteo->nombre }}</td>
                            <td>{{ $premio->titulo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">No hay ganadores aún.</p>
        @endif
    </div>
</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const data = @json($boletasPorDia);

    const labels = data.map(i => i.fecha);
    const values = data.map(i => i.total);

    const ctx = document.getElementById('chartEvolucion');

    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Boletas generadas',
                data: values,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

});
</script>

@endpush