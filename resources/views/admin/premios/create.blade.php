@extends('layouts.admin')

@php
    $title = 'Crear Premio';
    $subtitle = 'Registra un nuevo premio y asígnalo a un sorteo.';
@endphp

@section('topbar_actions')
    <a href="{{ route('admin.premios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
@endsection

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Nuevo premio</h5>
            <small class="text-muted">Completa la información del premio.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.premios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.premios._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.premios.index') }}" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar premio
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection