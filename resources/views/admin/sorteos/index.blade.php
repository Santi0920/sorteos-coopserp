@extends('layouts.admin')

@php
    $title = 'Gestión de Sorteos';
    $subtitle = 'Administra sorteos, participantes y boletas desde un solo lugar.';

    $lastImport = \App\Models\Import::latest()->first();
    $importErrors = $lastImport->errors ?? [];
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo sorteo
    </a>
@endsection

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="stats-box">
            <p>Total sorteos</p>
            <h3>{{ $sorteos->total() }}</h3>
            <small class="text-white">Registrados en el sistema</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stats-box">
            <p>Último sorteo creado</p>
            <h3>{{ $sorteos->sortByDesc('created_at')->first()->nombre ?? '—' }}</h3>
            <small class="text-white">
                {{ optional($sorteos->sortByDesc('created_at')->first())->fecha_sorteo?->format('d/m/Y') }}
            </small>
        </div>
    </div>

</div>

<div class="content-card card">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h5 class="mb-1 fw-bold">Sorteos del sistema</h5>
            <small class="text-muted">Accede a participantes, importación y boletas desde cada sorteo</small>
        </div>

        <form method="GET" action="{{ route('admin.sorteos.index') }}" class="d-flex gap-2">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   class="form-control"
                   placeholder="Buscar sorteo..."
                   style="min-width: 250px;">
            <button class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>

    </div>

    <div class="card-body">

        @if($sorteos->count())

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sorteo</th>
                            <th>Fecha</th>
                            <th>Lotería</th>
                            <th>Estado</th>
                            <th>Boletas</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($sorteos as $sorteo)

                            <tr>

                                <td class="fw-bold">{{ $sorteo->id }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $sorteo->nombre }}</div>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($sorteo->observaciones, 50) }}
                                    </small>
                                </td>

                                <td>{{ $sorteo->fecha_sorteo->format('d/m/Y') }}</td>

                                <td>{{ $sorteo->loteria ?? '—' }}</td>

                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        {{ ucfirst($sorteo->estado) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2">
                                        {{ \App\Models\Boleta::where('sorteo_id', $sorteo->id)->count() }} boletas
                                    </span>
                                </td>

                                <td class="text-end">

                                    <div class="d-flex justify-content-end flex-wrap gap-2">

                                        <a href="{{ route('admin.sorteos.edit', $sorteo->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>

                                        <a href="{{ route('admin.sorteos.import.form', $sorteo->id) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-cloud-arrow-up"></i> Importar
                                        </a>

                                        <a href="{{ route('admin.boleta.design.edit', $sorteo->id) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-palette"></i> Diseño
                                        </a>

                                        <a href="{{ route('admin.asociados.index', ['sorteo_id' => $sorteo->id]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-people"></i> Participantes
                                        </a>

                                        <a href="{{ route('admin.boletas.index', ['sorteo_id' => $sorteo->id]) }}"
                                           class="btn btn-sm btn-outline-dark">
                                            <i class="bi bi-ticket-perforated"></i> Boletas
                                        </a>

                                        <a href="{{ route('admin.boletas.mapa', $sorteo->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-grid-3x3-gap"></i> Mapa
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">

                <small class="text-muted">
                    Mostrando {{ $sorteos->firstItem() }} a {{ $sorteos->lastItem() }}
                    de {{ $sorteos->total() }}
                </small>

                {{ $sorteos->links() }}

            </div>

        @else

            <div class="text-center py-5">

                <i class="bi bi-calendar-x fs-1 text-muted"></i>

                <h5 class="mt-3 fw-bold">No hay sorteos</h5>

                <p class="text-muted">Crea tu primer sorteo para comenzar</p>

                <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary">
                    Crear sorteo
                </a>

            </div>

        @endif

    </div>

</div>

{{-- ========================= --}}
{{-- 🚨 MODAL PROFESIONAL --}}
{{-- ========================= --}}

@if($lastImport && count($importErrors) > 0)

<div class="modal fade" id="importErrorsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- HEADER --}}
            <div class="modal-header border-0 bg-light rounded-top-4 px-4 py-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <div>
                        <h5 class="mb-0 fw-bold">
                            Errores de importación
                        </h5>
                        <small class="text-muted">
                            Se encontraron problemas en la última importación
                        </small>
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 py-3">

                <div class="mb-3">
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                        {{ count($importErrors) }} errores detectados
                    </span>
                </div>

                <div class="table-responsive">

                    <table class="table table-sm align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="width:100px;">Fila</th>
                                <th>Error</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($importErrors as $error)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-dark px-3 py-2 rounded-pill">
                                            {{ $error['row'] }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $error['error'] }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 py-3">

                <button type="button"
                        class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>

            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('importErrorsModal'));
    modal.show();
});
</script>

@endif

@endsection