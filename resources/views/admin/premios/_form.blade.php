<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Sorteo</label>
        <select name="sorteo_id" class="form-select" required>
            <option value="">Selecciona un sorteo</option>
            @foreach($sorteos as $sorteo)
                <option value="{{ $sorteo->id }}"
                    {{ (string) old('sorteo_id', $premio->sorteo_id ?? '') === (string) $sorteo->id ? 'selected' : '' }}>
                    {{ $sorteo->nombre }} - {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Título</label>
        <input
            type="text"
            name="titulo"
            class="form-control"
            value="{{ old('titulo', $premio->titulo ?? '') }}"
            required
        >
    </div>

    <div class="col-md-8">
        <label class="form-label">Descripción</label>
        <textarea
            name="descripcion"
            rows="4"
            class="form-control"
            placeholder="Describe el premio..."
        >{{ old('descripcion', $premio->descripcion ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Imagen</label>
        <input
            type="file"
            name="imagen"
            class="form-control"
            accept=".jpg,.jpeg,.png,.webp"
        >

        @if(!empty($premio?->imagen))
            <div class="mt-3">
                <img
                    src="{{ asset('storage/' . $premio->imagen) }}"
                    alt="{{ $premio->titulo }}"
                    style="width: 100%; max-width: 220px; border-radius: 14px; object-fit: cover; border: 1px solid #e5e7eb;"
                >
            </div>
        @endif
    </div>

    <div class="col-md-3">
        <label class="form-label">Orden</label>
        <input
            type="number"
            name="orden"
            class="form-control"
            min="1"
            value="{{ old('orden', $premio->orden ?? 1) }}"
            required
        >
    </div>

    <div class="col-md-3">
        <label class="form-label">Estado</label>
        @php
            $activo = (string) old('activo', isset($premio) ? (int) $premio->activo : '1');
        @endphp
        <select name="activo" class="form-select" required>
            <option value="1" {{ $activo === '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ $activo === '0' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
</div>