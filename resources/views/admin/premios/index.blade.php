@extends('layouts.admin')

@php
    $title = 'Gestión de Premios';
    $subtitle = 'Administra los premios que estarán asociados a cada sorteo.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.premios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo premio
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Listado de premios</h5>
                <small class="text-muted">Consulta y administra todos los premios registrados.</small>
            </div>

            <form method="GET" action="{{ route('admin.premios.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Buscar premio o sorteo"
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
            @if($premios->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Sorteo</th>
                                <th>Orden</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($premios as $premio)
                                <tr>
                                    <td>{{ $premio->id }}</td>
                                                                            <td>
                                        @if($premio->imagen)
                                            <img
                                                src="{{ asset('storage/' . $premio->imagen) }}"
                                                alt="{{ $premio->titulo }}"
                                                class="preview-image"
                                                data-image="{{ asset('storage/' . $premio->imagen) }}"
                                                data-title="{{ $premio->titulo }}"
                                                style="width: 70px; height: 50px; object-fit: cover; border-radius: 12px; border: 1px solid #e5e7eb; cursor:pointer;"
                                            >
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                                 style="width: 70px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $premio->titulo }}</div>
                                        @if($premio->descripcion)
                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($premio->descripcion, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $premio->sorteo?->nombre ?? '—' }}</td>
                                    <td>{{ $premio->orden }}</td>
                                    <td>
                                        @if($premio->activo)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Activo</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.premios.show', $premio) }}" class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.premios.edit', $premio) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.premios.destroy', $premio) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este premio?')">
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
                        Mostrando {{ $premios->firstItem() }} a {{ $premios->lastItem() }} de {{ $premios->total() }} registros
                    </div>

                    <div>
                        {{ $premios->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-gift fs-1 text-muted"></i>
                    <h5 class="mt-3 fw-bold">No hay premios registrados</h5>
                    <p class="text-muted">Empieza creando el primer premio del sistema.</p>
                    <a href="{{ route('admin.premios.create') }}" class="btn btn-primary">
                        Crear premio
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection