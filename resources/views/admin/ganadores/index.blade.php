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
        <!-- RESULTADO LOTERÍA + SOPORTE -->
        <div class="content-card card mb-4">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Resultado de lotería</h5>
                <small class="text-muted">Registra número ganador y evidencia del sorteo</small>
            </div>

            <div class="card-body">

                <!-- FORMULARIO -->
                <form method="POST"
                    action="{{ route('admin.ganadores.guardar') }}"
                    enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="sorteo_id" value="{{ $sorteoSeleccionado->id ?? '' }}">

                    <div class="row g-3">

                        <!-- NÚMERO GANADOR -->
                        <div class="col-lg-4">
                            <label class="form-label">Número ganador</label>
                            <input type="text"
                                name="numero_resultado"
                                id="numeroGanador"
                                class="form-control"
                                placeholder="Ej: 0123" value="{{ $sorteoSeleccionado?->numero_resultado }}">    
                        </div>

                        <!-- BOTÓN GUARDAR -->
                        <div class="col-lg-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i> Guardar resultado
                            </button>
                        </div>

                    </div>

                    <!-- INPUT SOPORTE (SOLO SI NO EXISTE) -->
                    @if(!$sorteoSeleccionado?->soporte_resultado)
                        <div class="mt-3">
                            <label class="form-label">Soporte del resultado</label>
                            <input type="file" name="soporte_resultado" class="form-control">
                        </div>
                    @endif

                </form>

                <!-- SOPORTE EXISTENTE -->
                @if($sorteoSeleccionado?->soporte_resultado)
                    @php
                        $file = $sorteoSeleccionado->soporte_resultado;
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $url = asset('storage/' . $file);
                    @endphp

                    <hr>

                    <div class="mt-3">
                        <div class="text-muted small mb-2">Soporte del resultado actual</div>

                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                            <img src="{{ $url }}"
                                class="img-fluid rounded-4 border"
                                style="max-height: 280px; cursor:pointer;"
                                onclick="openSoporteModal('{{ $url }}','image')">

                        @elseif($ext === 'pdf')
                            <div class="border rounded-4 overflow-hidden" style="height:300px;">
                                <iframe src="{{ $url }}" width="100%" height="100%"></iframe>
                            </div>

                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-file-earmark-pdf"></i> Abrir PDF
                            </a>

                        @else
                            <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                Descargar archivo
                            </a>
                        @endif
                    </div>
                @endif

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

@push('scripts')
<script>


function openSoporteModal(url, type) {

    const img = document.getElementById('soporteModalImg');
    const pdf = document.getElementById('soporteModalPdf');

    img.classList.add('d-none');
    pdf.classList.add('d-none');

    if (type === 'image') {
        img.src = url;
        img.classList.remove('d-none');
    } else {
        pdf.src = url;
        pdf.classList.remove('d-none');
    }

    new bootstrap.Modal(document.getElementById('soporteModal')).show();
}


document.getElementById('numeroGanador').addEventListener('input', function () {

    let numero = this.value.trim();

    const box = document.getElementById('resultadoAsociado');

    if (numero.length < 2) {
        box.innerHTML = '<span class="text-muted">Ingresa un número</span>';
        return;
    }

    fetch(`{{ url('/admin/boletas/lookup') }}/${numero}`)
        .then(res => res.json())
        .then(data => {

            if (!data.ok) {
                box.innerHTML = '<span class="text-danger">No encontrado</span>';
                return;
            }

            box.innerHTML = `
                <div class="fw-bold">${data.nombre}</div>
                <div class="text-muted small">${data.documento ?? ''}</div>
                ${data.agencia ? `<div class="text-muted small">Agencia: ${data.agencia}</div>` : ''}
            `;
        })
        .catch(() => {
            box.innerHTML = '<span class="text-danger">Error consultando</span>';
        });

});


</script>
@endpush

<!-- MODAL SOPORTE -->
<div class="modal fade" id="soporteModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title">Visualización de soporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <!-- IMAGE -->
                <img id="soporteModalImg"
                     class="img-fluid rounded-4 d-none"
                     style="max-height: 80vh;">

                <!-- PDF -->
                <iframe id="soporteModalPdf"
                        class="w-100 d-none"
                        style="height:80vh;"
                        frameborder="0"></iframe>

            </div>

        </div>
    </div>
</div>
@endsection
