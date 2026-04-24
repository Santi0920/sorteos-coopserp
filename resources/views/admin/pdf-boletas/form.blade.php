@extends('layouts.admin')

@php
    $title = 'PDF Individual de Boletas';
    $subtitle = 'Genera un PDF imprimible de las boletas de un asociado por sorteo.';
@endphp

@section('content')
    <div class="content-card card">
        <div class="card-header">
            <h5 class="mb-1 fw-bold">Generar PDF individual</h5>
            <small class="text-muted">Selecciona el sorteo y el asociado para descargar su PDF.</small>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pdf-boletas.generate') }}" method="POST" class="row g-4">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Sorteo</label>
                    <select name="sorteo_id" class="form-select" required>
                        <option value="">Selecciona un sorteo</option>
                        @foreach($sorteos as $sorteo)
                            <option value="{{ $sorteo->id }}">
                                {{ $sorteo->nombre }} - {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Asociado</label>
                    <select name="asociado_id" class="form-select" required>
                        <option value="">Selecciona un asociado</option>
                        @foreach($asociados as $asociado)
                            <option value="{{ $asociado->id }}">
                                {{ $asociado->nombre_completo }} - {{ $asociado->documento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Generar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection