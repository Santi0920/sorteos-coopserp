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

                                        <form action="{{ route('admin.sorteos.destroy', $sorteo->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este sorteo? Esta acción no se puede deshacer.');"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>

                                        </form>

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



@endsection