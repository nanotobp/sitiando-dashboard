<nav class="sidebar-nav">

    <span class="sidebar-section-label">Dashboard</span>

    {{-- Dashboard (todos los roles) --}}
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span>📊</span>
        <span>Resumen</span>
    </a>


    <span class="sidebar-section-label">Ventas</span>

    {{-- Orders (admin, manager, seller) --}}
    @if(auth()->user()->hasAnyRole('admin','manager','seller'))
    <a href="{{ route('admin.orders.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <span>🧾</span>
        <span>Órdenes</span>
    </a>
    @endif


    <span class="sidebar-section-label">Catálogo</span>

    {{-- Productos --}}
    @if(auth()->user()->hasAnyRole('admin','manager'))
    <a href="{{ route('admin.products.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <span>📦</span>
        <span>Productos</span>
    </a>
    @endif


    <span class="sidebar-section-label">Usuarios</span>

    {{-- Users (solo admin) --}}
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('admin.users.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <span>👤</span>
        <span>Usuarios</span>
    </a>
    @endif

</nav>
