@extends('layouts.admin')

@section('content')
@php
    $title = 'Visualización de Asociados';
    $subtitle = 'Administra y revisa los asociados registrados en el sistema, así como sus créditos relacionados.';
@endphp
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Asociados</h3>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Agencia</th>
                            <th>Cuenta</th>
                            <th>Nomina</th>
                            <th>Ver</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($asociados as $asociado)
                            <tr>
                                <td class="fw-bold">
                                    {{ $asociado->id }}
                                </td>
                                <td>{{ $asociado->documento }}</td>

                                <td>{{ $asociado->nombre_completo }}</td>

                                <td>{{ $asociado->email }}</td>

                                <td>{{ $asociado->agencia ?? '-' }}</td>

                                <td>{{ $asociado->cuenta ?? '-' }}</td>

                                <td>{{ $asociado->nomina ?? '-' }}</td>

                                <td>
                                    <button 
                                        class="btn btn-sm px-3 d-flex align-items-center gap-2"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px; color: white; font-weight: 500; box-shadow: 0 2px 8px rgba(102,126,234,0.4); transition: all 0.2s ease;"
                                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102,126,234,0.6)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(102,126,234,0.4)'"
                                        onclick="verCreditos('{{ route('admin.asociados.creditos', $asociado->id) }}', '{{ $asociado->nombre_completo }}')"
                                    >
                                        <i class="bi bi-credit-card-2-front"></i>
                                        <span>{{ $asociado->creditos_count }} crédito{{ $asociado->creditos_count !== 1 ? 's' : '' }}</span>
                                        <span style="opacity: 0.7;">|</span>
                                        <span style="font-size: 0.8rem; opacity: 0.9;">Ver</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay asociados registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $asociados->links() }}
            </div>

        </div>
    </div>

</div>


<!-- MODAL -->
<div class="modal fade" id="creditosModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Créditos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="loading" class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Cargando créditos...</p>
                </div>

                <div id="creditosContent" class="d-none">

                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th># Crédito</th>
                                <th>Línea</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>

                        <tbody id="tablaCreditos"></tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
function verCreditos(url, nombre) {

    const modal = new bootstrap.Modal(document.getElementById('creditosModal'));

    document.getElementById('modalTitle').innerText = 'Créditos de ' + nombre;

    document.getElementById('loading').classList.remove('d-none');
    document.getElementById('creditosContent').classList.add('d-none');

    modal.show();

    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {

        let html = '';

        if (!data.length) {
            html = `<tr><td colspan="4" class="text-center text-muted">Sin créditos</td></tr>`;
        } else {

            data.forEach(c => {
                html += `
                    <tr>
                        <td><strong>#${c.numero_credito}</strong></td>
                        <td>${c.linea ?? '—'}</td>
                        <td>$${Number(c.monto).toLocaleString()}</td>
                        <td>${c.fecha_desembolso ?? '-'}</td>
                    </tr>
                `;
            });
        }

        document.getElementById('tablaCreditos').innerHTML = html;

        document.getElementById('loading').classList.add('d-none');
        document.getElementById('creditosContent').classList.remove('d-none');

    })
    .catch(err => {
        console.error(err);

        document.getElementById('tablaCreditos').innerHTML = `
            <tr>
                <td colspan="4" class="text-danger text-center">
                    Error cargando créditos
                </td>
            </tr>
        `;
    });
}
</script>
@endpush