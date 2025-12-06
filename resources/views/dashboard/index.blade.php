@extends('layouts.dashboard')

@section('content')

{{-- TÍTULO --}}
<h1 class="title">Dashboard general</h1>

{{-- ================================
     CARDS KPI (con sparklines)
================================ --}}
<div class="cards">

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Usuarios</h3>
            <span class="kpi-change positive">+12%</span>
        </div>

        <p>{{ $totalUsuarios }}</p>

        <canvas class="sparkline" id="sparkUsuarios"></canvas>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Productos</h3>
            <span class="kpi-change positive">+5%</span>
        </div>

        <p>{{ $totalProductos }}</p>

        <canvas class="sparkline" id="sparkProductos"></canvas>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Órdenes</h3>
            <span class="kpi-change negative">−3%</span>
        </div>

        <p>{{ $totalOrders }}</p>

        <canvas class="sparkline" id="sparkOrders"></canvas>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Afiliados</h3>
            <span class="kpi-change positive">+22%</span>
        </div>

        <p>{{ $totalAffiliates }}</p>

        <canvas class="sparkline" id="sparkAffiliates"></canvas>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Ventas del mes</h3>
            <span class="kpi-change positive">+18%</span>
        </div>

        <p>${{ number_format($ventasMes, 0) }}</p>

        <canvas class="sparkline" id="sparkVentasMes"></canvas>
    </div>

</div>


{{-- ================================
     GRÁFICO: Crecimiento de ingresos
================================ --}}
<h2 class="subtitle">Crecimiento de ingresos</h2>

<div class="card">
    <canvas id="revenueChart" height="120"></canvas>
</div>


{{-- ================================
     GRÁFICO: Retención
================================ --}}
<h2 class="subtitle">Retención de clientes</h2>

<div class="card">
    <canvas id="retentionChart" height="120"></canvas>
</div>


{{-- ================================
     TABLA – Últimos productos
================================ --}}
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


{{-- ================================
     TABLA – Últimas órdenes
================================ --}}
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
