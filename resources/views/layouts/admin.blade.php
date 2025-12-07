<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') — Sitiando Ecommerce PRO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap base --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Tipografía más limpia --}}
    <style>
        :root {
            --sidebar-width: 260px;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
            background-color: #0f172a;
            color: #e5e7eb;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: radial-gradient(circle at top, #0f172a 0, #020617 55%, #000 100%);
            border-right: 1px solid rgba(148, 163, 184, 0.2);
            padding: 1.25rem 1rem;
            position: sticky;
            top: 0;
            align-self: flex-start;
            min-height: 100vh;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }

        .sidebar-logo {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, #22c55e, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            color: #0b1120;
        }

        .sidebar-title {
            font-weight: 700;
            letter-spacing: .04em;
            font-size: 0.95rem;
            text-transform: uppercase;
            color: #e5e7eb;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .sidebar-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            margin: 1.5rem 0 .5rem;
        }

        .nav-sidebar {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .nav-sidebar li {
            margin-bottom: 4px;
        }

        .nav-sidebar a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: .55rem;
            text-decoration: none;
            color: #9ca3af;
            font-size: 0.86rem;
            transition: all .18s ease-in-out;
        }

        .nav-sidebar a .nav-icon {
            width: 18px;
            text-align: center;
            opacity: .75;
        }

        .nav-sidebar a:hover {
            background: rgba(15, 23, 42, 0.9);
            color: #e5e7eb;
        }

        .nav-sidebar a.active {
            background: linear-gradient(135deg, rgba(34,197,94,0.16), rgba(14,165,233,0.16));
            color: #f9fafb;
            border: 1px solid rgba(34, 197, 94, 0.5);
        }

        .sidebar-footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px dashed rgba(148, 163, 184, 0.3);
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* MAIN AREA */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 100%;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1.5rem;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            background: radial-gradient(circle at top left, #020617 0, #020617 40%, #020617 100%);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #9ca3af;
        }

        .topbar-title span {
            color: #e5e7eb;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .badge-env {
            font-size: 0.7rem;
            padding: .25rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(52, 211, 153, 0.4);
            color: #6ee7b7;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .btn-ghost {
            border-radius: 999px;
            border: 1px solid rgba(55, 65, 81, 0.9);
            background: rgba(15, 23, 42, 0.7);
            color: #e5e7eb;
            padding: .35rem .8rem;
            font-size: 0.8rem;
        }

        .btn-ghost:hover {
            background: rgba(31, 41, 55, 0.9);
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
        }

        .main-content {
            padding: 1.5rem;
        }

        /* Cards tweak */
        .card {
            background: radial-gradient(circle at top left, #020617 0, #020617 45%, #020617 100%);
            border-radius: 1.1rem;
            border: 1px solid rgba(31, 41, 55, 0.9);
            color: #e5e7eb;
        }

        .card-header {
            border-bottom-color: rgba(31, 41, 55, 0.9);
        }

        .card-body {
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                display: none;
            }
            .layout-wrapper {
                flex-direction: column;
            }
            .main-wrapper {
                width: 100%;
            }
        }
    </style>

    @yield('head')
</head>
<body>

<div class="layout-wrapper">

    {{-- SIDEBAR ENTERPRISE --}}
    <aside class="sidebar d-none d-lg-block">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                S
            </div>
            <div>
                <div class="sidebar-title">SITIANDO PRO</div>
                <div class="sidebar-subtitle">Ecommerce Admin Suite</div>
            </div>
        </div>

        <div class="sidebar-section-title">Panel</div>
        <ul class="nav-sidebar">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Operaciones</div>
        <ul class="nav-sidebar">
            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="nav-icon">🧾</span>
                    <span>Órdenes</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}"
                   class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span>
                    <span>Productos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.carts.index') }}"
                   class="{{ request()->routeIs('admin.carts.*') ? 'active' : '' }}">
                    <span class="nav-icon">🛒</span>
                    <span>Carritos activos</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Afiliados</div>
        <ul class="nav-sidebar">
            <li>
                <a href="{{ route('admin.affiliates.index') }}"
                   class="{{ request()->routeIs('admin.affiliates.*') ? 'active' : '' }}">
                    <span class="nav-icon">🤝</span>
                    <span>Afiliados</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.analytics.affiliates') }}"
                   class="{{ request()->routeIs('admin.analytics.affiliates*') ? 'active' : '' }}">
                    <span class="nav-icon">📈</span>
                    <span>Analytics Afiliados</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.payouts.index') }}"
                   class="{{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}">
                    <span class="nav-icon">💸</span>
                    <span>Liquidaciones</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Seguridad</div>
        <ul class="nav-sidebar">
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon">👤</span>
                    <span>Usuarios</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles.index') }}"
                   class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <span class="nav-icon">🛡️</span>
                    <span>Roles & Permisos</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div>Versión: <strong>v1.0.0 PRO</strong></div>
            <div>Railway · PHP 8.4 · PGSQL</div>
        </div>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title">
                Panel <span>@yield('title', 'Dashboard')</span>
            </div>

            <div class="topbar-actions">
                <span class="badge-env">PROD</span>

                <button class="btn btn-ghost btn-sm" type="button" onclick="location.href='{{ route('admin.orders.index') }}'">
                    Ver órdenes
                </button>

                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-ghost btn-sm" type="submit">
                        Cerrar sesión
                    </button>
                </form>

                <span class="theme-toggle" id="themeToggle" title="Toggle theme">◐</span>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script>
    // Mini dark/light toggle (por ahora solo body class toggle si querés luego expandimos)
    document.getElementById('themeToggle')?.addEventListener('click', () => {
        document.body.classList.toggle('theme-light');
    });
</script>

@yield('scripts')

</body>
</html>
