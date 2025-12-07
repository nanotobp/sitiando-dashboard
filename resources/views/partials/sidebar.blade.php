<div class="sidebar" id="sidebar">
    
    <div class="text-center mb-4">
        <h3 class="text-white fw-bold">Sitiando</h3>
    </div>

    {{-- SUPERADMIN --}}
    @if(Auth::user()->role === 'superadmin')
        <div class="menu-item" onclick="location.href='/dashboard'">
            <i class="fa fa-home"></i>
            <span class="menu-label">Dashboard</span>
        </div>

        <div class="menu-item" onclick="location.href='/ventas'">
            <i class="fa fa-shopping-cart"></i>
            <span class="menu-label">Ventas</span>
        </div>

        <div class="menu-item" onclick="location.href='/productos'">
            <i class="fa fa-box"></i>
            <span class="menu-label">Productos</span>
        </div>

        <div class="menu-item" onclick="location.href='/categorias'">
            <i class="fa fa-tags"></i>
            <span class="menu-label">Categorías</span>
        </div>
    @endif

    {{-- VENDEDOR --}}
    @if(Auth::user()->role === 'vendedor')
        <div class="menu-item" onclick="location.href='/ventas'">
            <i class="fa fa-shopping-cart"></i>
            <span class="menu-label">Ventas</span>
        </div>
    @endif

    {{-- OPERADOR --}}
    @if(Auth::user()->role === 'operador')
        <div class="menu-item" onclick="location.href='/pedidos'">
            <i class="fa fa-truck"></i>
            <span class="menu-label">Operaciones</span>
        </div>
    @endif

    {{-- ANALISTA --}}
    @if(Auth::user()->role === 'analista')
        <div class="menu-item" onclick="location.href='/reportes'">
            <i class="fa fa-chart-bar"></i>
            <span class="menu-label">Reportes</span>
        </div>
    @endif

</div>
