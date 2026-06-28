<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ $title ?? 'Panel Administrativo' }}
    </title>

    <link
        rel="shortcut icon"
        href="{{ asset('logoo.png') }}"
        type="image/png"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --sidebar-bg: #111827;
            --sidebar-bg-2: #0f172a;
            --sidebar-hover: #1f2937;
            --sidebar-active: #2563eb;

            --page-bg: #f3f4f6;
            --card-bg: #ffffff;

            --text-main: #111827;
            --text-muted: #6b7280;

            --border-soft: #e5e7eb;
            --border-strong: #d1d5db;

            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #eff6ff;

            --success-soft: #dcfce7;
            --success-text: #166534;

            --warning-soft: #fef3c7;
            --warning-text: #92400e;

            --danger-soft: #fee2e2;
            --danger-text: #991b1b;

            --shadow-soft: 0 12px 30px rgba(15, 23, 42, .06);
            --shadow-medium: 0 18px 38px rgba(15, 23, 42, .10);
            --shadow-primary: 0 18px 35px rgba(37, 99, 235, .28);

            --radius-sm: 12px;
            --radius-md: 18px;
            --radius-lg: 24px;
        }

        body.dark {
            --sidebar-bg: #020617;
            --sidebar-bg-2: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #3b82f6;

            --page-bg: #020617;
            --card-bg: #0f172a;

            --text-main: #e5e7eb;
            --text-muted: #94a3b8;

            --border-soft: #1e293b;
            --border-strong: #334155;

            --primary-soft: rgba(59, 130, 246, .16);

            --shadow-soft: 0 12px 30px rgba(0, 0, 0, .22);
            --shadow-medium: 0 18px 38px rgba(0, 0, 0, .32);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .08), transparent 28%),
                var(--page-bg);
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-main);
        }

        a {
            text-decoration: none;
        }

        .admin-wrapper {
            height: 100vh;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        .sidebar {
            width: 280px;
            flex-shrink: 0;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .22), transparent 34%),
                linear-gradient(180deg, var(--sidebar-bg-2) 0%, var(--sidebar-bg) 100%);
            color: #fff;
            height: 100vh;
            position: sticky;
            top: 0;
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            border-right: 1px solid rgba(255, 255, 255, .06);
        }

        .sidebar::-webkit-scrollbar,
        .main-content::-webkit-scrollbar {
            width: 7px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .16);
            border-radius: 999px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .brand-box {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 22px;
            padding: 18px;
            margin-bottom: 26px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06);
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .coopserp-logo {
            max-width: 178px;
            max-height: 72px;
            object-fit: contain;
            transition: transform .25s ease, filter .25s ease;
        }

        .coopserp-logo:hover {
            transform: scale(1.04);
            filter: drop-shadow(0 0 12px rgba(37, 99, 235, .48));
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 4px;
            line-height: 1.25;
        }

        .brand-subtitle {
            font-size: .82rem;
            color: rgba(255, 255, 255, .68);
            margin: 0;
        }

        .menu-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .10em;
            color: rgba(255, 255, 255, .44);
            margin-bottom: 12px;
            padding-left: 12px;
            font-weight: 800;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .82);
            border-radius: 16px;
            padding: 12px 14px;
            font-weight: 650;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            transition: all .16s ease;
            position: relative;
        }

        .sidebar .nav-link i {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 12px 26px rgba(37, 99, 235, .32);
        }

        .sidebar .nav-link.active::after {
            content: "";
            position: absolute;
            right: 12px;
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .85);
        }

        .sidebar-section {
            margin-bottom: 10px;
        }

        .sidebar-user {
            padding-top: 20px;
        }

        .user-card {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 20px;
            padding: 14px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .28);
        }

        /*
        |--------------------------------------------------------------------------
        | Main
        |--------------------------------------------------------------------------
        */

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 28px;
            overflow-y: auto;
            height: 100vh;
        }

        .topbar {
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, .90);
            border-radius: 24px;
            padding: 18px 22px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-soft);
        }

        body.dark .topbar {
            background: rgba(15, 23, 42, .82);
            border-color: rgba(51, 65, 85, .75);
        }

        .page-title {
            font-size: 1.55rem;
            font-weight: 850;
            margin: 0;
            letter-spacing: -.02em;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin: 4px 0 0;
            font-size: .94rem;
        }

        .topbar-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */

        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
        }

        .content-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-soft);
            padding: 20px 24px;
        }

        .content-card .card-body {
            padding: 24px;
        }

        .stats-box {
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, .22), transparent 32%),
                linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: var(--shadow-primary);
        }

        .stats-box h3 {
            font-size: 2rem;
            margin: 0;
            font-weight: 850;
            letter-spacing: -.02em;
        }

        .stats-box p {
            margin: 0 0 4px;
            opacity: .88;
            font-weight: 650;
        }

        .stats-box small {
            opacity: .78;
        }

        /*
        |--------------------------------------------------------------------------
        | Buttons
        |--------------------------------------------------------------------------
        */

        .btn {
            font-weight: 650;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: 0;
            border-radius: 14px;
            padding: 10px 18px;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .20);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(37, 99, 235, .26);
        }

        .btn-outline-secondary,
        .btn-outline-primary,
        .btn-outline-danger,
        .btn-outline-dark,
        .btn-light,
        .btn-warning,
        .btn-success,
        .btn-danger {
            border-radius: 14px;
            font-weight: 650;
        }

        .btn-light {
            background: #f8fafc;
            border-color: #e5e7eb;
        }

        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        .table {
            vertical-align: middle;
        }

        .table thead th {
            border-bottom: 1px solid var(--border-soft);
            color: var(--text-muted);
            font-size: .78rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }

        .table tbody td {
            border-color: var(--border-soft);
        }

        .table tbody tr {
            transition: background .15s ease;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        /*
        |--------------------------------------------------------------------------
        | Forms
        |--------------------------------------------------------------------------
        */

        .form-control,
        .form-select,
        .form-check-input {
            border-radius: 14px;
            padding: .75rem .95rem;
            border-color: var(--border-soft);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .form-label {
            font-weight: 700;
            margin-bottom: .45rem;
        }

        /*
        |--------------------------------------------------------------------------
        | Badges
        |--------------------------------------------------------------------------
        */

        .badge-soft,
        .badge-programado,
        .badge-ejecutado,
        .badge-cancelado {
            padding: .55rem .8rem;
            border-radius: 999px;
            font-weight: 750;
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

        /*
        |--------------------------------------------------------------------------
        | Alerts
        |--------------------------------------------------------------------------
        */

        .alert {
            border-radius: 18px;
            border: 0;
            box-shadow: var(--shadow-soft);
        }

        /*
        |--------------------------------------------------------------------------
        | Modals
        |--------------------------------------------------------------------------
        */

        .modal-content {
            border-radius: 24px;
            border: 0;
            box-shadow: var(--shadow-medium);
        }

        .modal-header {
            border-bottom-color: var(--border-soft);
        }

        .modal-footer {
            border-top-color: var(--border-soft);
        }

        .image-preview-modal .modal-content {
            overflow: hidden;
        }

        .image-preview-header {
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, .18), transparent 32%),
                linear-gradient(135deg, #1d4ed8 0%, #2563eb 48%, #111827 100%);
            color: #fff;
            border: 0;
        }

        .image-preview-body {
            background: #0f172a;
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview-body img {
            max-height: 78vh;
            max-width: 100%;
            object-fit: contain;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .35);
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1050;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 14px;
            width: 44px;
            height: 44px;
            font-size: 1.25rem;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .48);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 992px) {
            .sidebar-toggle {
                display: flex;
            }

            .sidebar {
                position: fixed;
                left: -300px;
                top: 0;
                z-index: 1045;
                transition: left .28s cubic-bezier(.4, 0, .2, 1);
                box-shadow: none;
            }

            .sidebar.open {
                left: 0;
                box-shadow: 4px 0 34px rgba(0, 0, 0, .34);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main-content {
                padding: 20px 16px;
                padding-top: 74px;
            }

            .topbar {
                border-radius: 18px;
                padding: 16px;
            }

            .page-title {
                font-size: 1.28rem;
            }

            .page-subtitle {
                font-size: .88rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 16px 12px;
                padding-top: 70px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }

            .topbar-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .topbar-actions .btn,
            .topbar-actions a,
            .topbar-actions button {
                width: 100%;
            }

            .content-card .card-header,
            .content-card .card-body {
                padding: 18px;
            }

            .brand-box {
                padding: 16px;
            }

            .coopserp-logo {
                max-width: 155px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <button
        class="sidebar-toggle"
        id="sidebarToggle"
        aria-label="Abrir menú"
        type="button"
    >
        <i class="bi bi-list"></i>
    </button>

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>

    <div class="admin-wrapper">

        <aside
            class="sidebar"
            id="sidebar"
        >

            <div class="sidebar-section">

                <div class="brand-box text-center">

                    <div class="logo-wrapper">
                        <img
                            src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                            alt="Coopserp Logo"
                            class="coopserp-logo"
                        >
                    </div>

                    <div class="brand-title">
                        Sorteos Admin Coopserp
                    </div>

                    <p class="brand-subtitle">
                        Panel administrativo del sistema
                    </p>

                </div>

                <div class="menu-label">
                    Menú principal
                </div>

                <nav class="nav flex-column">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>

                    <a
                        href="{{ route('admin.sorteos.index') }}"
                        class="nav-link {{ request()->routeIs('admin.sorteos.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-calendar2-event"></i>
                        <span>Sorteos</span>
                    </a>

                    <a
                        href="{{ route('admin.asociados.index') }}"
                        class="nav-link {{ request()->routeIs('admin.asociados.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-people"></i>
                        <span>Participantes</span>
                    </a>

                    <a
                        href="{{ route('admin.premios.index') }}"
                        class="nav-link {{ request()->routeIs('admin.premios.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-gift"></i>
                        <span>Premios</span>
                    </a>

                    <a
                        href="{{ route('admin.boletas.index') }}"
                        class="nav-link {{ request()->routeIs('admin.boletas.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Boletas</span>
                    </a>

                    <a
                        href="{{ route('admin.ganadores.index') }}"
                        class="nav-link {{ request()->routeIs('admin.ganadores.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-trophy"></i>
                        <span>Ganadores</span>
                    </a>

                    <a
                        href="{{ route('admin.reportes.index') }}"
                        class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Reportes</span>
                    </a>

                </nav>

            </div>

            <div class="sidebar-user mt-auto">

                <div class="user-card">

                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>

                        <div
                            class="flex-grow-1"
                            style="min-width: 0;"
                        >
                            <div class="fw-bold small text-truncate">
                                {{ auth()->user()->name ?? 'Usuario' }}
                            </div>

                            <div
                                class="small text-truncate"
                                style="opacity: .7;"
                            >
                                {{ auth()->user()->email ?? 'Sin correo' }}
                            </div>
                        </div>

                    </div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-3"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-sm btn-outline-light w-100"
                        >
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Cerrar sesión
                        </button>
                    </form>

                </div>

            </div>

        </aside>

        <main class="main-content">

            <div class="topbar d-flex justify-content-between align-items-center">

                <div>
                    <h1 class="page-title">
                        {{ $title ?? 'Panel Administrativo' }}
                    </h1>

                    <p class="page-subtitle">
                        {{ $subtitle ?? 'Administra y controla el sistema de sorteos.' }}
                    </p>
                </div>

                <div class="topbar-actions">
                    @yield('topbar_actions')
                </div>

            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Hay errores en el formulario:
                    </div>

                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    {{-- MODAL GLOBAL DE IMÁGENES --}}
    <div
        class="modal fade image-preview-modal"
        id="imagePreviewModal"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-xl">

            <div class="modal-content">

                <div class="modal-header image-preview-header">

                    <div>
                        <h5
                            class="modal-title fw-bold mb-1"
                            id="imagePreviewTitle"
                        >
                            Vista previa
                        </h5>

                        <div class="small opacity-75">
                            Imagen del registro seleccionado.
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>

                <div class="modal-body image-preview-body p-3">

                    <img
                        id="imagePreviewTag"
                        src=""
                        alt="Vista previa"
                    >

                </div>

            </div>

        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /*
            |--------------------------------------------------------------------------
            | Sidebar móvil
            |--------------------------------------------------------------------------
            */
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                if (!sidebar || !overlay || !toggle) return;

                sidebar.classList.add('open');
                overlay.classList.add('open');
                toggle.innerHTML = '<i class="bi bi-x-lg"></i>';
            }

            function closeSidebar() {
                if (!sidebar || !overlay || !toggle) return;

                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                toggle.innerHTML = '<i class="bi bi-list"></i>';
            }

            if (toggle && sidebar && overlay) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.contains('open')
                        ? closeSidebar()
                        : openSidebar();
                });

                overlay.addEventListener('click', closeSidebar);

                sidebar.querySelectorAll('.nav-link').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth <= 992) {
                            closeSidebar();
                        }
                    });
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeSidebar();
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Modal global de preview de imágenes
            |--------------------------------------------------------------------------
            | Para usarlo en cualquier vista:
            |
            | <img
            |   class="preview-image"
            |   data-image="URL_IMAGEN"
            |   data-title="Título"
            | >
            */
            const previewImages = document.querySelectorAll('.preview-image');
            const modalElement = document.getElementById('imagePreviewModal');
            const modalImg = document.getElementById('imagePreviewTag');
            const modalTitle = document.getElementById('imagePreviewTitle');

            if (modalElement && modalImg && modalTitle) {
                const imageModal = new bootstrap.Modal(modalElement);

                previewImages.forEach(function (img) {
                    img.addEventListener('click', function () {
                        modalImg.src = this.dataset.image || this.src || '';
                        modalImg.alt = this.dataset.title || 'Vista previa';
                        modalTitle.textContent = this.dataset.title || 'Vista previa';

                        imageModal.show();
                    });
                });

                modalElement.addEventListener('hidden.bs.modal', function () {
                    modalImg.src = '';
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>