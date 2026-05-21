@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-body">

            <h4 class="fw-bold">📥 Importación de asociados</h4>

            <p class="text-muted">
                Sube un archivo Excel o CSV. El sistema detecta automáticamente las columnas.
            </p>

            {{-- INFO --}}
            <div class="alert alert-info rounded-3">

                <h6 class="fw-bold">📌 Cómo funciona</h6>

                <ul class="mb-2">
                    <li>El sistema usa <b>header mapping</b></li>
                    <li>No importa el orden de las columnas</li>
                    <li>Cédula y nombre son obligatorios</li>
                    <li>Crédito es opcional (solo si el sorteo es por valor)</li>
                </ul>

                <hr>

                <p class="mb-0">
                    <b>Tipo de sorteo:</b> {{ $sorteo->tipo_asignacion }}
                    @if($sorteo->tipo_asignacion === 'por_valor')
                        | Monto por boleta: ${{ number_format($sorteo->monto_por_boleta) }}
                    @endif
                </p>

            </div>

            {{-- DESCARGA PLANTILLA --}}
            <a href="{{ route('admin.import.template') }}"
               class="btn btn-success mb-3">
                📊 Descargar plantilla Excel/CSV
            </a>

            {{-- FORM --}}
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.sorteos.import.store', $sorteo->id) }}">

                @csrf

                <input type="file"
                       name="file"
                       class="form-control mb-3"
                       accept=".csv,.xlsx,.txt"
                       required>

                <button class="btn btn-primary w-100">
                    🚀 Importar y generar asociados
                </button>

            </form>

        </div>
    </div>

</div>

@endsection