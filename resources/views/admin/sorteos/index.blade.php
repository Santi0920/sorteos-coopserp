@extends('layouts.admin')

@php
    $title = 'Gestión de Sorteos';
    $subtitle = 'Administra las fechas, loterías, estados y reprogramaciones.';
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
            </div>
        </div>
    </div>

    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Listado de sorteos</h5>
                <small class="text-muted">Consulta y administra todos los sorteos registrados.</small>
            </div>

            <form method="GET" action="{{ route('admin.sorteos.index') }}" class="d-flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Buscar por nombre, lotería o estado"
                    style="min-width: 280px;"
                >
                <button class="btn btn-outline-primary" type="submit">
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
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Lotería</th>
                                <th>Estado</th>
                                <th>Reprogramado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sorteos as $sorteo)
                                <tr>
                                    <td>{{ $sorteo->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $sorteo->nombre }}</div>
                                        @if($sorteo->observaciones)
                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($sorteo->observaciones, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $sorteo->fecha_sorteo->format('d/m/Y') }}</td>
                                    <td>{{ $sorteo->loteria ?: '—' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($sorteo->estado) {
                                                'programado' => 'badge-programado',
                                                'ejecutado' => 'badge-ejecutado',
                                                'cancelado' => 'badge-cancelado',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge-soft {{ $badgeClass }}">
                                            {{ ucfirst($sorteo->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($sorteo->es_reprogramado)
                                            <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-2">Sí</span>
                                        @else
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">No</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.sorteos.show', $sorteo) }}" class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sorteos.edit', $sorteo) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.sorteos.destroy', $sorteo) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este sorteo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $sorteos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar2-x fs-1 text-muted"></i>
                    <h5 class="mt-3 fw-bold">No hay sorteos registrados</h5>
                    <p class="text-muted">Empieza creando tu primer sorteo.</p>
                    <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary">
                        Crear sorteo
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection