@extends('layouts.admin')

@php
    $title = 'Gestión de Sorteos';
    $subtitle = 'Administra sorteos, participantes y boletas desde un solo lugar.';

    $sorteosCollection = method_exists($sorteos, 'getCollection')
        ? $sorteos->getCollection()
        : collect($sorteos ?? []);

    $sorteoIds = $sorteosCollection->pluck('id')->filter()->values();

    $ultimoSorteo = $sorteosCollection->sortByDesc('created_at')->first();

    /*
    |--------------------------------------------------------------------------
    | Conteos optimizados para no consultar dentro de cada fila
    |--------------------------------------------------------------------------
    */
    $boletasPorSorteo = $sorteoIds->count()
        ? \App\Models\Boleta::whereIn('sorteo_id', $sorteoIds)
            ->selectRaw('sorteo_id, COUNT(*) as total')
            ->groupBy('sorteo_id')
            ->pluck('total', 'sorteo_id')
        : collect();

    $participantesPorSorteo = $sorteoIds->count()
        ? \Illuminate\Support\Facades\DB::table('sorteo_asociado')
            ->whereIn('sorteo_id', $sorteoIds)
            ->selectRaw('sorteo_id, COUNT(*) as total')
            ->groupBy('sorteo_id')
            ->pluck('total', 'sorteo_id')
        : collect();

    $boletasConfiguradasPorSorteo = $sorteoIds->count()
        ? \Illuminate\Support\Facades\DB::table('sorteo_asociado')
            ->whereIn('sorteo_id', $sorteoIds)
            ->selectRaw('sorteo_id, COALESCE(SUM(boletas_por_persona), 0) as total')
            ->groupBy('sorteo_id')
            ->pluck('total', 'sorteo_id')
        : collect();

    $totalBoletasGeneradasPagina = $boletasPorSorteo->sum();
    $totalParticipantesPagina = $participantesPorSorteo->sum();
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i>
        Nuevo sorteo
    </a>
@endsection

@section('content')

{{-- PANEL PRINCIPAL --}}


{{-- FILTROS --}}
<div class="content-card card mb-4">

    <div class="card-body p-3">

        <form
            method="GET"
            action="{{ route('admin.sorteos.index') }}"
            class="row g-3 align-items-end"
        >

            <div class="col-lg-8 col-md-7">

                <label class="form-label small text-muted mb-1">
                    Buscar sorteo
                </label>

                <div class="sorteo-search-wrapper">

                    <div class="sorteo-search-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="form-control sorteo-search-input"
                        placeholder="Buscar por nombre, lotería, estado u observación..."
                    >

                </div>

            </div>

            <div class="col-lg-2 col-md-3">

                <label class="form-label small text-muted mb-1 d-block">
                    Acción
                </label>

                <button class="btn btn-outline-primary rounded-pill w-100" type="submit">
                    <i class="bi bi-search me-1"></i>
                    Buscar
                </button>

            </div>


        </form>

    </div>

</div>

