@extends('layouts.admin')

@php
    $title = 'Detalle del Sorteo';
    $subtitle = 'Visualiza toda la información del sorteo.';
@endphp

@section('topbar_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.sorteos.edit', $sorteo) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="{{ route('admin.sorteos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">{{ $sorteo->nombre }}</h5>
            <small class="text-muted">Información completa del registro.</small>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted">Nombre</label>
                    <div class="fw-semibold fs-5">{{ $sorteo->nombre }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">Fecha del sorteo</label>
                    <div class="fw-semibold fs-5">{{ $sorteo->fecha_sorteo->format('d/m/Y') }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">Lotería</label>
                    <div class="fw-semibold">{{ $sorteo->loteria ?: 'No definida' }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Estado</label>
                    <div class="fw-semibold">{{ ucfirst($sorteo->estado) }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">Reprogramado</label>
                    <div class="fw-semibold">{{ $sorteo->es_reprogramado ? 'Sí' : 'No' }}</div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted">Observaciones</label>
                    <div class="p-3 bg-light rounded-4">
                        {{ $sorteo->observaciones ?: 'Sin observaciones.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection