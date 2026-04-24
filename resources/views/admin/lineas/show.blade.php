@extends('layouts.admin')

@php
    $title = 'Detalle de Línea';
    $subtitle = 'Visualiza toda la información de la línea.';
@endphp

@section('topbar_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.lineas.edit', $linea) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="{{ route('admin.lineas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">{{ $linea->nombre }}</h5>
            <small class="text-muted">Información completa del registro.</small>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label text-muted">Código</label>
                    <div class="fw-semibold fs-5">{{ $linea->codigo }}</div>
                </div>

                <div class="col-md-8">
                    <label class="form-label text-muted">Nombre</label>
                    <div class="fw-semibold fs-5">{{ $linea->nombre }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">Participa en sorteos</label>
                    <div class="fw-semibold">{{ $linea->participa_sorteo ? 'Sí' : 'No' }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">Estado</label>
                    <div class="fw-semibold">{{ $linea->activo ? 'Activo' : 'Inactivo' }}</div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted">Descripción</label>
                    <div class="p-3 bg-light rounded-4">
                        {{ $linea->descripcion ?: 'Sin descripción.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection