@extends('layouts.admin')

@php
    $title = 'Gestión de Ganadores';
    $subtitle = 'Asigna una boleta ganadora a cada premio del sorteo.';
@endphp

@section('content')
    <div class="content-card card mb-4">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Seleccionar sorteo</h5>
            <small class="text-muted">Elige un sorteo para asignar sus premios a boletas ganadoras.</small>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ganadores.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-8">
                    <label class="form-label">Sorteo</label>
                    <select name="sorteo_id" class="form-select" required>
                        <option value="">Selecciona un sorteo</option>
                        @foreach($sorteos as $sorteo)
                            <option value="{{ $sorteo->id }}"
                                {{ (string) request('sorteo_id') === (string) $sorteo->id ? 'selected' : '' }}>
                                {{ $sorteo->nombre }} - {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Consultar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($sorteoSeleccionado)
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="content-card card h-100">
                    <div class="card-header">
                        <h5 class="mb-1 fw-bold">Sorteo seleccionado</h5>
                        <small class="text-muted">Información general.</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small">Nombre</div>
                            <div class="fw-semibold">{{ $sorteoSeleccionado->nombre }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Fecha</div>
                            <div class="fw-semibold">{{ $sorteoSeleccionado->fecha_sorteo->format('d/m/Y') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Estado</div>
                            <div class="fw-semibold">{{ ucfirst($sorteoSeleccionado->estado) }}</div>
                        </div>

                        <div>
                            <div class="text-muted small">Total boletas</div>
                            <div class="fw-semibold">{{ $boletas->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="content-card card h-100">
                    <div class="card-header">
                        <h5 class="mb-1 fw-bold">Resumen de premios</h5>
                        <small class="text-muted">Cada premio puede tener una boleta distinta como ganadora.</small>
                    </div>
                    <div class="card-body">
                        @if($premios->count())
                            <div class="row g-3">
                                @foreach($premios as $premio)
                                    <div class="col-md-6">
                                        <div class="border rounded-4 p-3 h-100">
                                            <div class="fw-semibold">{{ $premio->titulo }}</div>
                                            <div class="text-muted small mb-2">Orden: {{ $premio->orden }}</div>

                                            @if($premio->boletaGanadora)
                                                <div class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-2">
                                                    {{ $premio->boletaGanadora->numero_boleta }}
                                                </div>
                                                <div class="small">
                                                    {{ $premio->boletaGanadora->asociado?->nombre_completo ?? '—' }}
                                                </div>
                                            @else
                                                <div class="text-muted small">Sin boleta asignada</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-gift fs-1 text-muted"></i>
                                <h6 class="fw-bold mt-3">No hay premios creados para este sorteo</h6>
                                <p class="text-muted mb-0">Primero crea los premios del sorteo.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card card">
            <div class="card-header">
                <h5 class="mb-1 fw-bold">Asignación de premios a boletas</h5>
                <small class="text-muted">Selecciona la boleta ganadora de cada premio.</small>
            </div>
            <div class="card-body">
                @if($premios->count() && $boletas->count())
                    <div class="row g-4">
                        @foreach($premios as $premio)
                            <div class="col-12">
                                <div class="border rounded-4 p-4">
                                    <div class="row g-4 align-items-end">
                                        <div class="col-lg-4">
                                            <div class="fw-bold fs-5">{{ $premio->titulo }}</div>
                                            <div class="text-muted small mb-2">Orden: {{ $premio->orden }}</div>
                                            <div class="text-muted">
                                                {{ $premio->descripcion ?: 'Sin descripción.' }}
                                            </div>
                                        </div>

                                        <div class="col-lg-5">
                                            <form action="{{ route('admin.ganadores.asignarPremio') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="premio_id" value="{{ $premio->id }}">

                                                <label class="form-label">Boleta ganadora</label>
                                                <select name="boleta_id" class="form-select" required>
                                                    <option value="">Selecciona una boleta</option>
                                                    @foreach($boletas as $boleta)
                                                        <option value="{{ $boleta->id }}"
                                                            {{ (int) old('boleta_id', $premio->boleta_ganadora_id) === (int) $boleta->id ? 'selected' : '' }}>
                                                            {{ $boleta->numero_boleta }} - {{ $boleta->asociado?->nombre_completo ?? '—' }} - {{ $boleta->asociado?->documento ?? '—' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </div>

                                        <div class="col-lg-3">
                                                <button class="btn btn-success w-100" type="submit">
                                                    <i class="bi bi-check2-circle me-1"></i> Guardar asignación
                                                </button>
                                            </form>

                                            @if($premio->boleta_ganadora_id)
                                                <form action="{{ route('admin.ganadores.limpiarPremio', $premio) }}" method="POST" class="mt-2" onsubmit="return confirm('¿Seguro que deseas quitar la boleta asignada a este premio?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger w-100" type="submit">
                                                        <i class="bi bi-eraser me-1"></i> Limpiar premio
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    @if($premio->boletaGanadora)
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="small text-muted mb-1">Asignación actual</div>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                    {{ $premio->boletaGanadora->numero_boleta }}
                                                </span>
                                                <span class="fw-semibold">
                                                    {{ $premio->boletaGanadora->asociado?->nombre_completo ?? '—' }}
                                                </span>
                                                <span class="text-muted small">
                                                    {{ $premio->boletaGanadora->asociado?->documento ?? '—' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(!$premios->count())
                    <div class="text-center py-5">
                        <i class="bi bi-gift fs-1 text-muted"></i>
                        <h5 class="mt-3 fw-bold">No hay premios para este sorteo</h5>
                        <p class="text-muted mb-0">Primero debes crear los premios.</p>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-ticket-perforated fs-1 text-muted"></i>
                        <h5 class="mt-3 fw-bold">No hay boletas generadas</h5>
                        <p class="text-muted mb-0">Primero debes generar boletas para este sorteo.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection