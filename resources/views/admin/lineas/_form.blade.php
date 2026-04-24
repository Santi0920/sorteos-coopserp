<div class="row g-4">
    <div class="col-md-4">
        <label class="form-label">Código</label>
        <input
            type="text"
            name="codigo"
            class="form-control"
            value="{{ old('codigo', $linea->codigo ?? '') }}"
            required
        >
    </div>

    <div class="col-md-8">
        <label class="form-label">Nombre</label>
        <input
            type="text"
            name="nombre"
            class="form-control"
            value="{{ old('nombre', $linea->nombre ?? '') }}"
            required
        >
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea
            name="descripcion"
            rows="4"
            class="form-control"
            placeholder="Describe la línea..."
        >{{ old('descripcion', $linea->descripcion ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">¿Participa en sorteos?</label>
        @php
            $participa = (string) old('participa_sorteo', isset($linea) ? (int) $linea->participa_sorteo : '1');
        @endphp
        <select name="participa_sorteo" class="form-select" required>
            <option value="1" {{ $participa === '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ $participa === '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Estado</label>
        @php
            $activo = (string) old('activo', isset($linea) ? (int) $linea->activo : '1');
        @endphp
        <select name="activo" class="form-select" required>
            <option value="1" {{ $activo === '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ $activo === '0' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
</div>