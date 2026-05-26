@extends('layouts.admin')

@php
    $title = 'Mapa de Boletas';
    $subtitle = 'Visualización completa del estado de todas las boletas.';
@endphp

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>
<!-- LOADER -->
<div id="loaderBoletas" class="loader-overlay">
    <div class="loader-box text-center">
        <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
        <h5 class="fw-bold">Cargando mapa de boletas</h5>
        <p class="text-muted">Preparando visualización...</p>
    </div>
</div>

<div class="content-card card mb-4 border-0 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

        <!-- CONTEXTO DEL SORTEO -->
        <div>

            <div class="d-flex align-items-center gap-2 text-muted small mb-1">
                <span>Sorteo activo:</span>

                <span class="fw-semibold text-dark">
                    {{ $sorteo->nombre }}
                </span>

                <span>•</span>

                <span>
                    {{ \Carbon\Carbon::parse($sorteo->fecha_sorteo)->translatedFormat('d M Y') }}
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">

                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    {{ count($boletas) }} Boletas asignadas al sorteo
                </span>

                <span class="text-muted small">
                    Vista en tiempo real del sorteo
                </span>

            </div>

        </div>

        <!-- ACCIONES -->
        <div class="d-flex gap-2">

            <a href="{{ route('admin.boletas.index') }}"
               class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">

                <i class="bi bi-arrow-left"></i>
                Volver

            </a>

        </div>

    </div>
</div>

<div class="content-card card">
    <div class="card-body">

        <!-- LEYENDA -->
        <div class="d-flex gap-3 mb-4 flex-wrap">
            <span class="badge bg-success-subtle text-success">Disponible</span>
            <span class="badge bg-danger-subtle text-danger">Asignada</span>
            <span class="badge bg-warning-subtle text-warning">Ganadora</span>
        </div>

        <!-- GRID -->
        <div class="boletas-scroll">
            <div class="boletas-grid" id="gridBoletas">
                @for($i = (int)$sorteo->numero_inicio; $i <= (int)$sorteo->numero_fin; $i++)
                    @php
                        $max = (int) $sorteo->numero_fin;
                        $digits = strlen((string) $max);
                        $numero = str_pad($i, $digits, '0', STR_PAD_LEFT);
                        $boleta = $boletas[(string)$i] ?? null;

                        $estado = 'disponible';
                        if ($boleta) {
                            $estado = $boleta->ganadora ? 'ganadora' : 'ocupada';
                        }

                        $nombre = $boleta
                            ? trim(($boleta->asociado->nombres ?? '') . ' ' . ($boleta->asociado->apellidos ?? ''))
                            : 'Disponible';
                    @endphp

                    <div
                        class="boleta-item {{ $estado }} hidden"
                        data-bs-toggle="tooltip"
                        data-bs-html="true"
                        title="<strong>{{ $numero }}</strong><br>{{ $nombre }}"
                    >
                        {{ $numero }}
                    </div>
                @endfor
            </div>
        </div>

    </div>
</div>

<style>
.loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.85);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loader-box {
    background: #fff;
    padding: 30px;
    border-radius: 20px;
}

.boletas-scroll {
    max-height: 65vh;
    overflow-y: auto;
    padding-right: 6px;
}

.boletas-scroll::-webkit-scrollbar { width: 8px; }
.boletas-scroll::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 10px;
}

.boletas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(65px, 1fr));
    gap: 8px;
}

.boleta-item {
    padding: 10px;
    text-align: center;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s ease;
    border: 1px solid var(--border-soft);
    opacity: 0;
    transform: translateY(10px);
}

.boleta-item.show {
    opacity: 1;
    transform: translateY(0);
}

.boleta-item:hover { transform: scale(1.08); }

.boleta-item.disponible { background: #ecfdf5; color: #065f46; }
.boleta-item.ocupada    { background: #fee2e2; color: #991b1b; }
.boleta-item.ganadora   {
    background: #fef3c7;
    color: #92400e;
    box-shadow: 0 0 10px rgba(234,179,8,.5);
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const items  = document.querySelectorAll('.boleta-item');
    const loader = document.getElementById('loaderBoletas');

    let index = 0;
    const batchSize = 200;

    function renderBatch() {
        for (let i = 0; i < batchSize && index < items.length; i++, index++) {
            items[index].classList.add('show');
        }

        if (index < items.length) {
            requestAnimationFrame(renderBatch);
        } else {
            setTimeout(() => {
                loader.style.display = 'none';
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    .forEach(el => new bootstrap.Tooltip(el));
            }, 300);
        }
    }

    renderBatch();
});
</script>
@endpush