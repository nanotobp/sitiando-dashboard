@extends('layouts.dashboard')

@section('content')

{{-- ============================================
     TÍTULO
============================================ --}}
<h1 class="title">Dashboard general</h1>


{{-- ============================================
     KPI CARDS (con sparklines)
============================================ --}}
<div class="cards">

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Usuarios</h3>
            <p class="kpi-number">{{ $totalUsuarios }}</p>
        </div>
        <div class="kpi-sparkline">
            <canvas id="sparkUsuarios" height="40"></canvas>
        </div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Productos</h3>
            <p class="kpi-number">{{ $totalProductos }}</p>
        </div>
        <div class="kpi-sparkline">
            <canvas id="sparkProductos" height="40"></canvas>
        </div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Órdenes</h3>
            <p class="kpi-number">{{ $totalOrders }}</p>
        </div>
        <div class="kpi-sparkline">
            <canvas id="sparkOrders" height="40"></canvas>
        </div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Afiliados</h3>
            <p class="kpi-number">{{ $totalAffiliates }}</p>
        </div>
        <div class="kpi-sparkline">
            <canvas id="sparkAffiliates" height="40"></canvas>
        </div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Ventas del mes</h3>
            <p class="kpi-number">${{ number_format($ventasMes, 0) }}</p>
        </div>
        <div class="kpi-sparkline">
            <canvas id="sparkVentasMes" height="40"></canvas>
        </div>
    </div>

</div>



{{-- ============================================
     GRÁFICO: CRECIMIENTO DE INGRESOS
============================================ --}}
<h2 class="subtitle">Crecimiento de ingresos</h2>

<div class="card">
    <canvas id="revenueChart" height="120"></canvas>
</div>



{{-- ============================================
     GRÁFICO: RETENCIÓN DE CLIENTES
============================================ --}}
<h2 class="subtitle">Retención de clientes</h2>

<div class="card">
    <canvas id="retentionChart" height="120"></canvas>
</div>



{{-- ============================================
     TABLA – ÚLTIMOS PRODUCTOS
============================================ --}}
<h2 class="subtitle">Últimos productos</h2>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Creado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimosProductos as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>${{ number_format($p->price, 0) }}</td>
                    <td>{{ $p->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No hay productos todavía.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>



{{-- ============================================
     TABLA – ÚLTIMAS ÓRDENES
============================================ --}}
<h2 class="subtitle">Últimas órdenes</h2>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimasOrdenes as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>${{ number_format($o->total, 0) }}</td>
                    <td>{{ $o->status ?? 'pendiente' }}</td>
                    <td>{{ $o->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No hay órdenes todavía.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
