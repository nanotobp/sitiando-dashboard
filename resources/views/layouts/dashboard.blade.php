<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sitiando PRO - Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS principal del dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- JS del dashboard (toggle dark/light, sidebar, etc.) --}}
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
</head>

<body class="dashboard-layout theme-light">

    {{-- ===============================
         SIDEBAR
    =============================== --}}
    <div class="sidebar">

        {{-- LOGO --}}
        <div class="sidebar-logo">
            <span class="logo-dot"></span>
            <span class="logo-text">Sitiando</span>
        </div>

        {{-- USER INFO --}}
        <div class="sidebar-user">
            <div class="avatar-circle">
                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>

            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                <span class="sidebar-user-role">
                    {{ auth()->user()->roles->first()->name ?? 'Usuario' }}
                </span>
            </div>
        </div>

        {{-- NAV --}}
        <nav class="sidebar-nav">

            {{-- DASHBOARD --}}
            <span class="sidebar-section-label">Dashboard</span>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>📊</span>
                <span>Resumen</span>
            </a>

            {{-- GESTIÓN GENERAL --}}
            <span class="sidebar-section-label">Gestión</span>

            {{-- PRODUCTOS --}}
            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                <span>📦</span>
                <span>Productos</span>
            </a>

            {{-- ÓRDENES --}}
            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <span>🧾</span>
                <span>Órdenes</span>
            </a>

            {{-- USUARIOS --}}
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <span>👤</span>
                <span>Usuarios</span>
            </a>

            {{-- ROLES --}}
            <a href="{{ route('admin.roles.index') }}"
               class="sidebar-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                <span>🛡️</span>
                <span>Roles</span>
            </a>

            {{-- CARTS --}}
            <a href="{{ route('admin.carts.index') }}"
               class="sidebar-link {{ request()->is('admin/carts*') ? 'active' : '' }}">
                <span>🛒</span>
                <span>Carritos</span>
            </a>

            {{-- PAYOUTS --}}
            <a href="{{ route('admin.payouts.index') }}"
               class="sidebar-link {{ request()->is('admin/payouts*') ? 'active' : '' }}">
                <span>💸</span>
                <span>Payouts</span>
            </a>

            {{-- ANALYTICS AFILIADOS --}}
            <a href="{{ route('admin.analytics.affiliates') }}"
               class="sidebar-link {{ request()->is('admin/analytics/affiliates*') ? 'active' : '' }}">
                <span>📈</span>
                <span>Analytics de Afiliados</span>
            </a>

        </nav>

    </div>

    {{-- =============== MAIN WRAPPER ================= --}}
    <div class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">

            <div class="topbar-left">
                <div class="breadcrumb">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="breadcrumb-current">Resumen general</span>
                </div>
            </div>

            <div class="topbar-right">

                {{-- Toggle Light/Dark --}}
                <button id="theme-toggle" class="topbar-btn" type="button">
                    <span class="theme-icon">🌞</span>
                    <span class="theme-label">Modo claro</span>
                </button>

                {{-- LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="topbar-btn danger" type="submit">
                        Cerrar sesión
                    </button>
                </form>

            </div>
        </header>

        <main class="content-wrapper">
            <section class="content-inner">
                @yield('content')
            </section>
        </main>

    </div>

    {{-- CHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>

</body>
</html>
