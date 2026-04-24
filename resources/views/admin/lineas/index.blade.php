@extends('layouts.admin')

@php
    $title = 'Gestión de Líneas';
    $subtitle = 'Administra las líneas de crédito que participan en los sorteos.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.lineas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva línea
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Listado de líneas</h5>
                <small class="text-muted">Consulta y administra las líneas de crédito.</small>
            </div>

            <form method="GET" action="{{ route('admin.lineas.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Buscar por código o nombre"
                        style="min-width: 260px;"
                    >
                </div>

                <div class="col-auto">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="10" {{ (int)$perPage === 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ (int)$perPage === 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ (int)$perPage === 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ (int)$perPage === 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($lineas->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Participa</th>
                                <th>Activo</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lineas as $linea)
                                <tr>
                                    <td>{{ $linea->id }}</td>
                                    <td><span class="fw-semibold">{{ $linea->codigo }}</span></td>
                                    <td>{{ $linea->nombre }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($linea->descripcion, 50) ?: '—' }}</td>
                                    <td>
                                        @if($linea->participa_sorteo)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Sí</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($linea->activo)
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Activo</span>
                                        @else
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.lineas.show', $linea) }}" class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.lineas.edit', $linea) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.lineas.destroy', $linea) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta línea?')">
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

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <div class="text-muted small">
                        Mostrando {{ $lineas->firstItem() }} a {{ $lineas->lastItem() }} de {{ $lineas->total() }} registros
                    </div>

                    <div>
                        {{ $lineas->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-diagram-3 fs-1 text-muted"></i>
                    <h5 class="mt-3 fw-bold">No hay líneas registradas</h5>
                    <p class="text-muted">Empieza creando una línea de crédito.</p>
                    <a href="{{ route('admin.lineas.create') }}" class="btn btn-primary">
                        Crear línea
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection