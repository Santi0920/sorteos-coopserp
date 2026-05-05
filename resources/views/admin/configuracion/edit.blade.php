@extends('layouts.admin')

@php
    $title = 'Configuración del Sistema';
    $subtitle = 'Administra parámetros, módulos y reglas del sistema de sorteos.';
@endphp

@section('topbar_actions')
    <button form="configForm" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Guardar cambios
    </button>
@endsection

@section('content')

<form id="configForm" action="{{ route('admin.configuracion.update') }}" method="POST">
@csrf
@method('PUT')

<div class="row g-4">

    {{-- ================= RESUMEN GENERAL ================= --}}
    <div class="col-12">
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card p-3 shadow-sm border-0 rounded-4">
                    <small class="text-muted">Monto por boleta</small>
                    <h4 class="fw-bold mb-0">
                        ${{ number_format((float)($config['monto_por_boleta'] ?? 0), 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm border-0 rounded-4">
                    <small class="text-muted">Líneas activas</small>
                    <h4 class="fw-bold mb-0">{{ $lineasActivas }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm border-0 rounded-4">
                    <small class="text-muted">Líneas participando</small>
                    <h4 class="fw-bold mb-0">{{ $lineasParticipando }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm border-0 rounded-4">
                    <small class="text-muted">Premios activos</small>
                    <h4 class="fw-bold mb-0">{{ $premiosActivos }}</h4>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= BOLETAS ================= --}}
    <div class="col-lg-6">
        <div class="content-card card h-100">
            <div class="card-header">
                <h5 class="fw-bold mb-1">Configuración de Boletas</h5>
                <small class="text-muted">Reglas de asignación</small>
            </div>

            <div class="card-body">

                <label class="form-label">Monto por boleta</label>
                <input id="monto_display" class="form-control mb-3" placeholder="$ 0">

                <input type="hidden" name="monto_por_boleta" id="monto_real"
                    value="{{ $config['monto_por_boleta'] ?? '' }}">

                <div class="alert alert-info mt-3">
                    💡 Por cada monto configurado se genera automáticamente una boleta.
                </div>

            </div>
        </div>
    </div>

    {{-- ================= LINEAS ================= --}}
    <div class="col-lg-6">
        <div class="content-card card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Líneas de Crédito</h5>
                    <small class="text-muted">Gestión del módulo</small>
                </div>

                <a href="{{ route('admin.lineas.index') }}" class="btn btn-sm btn-outline-primary">
                    Administrar
                </a>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Total líneas</span>
                    <strong>{{ $lineas }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Activas</span>
                    <strong class="text-success">{{ $lineasActivas }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Participando</span>
                    <strong class="text-primary">{{ $lineasParticipando }}</strong>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= PREMIOS ================= --}}
    <div class="col-lg-6">
        <div class="content-card card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Premios</h5>
                    <small class="text-muted">Gestión de premios</small>
                </div>

                <a href="{{ route('admin.premios.index') }}" class="btn btn-sm btn-outline-primary">
                    Administrar
                </a>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Total premios</span>
                    <strong>{{ $premios }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Activos</span>
                    <strong class="text-success">{{ $premiosActivos }}</strong>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= TEXTO ================= --}}
    <div class="col-lg-6">
        <div class="content-card card h-100">
            <div class="card-header">
                <h5 class="fw-bold mb-1">Texto Promocional</h5>
                <small class="text-muted">Mensaje público</small>
            </div>

            <div class="card-body">

                <textarea
                    name="texto_promocional"
                    rows="6"
                    class="form-control"
                >{{ $config['texto_promocional'] ?? '' }}</textarea>

            </div>
        </div>
    </div>

</div>
</form>

{{-- SCRIPT MONTO --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const display = document.getElementById('monto_display');
    const real = document.getElementById('monto_real');

    if (real.value) {
        display.value = '$ ' + parseInt(real.value).toLocaleString('es-CO');
    }

    display.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        this.value = raw ? '$ ' + parseInt(raw).toLocaleString('es-CO') : '';
        real.value = raw;
    });
});
</script>

@endsection