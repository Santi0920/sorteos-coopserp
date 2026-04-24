@extends('layouts.admin')

@php
    $title = 'Crear Sorteo';
    $subtitle = 'Registra una nueva fecha de sorteo y sus parámetros principales.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.sorteos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Nuevo sorteo</h5>
            <small class="text-muted">Completa la información del sorteo.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sorteos.store') }}" method="POST">
                @csrf

                @include('admin.sorteos._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.sorteos.index') }}" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar sorteo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection