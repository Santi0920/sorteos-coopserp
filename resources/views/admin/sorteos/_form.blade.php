<div class="row g-4">

    {{-- ========================= --}}
    {{-- INFORMACIÓN BÁSICA --}}
    {{-- ========================= --}}
    <div class="col-12">
        <h6 class="text-muted fw-bold">📌 Información básica</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Nombre del sorteo
        </label>
        <small class="text-muted d-block mb-1">
            Ej: Sorteo mensual de asociados diciembre 2026
        </small>
        <input type="text" name="nombre" class="form-control"
               value="{{ old('nombre', $sorteo->nombre ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Fecha del sorteo
        </label>
        <small class="text-muted d-block mb-1">
            Día en que se realizará el sorteo oficialmente
        </small>
        <input type="date" name="fecha_sorteo" class="form-control"
               value="{{ old('fecha_sorteo', isset($sorteo) ? $sorteo->fecha_sorteo->format('Y-m-d') : '') }}"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Lotería</label>
        <small class="text-muted d-block mb-1">
            Referencia externa del sorteo (ej: Lotería de Medellín)
        </small>
        <input type="text" name="loteria" class="form-control"
               value="{{ old('loteria', $sorteo->loteria ?? '') }}">
    </div>

    {{-- ========================= --}}
    {{-- CONFIGURACIÓN DE BOLETAS --}}
    {{-- ========================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-muted fw-bold">🎟️ Configuración de boletas</h6>
    </div>

    <div class="row g-4">

        <div class="col-md-6">
            <label class="form-label">Número inicial</label>
            <small class="text-muted d-block mb-1">
                Número desde el que inicia la serie de boletas (puede ser 0001, 0000, etc).
            </small>

            <input
                type="number"
                name="numero_inicio"
                id="numero_inicio"
                class="form-control"
                min="0"
                max="9999"
                step="1"
                value="0"
                disabled
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Número final</label>
            <small class="text-muted d-block mb-1">
                Máximo 9999 boletas por sorteo.
            </small>

            <input
                type="number"
                name="numero_fin"
                id="numero_fin"
                class="form-control"
                min="0"
                max="9999"
                step="1"
                value="{{ old('numero_fin', $sorteo->numero_fin ?? 99) }}"
                required
            >
            <small id="range_error" class="text-danger d-none">
                El número final no puede ser menor que el inicial ni mayor a 9999.
            </small>
        </div>
    </div>


    {{-- ========================= --}}
    {{-- TIPO DE SORTEO --}}
    {{-- ========================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-muted fw-bold">⚙️ Tipo de sorteo</h6>
    </div>


    <div class="col-md-12">

        <input
            type="number"

            name="boletas_por_persona"

            min="1"

            required

            class="form-control"

            value="{{ old(
                'boletas_por_persona',
                $sorteo->boletas_por_persona ?? 1
            ) }}"

        >

    </div>



    {{-- ========================= --}}
    {{-- CONFIGURACIÓN EXTRA --}}
    {{-- ========================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-muted fw-bold">🧩 Configuración adicional</h6>
    </div>

    <div class="col-md-3">
        <label class="form-label">Estado</label>
        <small class="text-muted d-block mb-1">Control del ciclo del sorteo</small>

        <select name="estado" class="form-select">
            @php $estado = old('estado', $sorteo->estado ?? 'programado'); @endphp
            <option value="programado" @selected($estado=='programado')>Programado</option>
            <option value="ejecutado" @selected($estado=='ejecutado')>Ejecutado</option>
            <option value="cancelado" @selected($estado=='cancelado')>Cancelado</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Activo</label>
        <small class="text-muted d-block mb-1">Si está disponible o no</small>

        <select name="activo" class="form-select">
            <option value="1" @selected(old('activo',$sorteo->activo ?? 1))>Sí</option>
            <option value="0" @selected(!old('activo',$sorteo->activo ?? 1))>No</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Reprogramado</label>
        <small class="text-muted d-block mb-1">Indica si cambió de fecha</small>

        <select name="es_reprogramado" class="form-select">
            <option value="0" @selected(old('es_reprogramado',$sorteo->es_reprogramado ?? 0)==0)>No</option>
            <option value="1" @selected(old('es_reprogramado',$sorteo->es_reprogramado ?? 0)==1)>Sí</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Boletas</label>
        <small class="text-muted d-block mb-1">Se generan después</small>

        <input type="text" class="form-control" value="Auto-generadas" disabled>
    </div>

    {{-- ========================= --}}
    {{-- OBSERVACIONES --}}
    {{-- ========================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-muted fw-bold">📝 Notas internas</h6>
    </div>

    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <small class="text-muted d-block mb-1">
            Solo uso interno del sistema (no visible a usuarios finales)
        </small>

        <textarea name="observaciones" class="form-control" rows="3">
            {{ old('observaciones', $sorteo->observaciones ?? '') }}
        </textarea>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inicio = document.getElementById('numero_inicio');
    const fin = document.getElementById('numero_fin');
    const error = document.getElementById('range_error');

    const tipo = document.getElementById('tipo_asignacion');
    const monto = document.getElementById('monto_por_boleta');

    // 🔥 VALIDACIÓN DE RANGO
    function validateRange() {
        const i = parseInt(inicio.value || 0);
        const f = parseInt(fin.value || 0);

        if (f > 9999 || f < i) {
            error.classList.remove('d-none');
            fin.classList.add('is-invalid');
        } else {
            error.classList.add('d-none');
            fin.classList.remove('is-invalid');
        }
    }

    inicio.addEventListener('input', validateRange);
    fin.addEventListener('input', validateRange);

    // 🔥 LÓGICA TIPO DE ASIGNACIÓN
    function toggleMonto() {
        if (tipo.value === 'monto') {
            monto.disabled = false;
        } else {
            monto.disabled = true;
            monto.value = '';
        }
    }

    tipo.addEventListener('change', toggleMonto);
    toggleMonto(); // init
});
</script>