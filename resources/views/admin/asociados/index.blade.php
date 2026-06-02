@extends('layouts.admin')

@php
    $title = 'Gestión de Asociados';
    $subtitle = 'Consulta y administra asociados vinculados a sorteos.';
@endphp

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.sorteos.index') }}"
       class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a sorteos
    </a>
</div>
<!-- <div class="row g-4 mb-4">

    <div class="col-lg-4">
        <div class="stats-box">
            <p>Total asociados</p>
            <h3>{{ $asociados->total() }}</h3>
        </div>
    </div>

</div> -->

<div class="content-card card">

    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
            <h5 class="mb-1 fw-bold">
                Listado de Participantes
            </h5>

            <small class="text-muted">
                Consulta participantes vinculados a sorteos.
            </small>
        </div>

        <form method="GET"
              action="{{ route('admin.asociados.index') }}"
              class="row g-2 align-items-center">

            <!-- SORTEO -->
            <div class="col-auto">

                <select
                    name="sorteo_id"
                    class="form-select"
                    onchange="this.form.submit()"
                >

                    <!-- <option value="">
                        Todos los sorteos
                    </option> -->

                    @foreach($sorteos as $s)

                        <option value="{{ $s->id }}"
                            {{ request('sorteo_id') == $s->id ? 'selected' : '' }}>

                            {{ $s->nombre }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- SEARCH -->
            <div class="col-auto">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control"
                    placeholder="Buscar asociado"
                    style="min-width: 280px;"
                >

            </div>

            <!-- PAGINACIÓN -->
            <div class="col-auto">

                <select
                    name="per_page"
                    class="form-select"
                    onchange="this.form.submit()"
                >

                    <option value="10"  {{ (int)$perPage === 10 ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ (int)$perPage === 25 ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ (int)$perPage === 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (int)$perPage === 100 ? 'selected' : '' }}>100</option>

                </select>

            </div>

            <!-- BTN -->
            <div class="col-auto">

                <button class="btn btn-outline-primary">

                    <i class="bi bi-search"></i>

                </button>

            </div>

        </form>

    </div>

    <div class="card-body">

        @if($asociados->count())

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Agencia</th>
                            <th>Cuenta</th>
                            <th>Boletas por Persona</th>
                            <th>Nómina</th>
                            <th>Coordinador</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($asociados as $asociado)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $asociado->documento }}
                            </td>

                            <td>

                                <div class="fw-semibold">

                                    {{ $asociado->nombre_completo }}

                                </div>

                            </td>

                            <td>
                                {{ $asociado->email ?: '—' }}
                            </td>

                            <td>
                                {{ $asociado->agencia ?: '—' }}
                            </td>

                            <td>
                                {{ $asociado->cuenta ?: '—' }}
                            </td>

                           <td class="fw-bold">
                                {{ $asociado->boletas_por_persona ?: '—' }}
                            </td>

                            <td>
                                {{ $asociado->nomina ?: '—' }}
                            </td>

                            <td>
                                {{ $asociado->coordinador ?: '—' }}

                            <td>

                                @if($asociado->activo)

                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">

                <div class="text-muted small">

                    Mostrando
                    {{ $asociados->firstItem() }}
                    a
                    {{ $asociados->lastItem() }}
                    de
                    {{ $asociados->total() }}
                    registros

                </div>

                <div>

                    {{ $asociados->links() }}

                </div>

            </div>

        @else

            <div class="text-center py-5">

                <i class="bi bi-people fs-1 text-muted"></i>

                <h5 class="mt-3 fw-bold">
                    No hay asociados
                </h5>

                <p class="text-muted">
                    No existen asociados vinculados al sorteo seleccionado.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection