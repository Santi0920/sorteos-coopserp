@extends('layouts.admin')
@php
    $title = 'Importar datos';
    $subtitle = 'Carga masiva de asociados y créditos a través de un archivo CSV o Excel. Asegúrate de seguir el formato requerido para una importación exitosa.';
@endphp
@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-4">Importar CSV</h3>

    <div class="alert alert-info rounded-4">
        <h5 class="fw-bold mb-2">Formato requerido del archivo</h5>

        <p class="mb-2">
            El archivo debe ser un <strong>CSV o Excel (.xlsx)</strong> con las siguientes columnas:
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-2">
                <thead class="table-light">
                    <tr>
                        <th>Cuenta</th>
                        <th>Agencia</th>
                        <th>Nomina</th>
                        <th>Cedula</th>
                        <th>Nombre</th>
                        <th>Linea</th>
                        <th>Credito</th>
                        <th>Monto</th>
                        <th>Email</th>
                    </tr>
                </thead>
            </table>
        </div>

        <ul class="mb-0">
            <li>La primera fila debe contener los encabezados.</li>
            <li>No dejar columnas vacías en Cedula, Nombre, Credito y Monto.</li>
            <li>El número de crédito no debe repetirse para el mismo asociado.</li>
            <li>El monto debe ser numérico sin símbolos ($).</li>
        </ul>
    </div>
    <a href="{{ route('admin.import.template') }}" class="btn btn-outline-primary mb-3">
        <i class="bi bi-download me-1"></i> Descargar plantilla CSV
    </a>

    <a href="{{ route('admin.import.template.excel') }}" class="btn btn-success mb-3">
        <i class="bi bi-file-earmark-excel"></i> Descargar plantilla Excel
    </a>
    <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Archivo CSV</label>
                <input 
                    type="file" 
                    name="file" 
                    class="form-control"
                    accept=".csv,.txt,.xlsx"
                    required
                >
        </div>

        <button class="btn btn-primary">
            <i class="bi bi-upload"></i> Subir archivo
        </button>
    </form>

</div>
@endsection