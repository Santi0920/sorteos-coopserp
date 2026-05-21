<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Boletas</title>
    <link rel="shortcut icon" href="../logoo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f4f6;
            color: #111827;
        }

        .hero {
            background:
                radial-gradient(circle at top left, rgba(37,99,235,.25), transparent 30%),
                linear-gradient(135deg, #0f172a, #111827);
            color: white;
            padding: 56px 0 38px;
            margin-bottom: 28px;
        }

        .hero-card {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 24px;
            padding: 24px;
            backdrop-filter: blur(10px);
        }

        .content-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 35px rgba(15,23,42,.06);
        }

        .boleta-pill {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .winner-pill {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 600;
        }

        .pdf-btn {
            border-radius: 12px;
        }

        .boletas-scroll {
            max-height: 520px;
            overflow-y: auto;
            overflow-x: auto;
            border-radius: 16px;
        }

        .boletas-scroll::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .boletas-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .boletas-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 999px;
        }
        .hero-logo {
            max-width: 250px;
            margin-bottom: 10px;
        }
        .hero-logo-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .hero-logo {
            max-width: 220px;
            width: 100%;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 22px rgba(0,0,0,.18));
        }

        .hero-logo-text {
            font-size: .92rem;
            color: rgba(255,255,255,.72);
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <div class="hero-card">
                <div class="hero-logo-wrap">
                    <img 
                        src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                        alt="Coopserp"
                        class="hero-logo"
                    >
                    <div class="hero-logo-text">Consulta oficial de boletas</div>
                </div>
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        
                        <h1 class="fw-bold mb-2">Hola, {{ $asociado->nombre_completo }}</h1>
                        <p class="mb-0 text-white-50">
                            Aquí puedes consultar las boletas que tienes registradas en los sorteos.
                        </p>
           
                    </div>
                    <div class="col-lg-4">
                        <div class="text-lg-end">
                            <div class="small text-white-50">Documento</div>
                            <div class="fs-5 fw-semibold">{{ $asociado->documento }}</div>

                            @if($asociado->email)
                                <div class="small text-white-50 mt-3">Correo</div>
                                <div>{{ $asociado->email }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h3 class="fw-bold mb-0">Mis boletas</h3>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('consulta.boletas.pdf', ['token' => $asociado->token_consulta]) }}"
                class="btn btn-outline-danger rounded-4" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
                </a>

                <a href="{{ route('consulta.boletas.form') }}" class="btn btn-outline-secondary rounded-4">
                    <i class="bi bi-arrow-left me-1"></i> Nueva consulta
                </a>
            </div>
        </div>

        <div class="content-card p-4">
            @if($asociado->boletas->count())
                @php
                    $boletasPorSorteo = $asociado->boletas->groupBy('sorteo_id');
                @endphp

                @foreach($boletasPorSorteo as $sorteoId => $boletasGrupo)
                    @php
                        $sorteo = $boletasGrupo->first()->sorteo;
                    @endphp

                    <div class="border rounded-4 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $sorteo?->nombre ?? 'Sorteo' }}</h5>
                                <div class="text-muted small">
                                    Fecha:
                                    {{ $sorteo?->fecha_sorteo ? $sorteo->fecha_sorteo->format('d/m/Y') : '—' }}
                                </div>
                            </div>

                        </div>

                        <div class="{{ $boletasGrupo->count() > 10 ? 'boletas-scroll' : 'table-responsive' }}">
                            <table class="table align-middle mb-0">
                                <thead style="position: sticky; top: 0; background: white; z-index: 2;">
                                    <tr>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($boletasGrupo as $boleta)
                                        <tr>
                                            <td>
                                                <span class="boleta-pill">{{ $boleta->numero_boleta }}</span>
                                            </td>
                                            <td>
                                                {{ $boleta->sorteo?->fecha_sorteo ? $boleta->sorteo->fecha_sorteo->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>
                                                @if($boleta->ganadora)
                                                    <span class="winner-pill">Ganadora</span>
                                                @else
                                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Activa</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated fs-1 text-muted"></i>
                    <h5 class="fw-bold mt-3">No tienes boletas registradas</h5>
                    <p class="text-muted mb-0">
                        Cuando se generen boletas para un sorteo, aparecerán aquí.
                    </p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>