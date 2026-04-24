<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Sorteos</title>
    <link rel="shortcut icon" href="logoo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
            color: #0f172a;
        }

        .hero-results {
            background:
                radial-gradient(circle at top left, rgba(37,99,235,.20), transparent 30%),
                linear-gradient(135deg, #0f172a, #020617);
            color: white;
            padding: 70px 0 50px;
        }

        .hero-card {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 28px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        .sorteo-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 16px 35px rgba(15,23,42,.06);
            overflow: hidden;
        }

        .premio-card {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            height: 100%;
        }

        .premio-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .winner-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dcfce7;
            color: #166534;
            border-radius: 999px;
            padding: 9px 14px;
            font-weight: 700;
            font-size: .9rem;
        }

        .boleta-pill {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .empty-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 16px 35px rgba(15,23,42,.05);
        }
        .hero-logo {
            max-width: 250px;
            margin-bottom: 10px;
        }

        .results-brand {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.results-brand img {
    max-width: 220px;
    width: 100%;
    height: auto;
    object-fit: contain;
    filter: drop-shadow(0 10px 22px rgba(0,0,0,.18));
}

.results-brand-text {
    font-size: .92rem;
    color: rgba(255,255,255,.72);
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
}
    </style>
</head>
<body>

    <section class="hero-results">
        <div class="container">
            <div class="hero-card">
                <div class="results-brand">
                    <img 
                        src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                        alt="Coopserp"
                    >
                    <div class="results-brand-text">Publicación oficial de resultados</div>
                </div>
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <h1 class="fw-bold mb-2">Resultados oficiales de sorteos</h1>
                        <p class="text-white-50 mb-0">
                            Consulta los premios asignados y las boletas ganadoras publicadas para cada sorteo.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('landing') }}" class="btn btn-light rounded-4">
                            <i class="bi bi-arrow-left me-1"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-5">
        @php
            $hayResultados = false;
        @endphp

        <div class="row g-4">
            @foreach($sorteos as $sorteo)
                @if($sorteo->premios->count())
                    @php $hayResultados = true; @endphp

                    <div class="col-12">
                        <div class="sorteo-card p-4">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                                <div>
                                    <h3 class="fw-bold mb-1">{{ $sorteo->nombre }}</h3>
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div>
                                    <span class="winner-badge">
                                        <i class="bi bi-trophy-fill"></i>
                                        {{ $sorteo->premios->count() }} premio(s) asignado(s)
                                    </span>
                                </div>
                            </div>

                            <div class="row g-4">
                                @foreach($sorteo->premios as $premio)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="premio-card">
                                            @if($premio->imagen)
                                                <img
                                                    src="{{ asset('storage/' . $premio->imagen) }}"
                                                    alt="{{ $premio->titulo }}"
                                                    class="premio-image"
                                                >
                                            @else
                                                <div class="premio-image d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-image fs-1"></i>
                                                </div>
                                            @endif

                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                    <h5 class="fw-bold mb-0">{{ $premio->titulo }}</h5>
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                                        #{{ $premio->orden }}
                                                    </span>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="small text-muted">Boleta ganadora</div>
                                                    <div class="mt-1">
                                                        <span class="boleta-pill">{{ $premio->boletaGanadora?->numero_boleta ?? '—' }}</span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="small text-muted">Ganador</div>
                                                    <div class="fw-semibold">
                                                        {{ $premio->boletaGanadora?->asociado?->nombre_completo ?? 'No disponible' }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Documento: {{ $premio->boletaGanadora?->asociado?->documento ?? '—' }}
                                                    </div>
                                                </div>

                                                @if($premio->descripcion)
                                                    <div>
                                                        <div class="small text-muted">Descripción del premio</div>
                                                        <div class="text-muted">
                                                            {{ $premio->descripcion }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(!$hayResultados)
            <div class="empty-card mt-4">
                <i class="bi bi-trophy fs-1 text-muted"></i>
                <h4 class="fw-bold mt-3">Aún no hay resultados publicados</h4>
                <p class="text-muted mb-0">
                    Cuando se asignen premios a boletas ganadoras, aparecerán aquí automáticamente.
                </p>
            </div>
        @endif
    </main>
</body>
</html>