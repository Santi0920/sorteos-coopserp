@extends('layouts.admin')

@php
    $title = 'Configuración General';
    $subtitle = 'Administra las reglas principales del sistema de sorteos.';
@endphp

@section('topbar_actions')
    <button form="configForm" type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Guardar cambios
    </button>
@endsection

@section('content')
    <form id="configForm" action="{{ route('admin.configuracion.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card card">
                    <div class="card-header">
                        <h5 class="mb-1 fw-bold">Parámetros de boletas</h5>
                        <small class="text-muted">Define cómo se calcularán y numerarán las boletas.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Monto por boleta</label>
                                <input 
                                    type="text" 
                                    id="monto_display"
                                    class="form-control" 
                                    placeholder="$ 0"
                                    inputmode="numeric"
                                >
                                <input 
                                    type="hidden" 
                                    name="monto_por_boleta" 
                                    id="monto_real"
                                    value="{{ old('monto_por_boleta', $config['monto_por_boleta'] ?? '') }}"
                                >
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">Longitud número boleta</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="10"
                                    name="longitud_numero_boleta"
                                    class="form-control"
                                    value="{{ old('longitud_numero_boleta', $config['longitud_numero_boleta'] ?? '4') }}"
                                    required disabled
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Rango desde</label>
                                <input
                                    type="text"
                                    name="rango_boleta_desde"
                                    class="form-control"
                                    value="{{ old('rango_boleta_desde', $config['rango_boleta_desde'] ?? '0000') }}"
                                    required disabled
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Rango hasta</label>
                                <input
                                    type="text"
                                    name="rango_boleta_hasta"
                                    class="form-control"
                                    value="{{ old('rango_boleta_hasta', $config['rango_boleta_hasta'] ?? '9999') }}"
                                    required disabled
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card card mt-4">
                    <div class="card-header">
                        <h5 class="mb-1 fw-bold">Texto promocional</h5>
                        <small class="text-muted">Este contenido podrá mostrarse en la página pública del sorteo.</small>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Contenido promocional</label>
                        <textarea
                            name="texto_promocional"
                            rows="6"
                            class="form-control"
                            placeholder="Escribe aquí el mensaje promocional del sistema..."
                        >{{ old('texto_promocional', $config['texto_promocional'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-card card">
                    <div class="card-header">
                        <h5 class="mb-1 fw-bold">Resumen</h5>
                        <small class="text-muted">Configuraciones centrales del sistema.</small>
                    </div>
                    <div class="card-body">
                        <div class="p-3 rounded-4 border mb-3">
                            <div class="small text-muted">Monto actual por boleta</div>
                            <div class="fw-bold fs-4">
                                ${{ number_format((float)($config['monto_por_boleta'] ?? 0), 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="p-3 rounded-4 border mb-3">
                            <div class="small text-muted">Longitud actual</div>
                            <div class="fw-bold fs-4">
                                {{ $config['longitud_numero_boleta'] ?? 4 }} dígitos
                            </div>
                        </div>

                        <div class="p-3 rounded-4 border">
                            <div class="small text-muted">Rango permitido</div>
                            <div class="fw-bold fs-5">
                                {{ $config['rango_boleta_desde'] ?? '0000' }} - {{ $config['rango_boleta_hasta'] ?? '9999' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const display = document.getElementById('monto_display');
                const real    = document.getElementById('monto_real');

                // Formatear valor inicial si existe
                if (real.value) {
                    let formatted = parseInt(real.value, 10).toLocaleString('es-CO');
                    display.value = '$ ' + formatted;
                }

                display.addEventListener('input', function () {
                    let raw = this.value.replace(/\D/g, '');

                    if (!raw) {
                        this.value = '';
                        real.value = '';
                        return;
                    }

                    let formatted = parseInt(raw, 10).toLocaleString('es-CO');
                    this.value = '$ ' + formatted;
                    real.value = raw;
                });

                display.addEventListener('paste', function () {
                    setTimeout(() => display.dispatchEvent(new Event('input')), 0);
                });
            });
        </script>
@endpush
@endsection