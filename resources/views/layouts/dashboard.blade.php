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

{{-- theme-light / theme-dark se manejan desde dashboard.js --}}
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
                <span class="sidebar-user-role">Administrador</span>
            </div>
        </div>

        {{-- NAV --}}
        <nav class="sidebar-nav">

            <span class="sidebar-section-label">Dashboard</span>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>📊</span>
                <span>Resumen</span>
            </a>

            <span class="sidebar-section-label">Gestión</span>

            <a href="{{ route('productos.index') }}"
               class="sidebar-link {{ request()->is('productos*') ? 'active' : '' }}">
                <span>📦</span>
                <span>Productos</span>
            </a>

            {{-- Futuras secciones --}}
            {{-- <a href="#" class="sidebar-link"><span>🧾</span><span>Órdenes</span></a> --}}
            {{-- <a href="#" class="sidebar-link"><span>🤝</span><span>Afiliados</span></a> --}}
        </nav>

    </div>

    {{-- ===============================
         CONTENIDO PRINCIPAL
    =============================== --}}
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

                {{-- Botón de Modo Claro/Oscuro --}}
                <button id="theme-toggle" class="topbar-btn" type="button">
                    <span class="theme-icon">🌞</span>
                    <span class="theme-label">Modo claro</span>
                </button>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="topbar-btn danger" type="submit">
                        Cerrar sesión
                    </button>
                </form>

            </div>
        </header>

        {{-- WRAPPER DEL CONTENIDO --}}
        <main class="content-wrapper">
            <section class="content-inner">
                @yield('content')
            </section>
        </main>

    </div>

    {{-- ===============================
         CHARTS
    =============================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>


</body>
</html>
