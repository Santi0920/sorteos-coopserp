@extends('layouts.admin')

@php
    $title = 'Dashboard General';
    $subtitle = 'Panel inteligente del sistema de sorteos.';

    $rankingCollection = collect($rankingAsociados ?? []);
    $ganadoresCollection = collect($ultimosGanadores ?? []);
    $boletasPorDiaCollection = collect($boletasPorDia ?? []);

    $topParticipante = $rankingCollection->first();
    $totalRankingBoletas = $rankingCollection->sum('total');
@endphp

@section('topbar_actions')
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.sorteos.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-calendar2-event me-1"></i>
            Ver sorteos
        </a>

        <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>
            Nuevo sorteo
        </a>
    </div>
@endsection

@section('content')


{{-- KPIS --}}
<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">
        <div class="kpi-card kpi-primary">
            <div class="kpi-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>

            <div>
                <div class="kpi-label">
                    Boletas asignadas
                </div>

                <div class="kpi-value">
                    {{ number_format($boletasAsignadas ?? 0) }}
                </div>

                <div class="kpi-sub">
                    Total del sistema
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-icon soft-blue">
                <i class="bi bi-calendar2-check"></i>
            </div>

            <div>
                <div class="kpi-label">
                    Sorteos activos
                </div>

                <div class="kpi-value">
                    {{ number_format($sorteosActivos ?? 0) }}
                </div>

                <div class="kpi-sub">
                    En ejecución o disponibles
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-icon soft-green">
                <i class="bi bi-trophy"></i>
            </div>

            <div>
                <div class="kpi-label">
                    Últimos ganadores
                </div>

                <div class="kpi-value">
                    {{ number_format($ganadoresCollection->count()) }}
                </div>

                <div class="kpi-sub">
                    Registros recientes
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-icon soft-purple">
                <i class="bi bi-people"></i>
            </div>

            <div>
                <div class="kpi-label">
                    Top ranking
                </div>

                <div class="kpi-value">
                    {{ number_format($totalRankingBoletas) }}
                </div>

                <div class="kpi-sub">
                    Boletas del top actual
                </div>
            </div>
        </div>
    </div>

</div>

{{-- GRÁFICA + RANKING --}}
<div class="row g-4">

    {{-- EVOLUCIÓN --}}
    <div class="col-lg-7">
        <div class="content-card card h-100">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h5 class="fw-bold mb-1">
                        Evolución de boletas
                    </h5>

                    <small class="text-muted">
                        Comportamiento de generación en los últimos días.
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    <i class="bi bi-graph-up-arrow me-1"></i>
                    Tendencia
                </span>

            </div>

            <div class="card-body">

                @if($boletasPorDiaCollection->count())
                    <div class="chart-container">
                        <canvas id="chartEvolucion"></canvas>
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mx-auto mb-3">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>

                        <h5 class="fw-bold">
                            Sin datos para graficar
                        </h5>

                        <p class="text-muted mb-0">
                            Cuando se generen boletas, aquí verás la evolución.
                        </p>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- RANKING --}}
    <div class="col-lg-5">
        <div class="content-card card h-100">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h5 class="fw-bold mb-1">
                        Top 10 participantes
                    </h5>

                    <small class="text-muted">
                        Participantes con más boletas asignadas.
                    </small>
                </div>

                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="bi bi-award me-1"></i>
                    Ranking
                </span>

            </div>

            <div class="card-body">

                @forelse($rankingCollection as $index => $item)

                    @php
                        $asociado = $item->asociado ?? null;
                        $sorteo = $item->sorteo ?? null;

                        $nombre = $asociado->nombre_completo ?? '—';
                        $iniciales = strtoupper(substr($nombre, 0, 1));
                    @endphp

                    <div class="ranking-item">

                        <div class="d-flex align-items-center gap-3">

                            <div class="ranking-position">
                                #{{ $index + 1 }}
                            </div>

                            <div class="ranking-avatar">
                                {{ $iniciales }}
                            </div>

                            <div style="min-width: 0;">
                                <div class="fw-semibold text-truncate">
                                    {{ $nombre }}
                                </div>

                                <div class="text-muted small text-truncate">
                                    {{ $sorteo->nombre ?? 'Sin sorteo' }}
                                </div>
                            </div>

                        </div>

                        <span class="ranking-badge">
                            {{ number_format($item->total ?? 0) }}
                        </span>

                    </div>

                @empty

                    <div class="empty-state text-center py-5">

                        <div class="empty-icon mx-auto mb-3">
                            <i class="bi bi-people"></i>
                        </div>

                        <h5 class="fw-bold">
                            Sin ranking
                        </h5>

                        <p class="text-muted mb-0">
                            Aún no hay participantes con boletas asignadas.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>
    </div>