{{-- LISTADO --}}
<div class="content-card card">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Sorteos del sistema
            </h5>

            <small class="text-muted">
                Accede a participantes, importación, diseño, boletas y mapa desde cada sorteo.
            </small>
        </div>

    </div>

    <div class="card-body">

        @if($sorteos->count())

            <div class="table-responsive">

                <table class="table align-middle sorteos-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sorteo</th>
                            <th>Fecha</th>
                            <th>Lotería</th>
                            <th>Estado</th>
                            <th>Participantes</th>
                            <th>Boletas</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($sorteos as $sorteo)

                        @php
                            $totalBoletas = (int) ($boletasPorSorteo[$sorteo->id] ?? 0);
                            $totalParticipantes = (int) ($participantesPorSorteo[$sorteo->id] ?? 0);
                            $totalConfiguradas = (int) ($boletasConfiguradasPorSorteo[$sorteo->id] ?? 0);

                            $estado = strtolower($sorteo->estado ?? 'sin estado');

                            $estadoClass = match($estado) {
                                'activo', 'programado' => 'bg-primary-subtle text-primary',
                                'ejecutado', 'finalizado' => 'bg-success-subtle text-success',
                                'cancelado', 'inactivo' => 'bg-danger-subtle text-danger',
                                default => 'bg-light text-dark',
                            };
                        @endphp

                        <tr>

                            <td>
                                <span class="id-badge">
                                    #{{ $sorteo->id }}
                                </span>
                            </td>

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="sorteo-avatar">
                                        <i class="bi bi-calendar2-event"></i>
                                    </div>

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $sorteo->nombre }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ \Illuminate\Support\Str::limit($sorteo->observaciones ?: 'Sin observaciones', 55) }}
                                        </div>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <div class="fw-semibold small">
                                    {{ $sorteo->fecha_sorteo ? $sorteo->fecha_sorteo->format('d/m/Y') : '—' }}
                                </div>

                                <div class="text-muted small">
                                    Fecha sorteo
                                </div>
                            </td>

                            <td>
                                <span class="loteria-badge">
                                    {{ $sorteo->loteria ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $estadoClass }} rounded-pill px-3 py-2">
                                    {{ ucfirst($sorteo->estado ?? 'Sin estado') }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="participants-badge">
                                        <i class="bi bi-people me-1"></i>
                                        {{ $totalParticipantes }}
                                    </span>

                                    <small class="text-muted">
                                        Boletas config: {{ $totalConfiguradas }}
                                    </small>
                                </div>
                            </td>

                            <td>
                                @if($totalBoletas > 0)
                                    <span class="ticket-badge">
                                        <i class="bi bi-ticket-perforated me-1"></i>
                                        {{ $totalBoletas }} boletas
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        Sin generar
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">

                                <div class="actions-grid">

                                    <a href="{{ route('admin.sorteos.edit', $sorteo->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Editar
                                    </a>

                                    <a href="{{ route('admin.sorteos.import.form', $sorteo->id) }}"
                                       class="btn btn-sm btn-success rounded-pill">
                                        <i class="bi bi-cloud-arrow-up me-1"></i>
                                        Importar
                                    </a>

                                    <a href="{{ route('admin.boleta.design.edit', $sorteo->id) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="bi bi-palette me-1"></i>
                                        Diseño
                                    </a>

                                    <a href="{{ route('admin.asociados.index', ['sorteo_id' => $sorteo->id]) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-people me-1"></i>
                                        Participantes
                                    </a>

                                    <a href="{{ route('admin.boletas.index', ['sorteo_id' => $sorteo->id]) }}"
                                       class="btn btn-sm btn-outline-dark rounded-pill">
                                        <i class="bi bi-ticket-perforated me-1"></i>
                                        Boletas
                                    </a>

                                    <a href="{{ route('admin.boletas.mapa', $sorteo->id) }}"
                                       class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-grid-3x3-gap me-1"></i>
                                        Mapa
                                    </a>

                                    <form
                                        action="{{ route('admin.sorteos.destroy', $sorteo->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este sorteo? Esta acción no se puede deshacer.');"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
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
                    {{ $sorteos->firstItem() }}
                    a
                    {{ $sorteos->lastItem() }}
                    de
                    {{ $sorteos->total() }}
                    registros
                </div>

                <div>
                    {{ $sorteos->links() }}
                </div>

            </div>

        @else

            <div class="empty-state text-center py-5">

                <div class="empty-icon mx-auto mb-3">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <h5 class="fw-bold">
                    No hay sorteos
                </h5>

                <p class="text-muted">
                    Crea tu primer sorteo para comenzar.
                </p>

                <a href="{{ route('admin.sorteos.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    Crear sorteo
                </a>

            </div>

        @endif

    </div>

</div>

<style>
    .sorteos-hero {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .12), transparent 35%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .sorteos-hero-icon {
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

    .sorteo-search-wrapper {
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

    .sorteo-search-wrapper:hover {
        border-color: #bfdbfe;
        box-shadow: 0 16px 34px rgba(37, 99, 235, .09);
    }

    .sorteo-search-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .sorteo-search-icon {
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

    .sorteo-search-input {
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: .95rem 1rem !important;
        font-weight: 600;
        color: #111827;
        background-color: transparent;
        min-height: 52px;
    }

    .sorteo-search-input:focus {
        box-shadow: none !important;
    }

    .sorteos-table tbody tr {
        transition: background .15s ease;
    }

    .sorteos-table tbody tr:hover {
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

    .sorteo-avatar {
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

    .loteria-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: .85rem;
    }

    .participants-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 800;
        font-size: .85rem;
    }

    .ticket-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 12px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-weight: 800;
        font-size: .85rem;
    }

    .actions-grid {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        min-width: 420px;
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
        .sorteos-hero-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .sorteo-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
        }
    }
</style>

@endsection