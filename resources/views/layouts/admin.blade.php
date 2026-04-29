
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Administrativo' }}</title>
    <link rel="shortcut icon" href="../logoo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --sidebar-active: #2563eb;
            --page-bg: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-soft: #e5e7eb;
        }

        /* DARK MODE */
        body.dark {
            --sidebar-bg: #020617;
            --sidebar-hover: #0f172a;
            --sidebar-active: #3b82f6;
            --page-bg: #020617;
            --card-bg: #0f172a;
            --text-main: #e5e7eb;
            --text-muted: #94a3b8;
            --border-soft: #1e293b;
        }

        body {
            background: var(--page-bg);
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-main);
        }

        .admin-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #fff;
            min-height: 100vh;
            padding: 24px 18px;
            position: sticky;
            top: 0;
        }

        .brand-box {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 28px;
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-size: .88rem;
            color: rgba(255,255,255,.72);
            margin: 0;
        }

        .menu-label {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.45);
            margin-bottom: 12px;
            padding-left: 12px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.84);
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .30);
        }

        .main-content {
            flex: 1;
            padding: 28px;
        }

        .topbar {
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229,231,235,.9);
            border-radius: 22px;
            padding: 18px 22px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .page-title {
            font-size: 1.55rem;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin: 4px 0 0;
        }

        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        }

        .content-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-soft);
            padding: 20px 24px;
        }

        .content-card .card-body {
            padding: 24px;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-outline-secondary,
        .btn-outline-primary,
        .btn-outline-danger,
        .btn-light {
            border-radius: 12px;
        }

        .table {
            vertical-align: middle;
        }

        .table thead th {
            border-bottom: 1px solid var(--border-soft);
            color: var(--text-muted);
            font-size: .88rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .badge-soft {
            padding: .55rem .8rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: .78rem;
        }

        .badge-programado {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-ejecutado {
            background: #dcfce7;
            color: #166534;
        }

        .badge-cancelado {
            background: #fee2e2;
            color: #b91c1c;
        }

        .form-control,
        .form-select,
        .form-check-input {
            border-radius: 12px;
            padding: .75rem .95rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: .45rem;
        }

        .stats-box {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 18px 35px rgba(37, 99, 235, .28);
        }

        .stats-box h3 {
            font-size: 2rem;
            margin: 0;
            font-weight: 800;
        }

        .stats-box p {
            margin: 0;
            opacity: .88;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar-user {
            padding-top: 20px;
        }

        .user-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 14px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }



        .coopserp-logo {
            max-width: 180px; /* MÁS GRANDE */
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        /* efecto hover pro */
        .coopserp-logo:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 0 12px rgba(37, 99, 235, 0.6));
        }
    </style>
</head>
<body>
    
    <div class="admin-wrapper">
        <aside class="sidebar d-flex flex-column">

            <!-- TOP -->
            <div>
                <div class="brand-box text-center">

                    <!-- LOGO -->
                    <div class="logo-wrapper">
                        <img 
                            src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png" 
                            alt="Coopserp Logo"
                            class="coopserp-logo"
                        >
                    </div>

                    <!-- TEXTO -->
                    <div class="brand-title mt-0">Sorteos Admin Coopserp</div>
                    <p class="brand-subtitle">Panel administrativo del sistema</p>

                </div>

                <div class="menu-label">Menú principal</div>

                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.asociados.index') }}"
                    class="nav-link {{ request()->routeIs('admin.asociados.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Asociados</span>
                    </a>

                    <a href="{{ route('admin.import.form') }}"
                    class="nav-link {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>Importar CSV</span>
                    </a>

                    <a href="{{ route('admin.sorteos.index') }}"
                    class="nav-link {{ request()->routeIs('admin.sorteos.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-event"></i>
                        <span>Sorteos</span>
                    </a>

                    <a href="{{ route('admin.lineas.index') }}"
                    class="nav-link {{ request()->routeIs('admin.lineas.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3"></i>
                        <span>Líneas</span>
                    </a>

                    <a href="{{ route('admin.premios.index') }}"
                    class="nav-link {{ request()->routeIs('admin.premios.*') ? 'active' : '' }}">
                        <i class="bi bi-gift"></i>
                        <span>Premios</span>
                    </a>

                    <a href="{{ route('admin.boletas.index') }}"
                    class="nav-link {{ request()->routeIs('admin.boletas.*') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Boletas</span>
                    </a>

                    <a href="{{ route('admin.ganadores.index') }}"
                    class="nav-link {{ request()->routeIs('admin.ganadores.*') ? 'active' : '' }}">
                        <i class="bi bi-trophy"></i>
                        <span>Ganadores</span>
                    </a>
                    

                    <a href="{{ route('admin.configuracion.edit') }}"
                    class="nav-link {{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Configuración</span>
                    </a>
                </nav>
            </div>

            <!-- BOTTOM USER -->
            <div class="sidebar-user mt-auto">
                <div class="user-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="flex-grow-1">
                            <div class="fw-bold small">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="small">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button class="btn btn-sm btn-outline-light w-100">
                            <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

        </aside>
        

        <main class="main-content">
            <div class="topbar d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">{{ $title ?? 'Panel Administrativo' }}</h1>
                    <p class="page-subtitle">{{ $subtitle ?? 'Administra y controla el sistema de sorteos.' }}</p>
                </div>
                <div>
                    @yield('topbar_actions')
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4">
                    <strong>Hay errores en el formulario:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    @stack('scripts')
</body>
</html>