</div>

{{-- GANADORES --}}
<div class="content-card card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

        <div>
            <h5 class="fw-bold mb-1">
                Últimos ganadores
            </h5>

            <small class="text-muted">
                Consulta los premios que ya tienen boleta ganadora asignada.
            </small>
        </div>

        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
            <i class="bi bi-trophy me-1"></i>
            Ganadores
        </span>

    </div>

    <div class="card-body">

        @if($ganadoresCollection->count())

            <div class="table-responsive">

                <table class="table align-middle dashboard-table">

                    <thead>
                        <tr>
                            <th>Boleta</th>
                            <th>Asociado</th>
                            <th>Sorteo</th>
                            <th>Premio</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($ganadoresCollection as $premio)

                        @php
                            $boletaGanadora = $premio->boletaGanadora ?? null;
                            $asociadoGanador = $boletaGanadora?->asociado;
                            $sorteoPremio = $premio->sorteo ?? null;
                        @endphp

                        <tr>

                            <td>
                                <span class="ticket-number">
                                    {{ $boletaGanadora->numero_boleta ?? '—' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $asociadoGanador->nombre_completo ?? '—' }}
                                </div>

                                @if($asociadoGanador?->documento)
                                    <div class="text-muted small">
                                        {{ $asociadoGanador->documento }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold small">
                                    {{ $sorteoPremio->nombre ?? '—' }}
                                </div>
                            </td>

                            <td>
                                <span class="prize-badge">
                                    <i class="bi bi-gift me-1"></i>
                                    {{ $premio->titulo ?? '—' }}
                                </span>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state text-center py-5">

                <div class="empty-icon mx-auto mb-3">
                    <i class="bi bi-trophy"></i>
                </div>

                <h5 class="fw-bold">
                    No hay ganadores aún
                </h5>

                <p class="text-muted mb-0">
                    Cuando registres ganadores, aparecerán en este panel.
                </p>

            </div>

        @endif

    </div>

</div>

<style>
    .dashboard-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .dashboard-hero-icon {
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

    .kpi-icon.soft-purple {
        background: #f3e8ff;
        color: #7e22ce;
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

    .chart-container {
        position: relative;
        min-height: 320px;
    }

    .ranking-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #eef1f5;
    }

    .ranking-item:last-child {
        border-bottom: 0;
    }

    .ranking-position {
        width: 42px;
        height: 34px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .85rem;
        flex: 0 0 auto;
    }

    .ranking-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        flex: 0 0 auto;
        box-shadow: 0 10px 22px rgba(37, 99, 235, .20);
    }

    .ranking-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1d4ed8;
        font-weight: 800;
    }

    .dashboard-table tbody tr {
        transition: background .15s ease;
    }

    .dashboard-table tbody tr:hover {
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

    .prize-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-weight: 800;
        font-size: .85rem;
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
        .dashboard-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .kpi-card {
            padding: 18px;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
        }

        .chart-container {
            min-height: 260px;
        }
    }
</style>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const data = @json($boletasPorDia ?? []);

    const labels = data.map(item => item.fecha);
    const values = data.map(item => item.total);

    const ctx = document.getElementById('chartEvolucion');

    if (!ctx || !data.length) {
        return;
    }

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.28)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.02)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Boletas generadas',
                data: values,
                tension: 0.42,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#2563eb',
                borderWidth: 3,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' boletas generadas';
                        }
                    }
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },

                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, .22)'
                    },
                    ticks: {
                        color: '#6b7280',
                        precision: 0
                    }
                }
            }
        }
    });

});
</script>

@endpush