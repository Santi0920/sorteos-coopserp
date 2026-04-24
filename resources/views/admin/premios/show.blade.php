@extends('layouts.admin')

@php
    $title = 'Detalle del Premio';
    $subtitle = 'Visualiza toda la información del premio.';
@endphp

@section('topbar_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.premios.edit', $premio) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="{{ route('admin.premios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">{{ $premio->titulo }}</h5>
            <small class="text-muted">Información completa del premio.</small>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    @if($premio->imagen)
                        <img
                            src="{{ asset('storage/' . $premio->imagen) }}"
                            alt="{{ $premio->titulo }}"
                            class="img-fluid rounded-4 border preview-image"
                            data-image="{{ asset('storage/' . $premio->imagen) }}"
                            data-title="{{ $premio->titulo }}"
                            style="cursor:pointer;"
                        >
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-4 border" style="height: 240px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Título</label>
                            <div class="fw-semibold fs-5">{{ $premio->titulo }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Sorteo</label>
                            <div class="fw-semibold">{{ $premio->sorteo?->nombre ?? 'No definido' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Orden</label>
                            <div class="fw-semibold">{{ $premio->orden }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Estado</label>
                            <div class="fw-semibold">{{ $premio->activo ? 'Activo' : 'Inactivo' }}</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted">Descripción</label>
                            <div class="p-3 bg-light rounded-4">
                                {{ $premio->descripcion ?: 'Sin descripción.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection