@extends('layouts.admin')

@php
    $title = 'Gestión de Premios';
    $subtitle = 'Administra los premios que estarán asociados a cada sorteo.';

    $premiosCollection = method_exists($premios, 'getCollection')
        ? $premios->getCollection()
        : collect($premios ?? []);

    $totalActivosPagina = $premiosCollection->where('activo', true)->count();
    $totalInactivosPagina = $premiosCollection->where('activo', false)->count();
    $totalConImagenPagina = $premiosCollection->filter(fn ($premio) => filled($premio->imagen))->count();
    $ultimoPremio = $premiosCollection->sortByDesc('created_at')->first();
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.premios.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i>
        Nuevo premio
    </a>
@endsection

@section('content')


{{-- FILTROS --}}
<div class="content-card card mb-4">

    <div class="card-body p-3">

        <form
            method="GET"
            action="{{ route('admin.premios.index') }}"
            class="row g-3 align-items-end"
        >

            <div class="col-lg-7 col-md-6">

                <label class="form-label small text-muted mb-1">
                    Buscar premio
                </label>

                <div class="premio-search-wrapper">

                    <div class="premio-search-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="form-control premio-search-input"
                        placeholder="Buscar por premio, descripción o sorteo..."
                    >

                </div>

            </div>

            <div class="col-lg-2 col-md-3">

                <label class="form-label small text-muted mb-1">
                    Registros
                </label>

                <select
                    name="per_page"
                    class="form-select"
                    onchange="this.form.submit()"
                >
                    <option value="10"  {{ (int)($perPage ?? 10) === 10 ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ (int)($perPage ?? 10) === 25 ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ (int)($perPage ?? 10) === 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (int)($perPage ?? 10) === 100 ? 'selected' : '' }}>100</option>
                </select>

            </div>

            <div class="col-lg-3 col-md-3">

                <label class="form-label small text-muted mb-1 d-block">
                    Acción
                </label>

                <div class="d-flex gap-2">

                    <button class="btn btn-outline-primary rounded-pill w-100" type="submit">
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>

                    <a href="{{ route('admin.premios.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-plus-lg"></i>
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

{{-- LISTADO --}}
<div class="content-card card">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Listado de premios
            </h5>

            <small class="text-muted">
                Consulta y administra todos los premios registrados.
            </small>
        </div>

        <a href="{{ route('admin.premios.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i>
            Nuevo premio
        </a>

    </div>

    <div class="card-body">

        @if($premios->count())

            <div class="table-responsive">

                <table class="table align-middle premios-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Premio</th>
                            <th>Sorteo</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($premios as $premio)

                        @php
                            $estadoClass = $premio->activo
                                ? 'bg-success-subtle text-success'
                                : 'bg-secondary-subtle text-secondary';
                        @endphp

                        <tr>

                            <td>
                                <span class="id-badge">
                                    #{{ $premio->id }}
                                </span>
                            </td>

                            <td>
                                @if($premio->imagen)
                                    <img
                                        src="{{ asset('storage/' . $premio->imagen) }}"
                                        alt="{{ $premio->titulo }}"
                                        class="premio-image preview-image"
                                        data-image="{{ asset('storage/' . $premio->imagen) }}"
                                        data-title="{{ $premio->titulo }}"
                                    >
                                @else
                                    <div class="premio-image-empty">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="premio-avatar">
                                        <i class="bi bi-gift"></i>
                                    </div>

                                    <div style="min-width: 0;">
                                        <div class="fw-semibold text-truncate">
                                            {{ $premio->titulo }}
                                        </div>

                                        @if($premio->descripcion)
                                            <div class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit($premio->descripcion, 70) }}
                                            </div>
                                        @else
                                            <div class="text-muted small">
                                                Sin descripción
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            <td>
                                @if($premio->sorteo)
                                    <span class="sorteo-badge">
                                        <i class="bi bi-calendar2-event me-1"></i>
                                        {{ \Illuminate\Support\Str::limit($premio->sorteo->nombre, 35) }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        Sin sorteo
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="orden-badge">
                                    {{ $premio->orden }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $estadoClass }} rounded-pill px-3 py-2">
                                    @if($premio->activo)
                                        <i class="bi bi-check-circle me-1"></i>
                                        Activo
                                    @else
                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactivo
                                    @endif
                                </span>
                            </td>

                            <td class="text-end">

                                <div class="actions-grid">

                                    <a
                                        href="{{ route('admin.premios.show', $premio) }}"
                                        class="btn btn-sm btn-light rounded-pill"
                                        title="Ver premio"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Ver
                                    </a>

                                    <a
                                        href="{{ route('admin.premios.edit', $premio) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill"
                                        title="Editar premio"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Editar
                                    </a>

                                    <form
                                        action="{{ route('admin.premios.destroy', $premio) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este premio?')"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill"
                                            title="Eliminar premio"
                                        >
                                            <i class="bi bi-trash me-1"></i>
                                            Eliminar
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
                    Mostrando
                    {{ $premios->firstItem() }}
                    a
                    {{ $premios->lastItem() }}
                    de
                    {{ $premios->total() }}
                    registros
                </div>

                <div>
                    {{ $premios->links() }}
                </div>

            </div>

        @else

            <div class="empty-state text-center py-5">

                <div class="empty-icon mx-auto mb-3">
                    <i class="bi bi-gift"></i>
                </div>

                <h5 class="fw-bold">
                    No hay premios registrados
                </h5>

                <p class="text-muted mb-4">
                    Empieza creando el primer premio del sistema.
                </p>

                <a href="{{ route('admin.premios.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    Crear premio
                </a>

            </div>

        @endif

    </div>

</div>

<style>
    .premios-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .premios-hero-icon {
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

    .mini-stat-name {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.2;
    }

    .mini-stat-sub {
        color: #6c757d;
        font-size: 13px;
        margin-top: 4px;
    }

    .premio-search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        transition: all .18s ease;
        overflow: hidden;
    }

    .premio-search-wrapper:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .09);
    }

    .premio-search-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .premio-search-icon {
        width: 52px;
        height: 52px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .premio-search-input {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: .95rem 1rem !important;
        font-weight: 600;
        color: #111827;
        background-color: transparent;
        min-height: 52px;
    }

    .premio-search-input:focus {
        box-shadow: none !important;
    }

    .premios-table tbody tr {
        transition: background .15s ease;
    }

    .premios-table tbody tr:hover {
        background: #f8fbff;
    }

    .id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 800;
        font-size: .85rem;
    }

    .premio-image {
        width: 78px;
        height: 54px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .08);
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .premio-image:hover {
        transform: scale(1.04);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .14);
    }

    .premio-image-empty {
        width: 78px;
        height: 54px;
        border-radius: 14px;
        background: #f1f5f9;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .premio-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex: 0 0 auto;
        box-shadow: 0 10px 22px rgba(37, 99, 235, .20);
    }

    .sorteo-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: .85rem;
        max-width: 260px;
    }

    .orden-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 800;
    }

    .actions-grid {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        min-width: 240px;
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

    @media (max-width: 992px) {
        .actions-grid {
            min-width: 0;
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .premios-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .premio-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
        }
    }
</style>

@endsection