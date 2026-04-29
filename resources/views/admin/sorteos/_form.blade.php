<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input
            type="text"
            name="nombre"
            class="form-control"
            value="{{ old('nombre', $sorteo->nombre ?? '') }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label">Fecha del sorteo</label>
        <input
            type="date"
            name="fecha_sorteo"
            class="form-control"
            value="{{ old('fecha_sorteo', isset($sorteo) && $sorteo->fecha_sorteo ? $sorteo->fecha_sorteo->format('Y-m-d') : '') }}"
            required
        >
    </div>

    <div class="col-md-6">
        <label class="form-label">Lotería</label>
        <input
            type="text"
            name="loteria"
            class="form-control"
            value="{{ old('loteria', $sorteo->loteria ?? '') }}"
            placeholder="Ej: Lotería de Medellín"
        >
    </div>

    <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            @php
                $estadoActual = old('estado', $sorteo->estado ?? 'programado');
            @endphp
            <option value="programado" {{ $estadoActual === 'programado' ? 'selected' : '' }}>Programado</option>
            <option value="ejecutado" {{ $estadoActual === 'ejecutado' ? 'selected' : '' }}>Ejecutado</option>
            <option value="cancelado" {{ $estadoActual === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">¿Es reprogramado?</label>
        @php
            $reprogramado = (string) old('es_reprogramado', isset($sorteo) ? (int) $sorteo->es_reprogramado : '0');
        @endphp
        <select name="es_reprogramado" class="form-select" required>
            <option value="0" {{ $reprogramado === '0' ? 'selected' : '' }}>No</option>
            <option value="1" {{ $reprogramado === '1' ? 'selected' : '' }}>Sí</option>
        </select>
    </div>



    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea
            name="observaciones"
            rows="4"
            class="form-control"
            placeholder="Notas internas del sorteo..."
        >{{ old('observaciones', $sorteo->observaciones ?? '') }}</textarea>
    </div>
</div>