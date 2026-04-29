<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteos - Página Oficial</title>
    <link rel="shortcut icon" href="logoo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-darker: #020617;
            --primary: #2563eb;
            --primary-2: #3b82f6;
            --soft-text: #94a3b8;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .navbar-public {
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(12px);
            background: rgba(15, 23, 42, 0.84);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .brand-badge {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 12px 30px rgba(37,99,235,.35);
            flex-shrink: 0;
        }

        .nav-link-public {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-weight: 500;
            transition: .2s ease;
        }

        .nav-link-public:hover {
            color: #fff;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 15% 20%, rgba(37,99,235,.24), transparent 30%),
                radial-gradient(circle at 85% 15%, rgba(59,130,246,.16), transparent 26%),
                linear-gradient(135deg, #0f172a 0%, #081226 45%, #020617 100%);
            color: white;
            padding: 72px 0 56px;
        }

        .hero-card {
            background: linear-gradient(180deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,.06) 100%);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 28px;
            padding: 38px;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 60px rgba(0,0,0,.18);
            height: 100%;
        }

        .hero-title {
            font-size: clamp(2.2rem, 4.6vw, 4.8rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -.04em;
            max-width: 820px;
            word-break: break-word;
        }

        .hero-text {
            color: rgba(255,255,255,.76);
            font-size: 1.08rem;
            max-width: 650px;
            line-height: 1.65;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .btn-premium {
            border-radius: 16px;
            padding: 14px 22px;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(37,99,235,.25);
        }

        .btn-soft-light {
            background: rgba(255,255,255,.08);
            color: white;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 16px;
            padding: 14px 22px;
            font-weight: 600;
        }

        .btn-soft-light:hover {
            background: rgba(255,255,255,.14);
            color: white;
        }

        .hero-side-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
            height: 100%;
        }

        .hero-metric {
            background: linear-gradient(180deg, rgba(255,255,255,.08) 0%, rgba(255,255,255,.05) 100%);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 22px;
            padding: 22px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-metric .value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            word-break: break-word;
        }

        .hero-metric .label {
            color: rgba(255,255,255,.68);
            font-size: .95rem;
        }

        .quick-access-card {
            background: linear-gradient(180deg, rgba(255,255,255,.08) 0%, rgba(255,255,255,.05) 100%);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 18px 35px rgba(0,0,0,.12);
        }

        .quick-access-card .btn {
            border-radius: 16px;
            padding: 14px 18px;
            font-weight: 700;
            white-space: normal;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .section-subtitle {
            color: #64748b;
            max-width: 760px;
        }

        .premium-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 18px 35px rgba(15,23,42,.06);
        }

        .schedule-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            padding: 24px;
            height: 100%;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .schedule-card:hover,
        .prize-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(15,23,42,.10);
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: .88rem;
            flex-wrap: wrap;
        }

        .prize-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            overflow: hidden;
            height: 100%;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 12px 28px rgba(15,23,42,.05);
        }

        .prize-image {
            width: 100%;
            height: 260px;
            object-fit: cover;
            background: #f1f5f9;
            cursor: pointer;
        }

        .prize-placeholder {
            width: 100%;
            height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 2rem;
        }

        .cta-section {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 26px 50px rgba(15,23,42,.18);
        }

        .footer-public {
            background: #020617;
            color: rgba(255,255,255,.72);
        }

        .navbar-brand-text {
            min-width: 0;
        }

        .navbar-brand-text div {
            word-break: break-word;
        }

        /* TABLET */
        @media (max-width: 991.98px) {
            .hero {
                padding: 56px 0 42px;
            }

            .hero-card {
                padding: 28px;
            }

            .hero-title {
                font-size: clamp(2rem, 9vw, 3.3rem);
            }

            .hero-text {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .cta-section {
                padding: 32px 24px;
            }
        }

        /* MOBILE */
        @media (max-width: 767.98px) {
            .navbar-public .container {
                padding-top: 14px !important;
                padding-bottom: 14px !important;
            }

            .brand-badge {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .hero {
                padding: 42px 0 30px;
            }

            .hero-card {
                padding: 22px;
                border-radius: 22px;
            }

            .hero-title {
                font-size: clamp(1.8rem, 9vw, 2.6rem);
                line-height: 1.08;
            }

            .hero-text {
                font-size: .97rem;
                line-height: 1.6;
            }

            .hero-actions {
                flex-direction: column;
                gap: 12px;
            }

            .hero-actions .btn,
            .quick-access-card .btn,
            .cta-section .btn {
                width: 100%;
            }

            .hero-metric {
                min-height: auto;
                padding: 18px;
                border-radius: 18px;
            }

            .hero-metric .value {
                font-size: 1.5rem;
            }

            .quick-access-card {
                padding: 18px;
                border-radius: 20px;
            }

            .schedule-card,
            .prize-card .p-4,
            .premium-card,
            .cta-section {
                border-radius: 20px;
            }

            .schedule-card {
                padding: 20px;
            }

            .date-badge {
                font-size: .82rem;
                padding: 7px 12px;
            }

            .prize-image,
            .prize-placeholder {
                height: 220px;
            }

            .section-title {
                font-size: 1.45rem;
            }

            .section-subtitle {
                font-size: .95rem;
            }

            .cta-section {
                padding: 24px 18px;
            }

            .footer-public .container {
                text-align: center;
            }

            .modal-dialog {
                margin: 1rem;
            }
        }

        /* VERY SMALL DEVICES */
        @media (max-width: 575.98px) {
            .container {
                padding-left: 16px;
                padding-right: 16px;
            }

            .hero-card {
                padding: 18px;
            }

            .hero-title {
                font-size: 1.65rem;
            }

            .btn-premium,
            .btn-soft-light {
                padding: 12px 16px;
                border-radius: 14px;
            }

            .prize-image,
            .prize-placeholder {
                height: 200px;
            }

            .modal-body {
                padding: 12px !important;
            }

            #imagePreviewTag {
                max-height: 60vh !important;
            }
        }
        .landing-logo {
            max-height: 70px;
            transition: transform 0.3s ease;
        }

        .landing-logo:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-public">
        <div class="container py-3">
            <a href="#" class="navbar-brand d-flex align-items-center gap-3 m-0 text-decoration-none">
                <img 
                    src="https://uiaf.gov.co/sites/default/files/styles/large/public/inline-images/coljuegos-logo_0.png"
                    alt="Coljuegos"
                    class="landing-logo"
                    style="background: rgba(255,255,255,0.95); padding: 5px 10px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);"
                >
                <img 
                    src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                    alt="Coopserp"
                    class="landing-logo"
                >
                <div class="navbar-brand-text">
                    <div class="fw-bold text-white">Sorteos OFICIALES</div>
                    <div style="font-size:.85rem; color:rgba(255,255,255,.55);">
                        Premios, fechas y consulta de boletas
                    </div>
                </div>
            </a>

            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPublicContent"
                aria-controls="navbarPublicContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="text-white fs-3">
                    <i class="bi bi-list"></i>
                </span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarPublicContent">
                <div class="ms-auto d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3">
                    <a href="#sorteos" class="nav-link-public">Sorteos</a>
                    <a href="#premios" class="nav-link-public">Premios</a>
                    <a href="{{ route('resultados.index') }}" class="nav-link-public">Resultados</a>
                    <a href="{{ route('public.detalle') }}" class="nav-link-public">Mapa de Boletas</a>
                    <a href="{{ route('consulta.boletas.form') }}" class="btn btn-primary btn-premium">
                        <i class="bi bi-search me-1"></i> Consultar boletas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="hero-card d-flex flex-column justify-content-center">
                        <span class="badge rounded-pill text-bg-light mb-3 px-3 py-2 align-self-start">
                            Plataforma oficial de sorteos
                        </span>

                        <h1 class="hero-title mb-3">
                            {{ $textoPromocional }}
                        </h1>

                        <p class="hero-text mb-0">
                            Participa en nuestros sorteos oficiales, consulta tus boletas en línea y conoce los premios disponibles. Entre más créditos realices, más oportunidades tendrás de acumular boletas y participar por los premios publicados.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('consulta.boletas.form') }}" class="btn btn-primary btn-premium">
                                <i class="bi bi-search me-1"></i> Consultar mis boletas
                            </a>

                            <a href="#premios" class="btn btn-soft-light">
                                <i class="bi bi-gift me-1"></i> Ver premios
                            </a>

                            <a href="{{ route('resultados.index') }}" class="btn btn-soft-light">
                                <i class="bi bi-trophy me-1"></i> Ver resultados
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-side-stack">
                        <div class="hero-metric">
                            <div class="value">{{ $sorteos->count() }}</div>
                            <div class="label">Sorteos programados</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="hero-metric h-100">
                                    <div class="value">{{ $premios->count() }}</div>
                                    <div class="label">Premios activos</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="hero-metric h-100">
                                    <div class="value">24/7</div>
                                    <div class="label">Consulta disponible</div>
                                </div>
                            </div>
                        </div>

                        <div class="quick-access-card mt-auto">
                            <div class="label mb-3">Acceso rápido</div>
                            <a href="#premios" class="btn btn-light w-100 mb-3">
                                Ir a consulta de premios
                            </a>
                            <a href="{{ route('consulta.boletas.form') }}" class="btn btn-light w-100">
                                Ir a consulta de boletas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="sorteos" class="py-5">
        <div class="container">
            <div class="mb-4">
                <h2 class="section-title mb-2">Próximos sorteos</h2>
                <p class="section-subtitle mb-0">
                    Consulta las fechas programadas y prepárate para participar con tus boletas.
                </p>
            </div>

            <div class="row g-4">
                @forelse($sorteos as $sorteo)
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="schedule-card">
                            <div class="date-badge mb-3">
                                <i class="bi bi-calendar-event"></i>
                                {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
                            </div>
                            
                            <h5 class="fw-bold mb-2 d-flex align-items-center gap-2 flex-wrap">
                                <span>{{ $sorteo->nombre }}</span>
                                <span class="text-muted fw-bold">Reprogramado:</span>

                                @if($sorteo->es_reprogramado)
                                    <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-2">Sí</span>
                                @else
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">No</span>
                                @endif
                            </h5>

                            <div class="text-muted mb-2">
                                <i class="bi bi-award me-1"></i>
                                {{ $sorteo->loteria ?: 'Lotería por definir' }}
                            </div>

                            @if($sorteo->observaciones)
                                <p class="text-muted mb-0">
                                    {{ \Illuminate\Support\Str::limit($sorteo->observaciones, 110) }}
                                </p>
                            @else
                                <p class="text-muted mb-0">
                                    Sorteo programado y listo para participación.
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="premium-card p-4 text-center">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <h5 class="fw-bold mt-3">No hay sorteos programados</h5>
                            <p class="text-muted mb-0">
                                Próximamente se publicarán nuevas fechas.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="premios" class="py-5">
        <div class="container">
            <div class="mb-4">
                <h2 class="section-title mb-2">Premios destacados</h2>
                <p class="section-subtitle mb-0">
                    Estos son los premios iniciales publicados para los sorteos activos. Haz más créditos para conseguir más boletas y aumentar tus oportunidades de ganar.
                </p>
            </div>

            <div class="row g-4">
                @forelse($premios as $premio)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="prize-card">
                            @if($premio->imagen)
                                <img
                                    src="{{ asset('storage/' . $premio->imagen) }}"
                                    alt="{{ $premio->titulo }}"
                                    class="prize-image preview-image"
                                    data-image="{{ asset('storage/' . $premio->imagen) }}"
                                    data-title="{{ $premio->titulo }}"
                                >
                            @else
                                <div class="prize-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2 flex-wrap">
                                    <h5 class="fw-bold mb-0">{{ $premio->titulo }}</h5>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        #{{ $premio->orden }}
                                    </span>
                                </div>

                                <div class="text-muted small mb-3">
                                    <i class="bi bi-calendar2-event me-1"></i>
                                    {{ $premio->sorteo?->nombre ?? 'Sin sorteo asignado' }}
                                </div>

                                <p class="text-muted mb-0">
                                    {{ $premio->descripcion ?: 'Premio activo del sistema de sorteos.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="premium-card p-4 text-center">
                            <i class="bi bi-gift fs-1 text-muted"></i>
                            <h5 class="fw-bold mt-3">No hay premios activos</h5>
                            <p class="text-muted mb-0">
                                Muy pronto verás aquí los premios disponibles.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">¿Quieres revisar tus boletas ahora mismo?</h2>
                        <p class="mb-0 text-white-50">
                            Accede al módulo de consulta y verifica rápidamente tus números registrados.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('consulta.boletas.form') }}" class="btn btn-light btn-premium">
                            <i class="bi bi-arrow-right-circle me-1"></i> Ir a consulta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-public py-4 mt-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>Sistema de sorteos COOPSERP · Plataforma pública · <span class="fw-bold">AUTORIZA COLJUEGOS</span></div>
            <div>
                <a href="{{ route('consulta.boletas.form') }}" class="text-decoration-none text-light">
                    Consultar boletas
                </a>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewTitle">Vista previa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img id="imagePreviewTag" src="" alt="" class="img-fluid rounded-4" style="max-height: 75vh;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewImages = document.querySelectorAll('.preview-image');
            const modalElement = document.getElementById('imagePreviewModal');

            if (!modalElement) return;

            const modal = new bootstrap.Modal(modalElement);
            const modalImg = document.getElementById('imagePreviewTag');
            const modalTitle = document.getElementById('imagePreviewTitle');

            previewImages.forEach(img => {
                img.addEventListener('click', function () {
                    modalImg.src = this.dataset.image;
                    modalImg.alt = this.dataset.title || 'Vista previa';
                    modalTitle.textContent = this.dataset.title || 'Vista previa';
                    modal.show();
                });
            });
        });
    </script>
</body>
</html>