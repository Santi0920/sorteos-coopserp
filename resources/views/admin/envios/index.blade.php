@extends('layouts.admin')

@php
    $title = 'Gestión de Envíos';
    $subtitle = 'Envía boletas por correo y registra envíos pendientes por WhatsApp.';
@endphp

@section('topbar_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emailModal">
        <i class="bi bi-envelope me-1"></i> Enviar correo
    </button>
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="content-card card">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">Correo electrónico</h5>
                    <small class="text-muted">Envía al asociado las boletas del sorteo seleccionado.</small>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#emailModal">
                        <i class="bi bi-envelope-paper me-1"></i> Preparar envío por correo
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card card">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">WhatsApp</h5>
                    <small class="text-muted">Registra el envío como pendiente para futura integración real.</small>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                        <i class="bi bi-whatsapp me-1"></i> Registrar envío WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Historial de envíos</h5>
                <small class="text-muted">Consulta los registros de correo y WhatsApp.</small>
            </div>

            <form method="GET" action="{{ route('admin.envios.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Buscar por canal, destino, asociado o sorteo"
                        style="min-width: 290px;"
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
            @if($envios->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Canal</th>
                                <th>Destino</th>
                                <th>Asociado</th>
                                <th>Sorteo</th>
                                <th>Boleta</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($envios as $envio)
                                <tr>
                                    <td>{{ $envio->id }}</td>
                                    <td class="text-capitalize">{{ $envio->canal }}</td>
                                    <td>{{ $envio->destino ?: '—' }}</td>
                                    <td>{{ $envio->boleta?->asociado?->nombre_completo ?? '—' }}</td>
                                    <td>{{ $envio->boleta?->sorteo?->nombre ?? '—' }}</td>
                                    <td>{{ $envio->boleta?->numero_boleta ?? '—' }}</td>
                                    <td>
                                        @if($envio->estado === 'enviado')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Enviado</span>
                                        @elseif($envio->estado === 'pendiente')
                                            <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-2">Pendiente</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Fallido</span>
                                        @endif
                                    </td>
                                    <td>{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <div class="text-muted small">
                        Mostrando {{ $envios->firstItem() }} a {{ $envios->lastItem() }} de {{ $envios->total() }} registros
                    </div>

                    <div>
                        {{ $envios->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-send fs-1 text-muted"></i>
                    <h5 class="mt-3 fw-bold">No hay envíos registrados</h5>
                    <p class="text-muted mb-0">Cuando realices envíos o registros, aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Enviar boletas por correo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form action="{{ route('admin.envios.email') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Sorteo</label>
                            <select name="sorteo_id" class="form-select" required>
                                <option value="">Selecciona un sorteo</option>
                                @foreach($sorteos as $sorteo)
                                    <option value="{{ $sorteo->id }}">{{ $sorteo->nombre }} - {{ $sorteo->fecha_sorteo->format('d/m/Y') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asociado</label>
                            <select name="asociado_id" class="form-select" required>
                                <option value="">Selecciona un asociado</option>
                                @foreach($asociados as $asociado)
                                    <option value="{{ $asociado->id }}">
                                        {{ $asociado->nombre_completo }} - {{ $asociado->documento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-envelope me-1"></i> Enviar correo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="whatsappModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar envío por WhatsApp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form action="{{ route('admin.envios.whatsapp') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Sorteo</label>
                            <select name="sorteo_id" class="form-select" required>
                                <option value="">Selecciona un sorteo</option>
                                @foreach($sorteos as $sorteo)
                                    <option value="{{ $sorteo->id }}">{{ $sorteo->nombre }} - {{ $sorteo->fecha_sorteo->format('d/m/Y') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asociado</label>
                            <select name="asociado_id" class="form-select" required>
                                <option value="">Selecciona un asociado</option>
                                @foreach($asociados as $asociado)
                                    <option value="{{ $asociado->id }}">
                                        {{ $asociado->nombre_completo }} - {{ $asociado->documento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-0">
                            Este registro quedará como pendiente hasta integrar un proveedor real de WhatsApp.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-whatsapp me-1"></i> Registrar envío
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection