@extends('layouts.admin')

@php
    $title = 'Editar Premio';
    $subtitle = 'Actualiza la información del premio seleccionado.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.premios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Editar premio</h5>
            <small class="text-muted">Modifica los datos y guarda los cambios.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.premios.update', $premio) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.premios._form', ['premio' => $premio])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.premios.index') }}" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar premio
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection