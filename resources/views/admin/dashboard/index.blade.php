@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Resumen general</h1>
    <p class="subtitle-text">
        Visión rápida de las ventas, ingresos y comportamiento de los clientes en Sitiando.
    </p>
</div>

{{-- KPI GRID --}}
<div class="kpi-grid">

    {{-- Ingresos totales --}}
    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Ingresos totales</h3>
            @if(!is_null($chartPayload['kpis']['revenueGrowth']))
                @php
                    $growth = $chartPayload['kpis']['revenueGrowth'];
                    $growthClass = $growth >= 0 ? 'positive' : 'negative';
                    $prefix = $growth >= 0 ? '+' : '';
                @endphp
                <span class="kpi-change {{ $growthClass }}">
                    {{ $prefix . number_format($growth, 1) }}% vs mes anterior
                </span>
            @endif
        </div>
        <div class="kpi-number">
            {{ number_format($totalRevenue, 0, ',', '.') }} Gs
        </div>
        <div class="kpi-sparkline">
            <canvas id="spark-revenue"></canvas>
        </div>
    </div>

    {{-- Órdenes totales --}}
    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Órdenes totales</h3>
            <span class="kpi-change positive">
                {{ $chartPayload['kpis']['totalOrders'] }} en total
            </span>
        </div>
        <div class="kpi-number">
            {{ $totalOrders }}
        </div>
        <div class="kpi-sparkline">
            <canvas id="spark-orders"></canvas>
        </div>
    </div>

    {{-- Ticket promedio --}}
    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Ticket promedio</h3>
            <span class="kpi-change">
                Basado en todas las órdenes
            </span>
        </div>
        <div class="kpi-number">
            {{ number_format($avgTicket, 0, ',', '.') }} Gs
        </div>
        <div class="kpi-sparkline">
            <canvas id="spark-ticket"></canvas>
        </div>
    </div>

    {{-- Conversión --}}
    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Conversión a pago</h3>
            <span class="kpi-change">
                Órdenes pagadas / totales
            </span>
        </div>
        <div class="kpi-number">
            {{ $chartPayload['kpis']['conversionRate'] }}%
        </div>
        <div class="kpi-sparkline">
            <canvas id="spark-conversion"></canvas>
        </div>
    </div>

</div>

{{-- CHARTS --}}
<div class="cards">

    <div class="card chart-card">
        <h3>Ingresos por mes</h3>
        <p class="subtitle-text">Evolución de los ingresos totales en los últimos 12 meses.</p>
        <canvas id="revenueChart"></canvas>
    </div>

    <div class="card chart-card">
        <h3>Órdenes por mes</h3>
        <p class="subtitle-text">Cantidad de órdenes creadas en el período.</p>
        <canvas id="ordersChart"></canvas>
    </div>

</div>

{{-- TOPS + ÚLTIMAS ÓRDENES --}}
<div class="cards">

    {{-- Top productos --}}
    <div class="card table-card">
        <h3>Top productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $p)
                    <tr>
                        <td>{{ $p->product_name }}</td>
                        <td>{{ $p->qty }}</td>
                        <td>{{ number_format($p->revenue, 0, ',', '.') }} Gs</td>
                    </tr>
                @endforeach
                @if($topProducts->isEmpty())
                    <tr><td colspan="3">Sin datos aún.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Últimas órdenes --}}
    <div class="card table-card">
        <h3>Últimas órdenes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }} Gs</td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
                @if($recentOrders->isEmpty())
                    <tr><td colspan="6">Sin órdenes aún.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

</div>

{{-- Payload para charts.js --}}
<script>
    window.dashboardCharts = @json($chartPayload);
</script>

@endsection