@extends('layouts.admin')

@php
    $title = 'Editar Sorteo';
    $subtitle = 'Actualiza la información del sorteo seleccionado.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.sorteos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Editar sorteo</h5>
            <small class="text-muted">Modifica los datos y guarda los cambios.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sorteos.update', $sorteo) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.sorteos._form', ['sorteo' => $sorteo])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.sorteos.index') }}" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar sorteo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection