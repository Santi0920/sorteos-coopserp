@extends('layouts.admin')

@php
    $title = 'Editar Línea';
    $subtitle = 'Actualiza la información de la línea seleccionada.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.lineas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Editar línea</h5>
            <small class="text-muted">Modifica los datos y guarda los cambios.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lineas.update', $linea) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.lineas._form', ['linea' => $linea])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.lineas.index') }}" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar línea
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection