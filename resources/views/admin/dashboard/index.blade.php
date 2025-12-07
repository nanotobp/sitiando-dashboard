@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
    $k = $kpis ?? [];

    $totalUsers        = $k['total_users']        ?? 0;
    $totalAffiliates   = $k['total_affiliates']   ?? 0;
    $totalOrders       = $k['total_orders']       ?? 0;
    $totalSales        = $k['total_sales']        ?? 0;
    $totalCommissions  = $k['total_commissions']  ?? 0;
    $pendingPayouts    = $k['pending_payouts']    ?? 0;
    $conversionRate    = $k['conversion_rate']    ?? 0;
    $avgOrderValue     = $k['avg_order_value']    ?? 0;
@endphp

<div class="container-fluid">

    {{-- ROW 1: KPIs PRINCIPALES --}}
    <div class="row g-3 mb-3">

        <div class="col-12 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Ingresos totales</span>
                        <span class="small text-success">PRO</span>
                    </div>
                    <h2 class="mb-0">
                        ${{ number_format($totalSales, 0) }}
                    </h2>
                    <p class="text-muted small mb-0 mt-1">
                        Ticket promedio: ${{ number_format($avgOrderValue, 0) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Órdenes</span>
                        <span class="small text-info">Últimos 30 días</span>
                    </div>
                    <h2 class="mb-0">
                        {{ number_format($totalOrders, 0) }}
                    </h2>
                    <p class="text-muted small mb-0 mt-1">
                        Conversión: {{ number_format($conversionRate, 2) }}%
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Afiliados activos</span>
                        <span class="small text-warning">Programa</span>
                    </div>
                    <h2 class="mb-0">
                        {{ number_format($totalAffiliates, 0) }}
                    </h2>
                    <p class="text-muted small mb-0 mt-1">
                        Comisiones: ${{ number_format($totalCommissions, 0) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Usuarios sistema</span>
                        <span class="small text-secondary">Staff + clientes</span>
                    </div>
                    <h2 class="mb-0">
                        {{ number_format($totalUsers, 0) }}
                    </h2>
                    <p class="text-muted small mb-0 mt-1">
                        Pagos pendientes: ${{ number_format($pendingPayouts, 0) }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- ROW 2: GRÁFICOS PRINCIPALES --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">Ventas últimos 30 días</span>
                    <span class="small text-muted">Monto diario</span>
                </div>
                <div class="card-body">
                    <canvas id="chartSales" style="min-height: 260px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">Órdenes últimos 30 días</span>
                    <span class="small text-muted">Cantidad diaria</span>
                </div>
                <div class="card-body">
                    <canvas id="chartOrders" style="min-height: 260px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: LISTAS / TABLAS --}}
    <div class="row g-3">
        {{-- ÓRDENES RECIENTES --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">Órdenes recientes</span>
                    <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none">
                        Ver todas →
                    </a>
                </div>
                <div class="card-body">

                    @if($recent_orders->isEmpty())
                        <p class="text-muted small mb-0">No hay órdenes recientes.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 table-dark table-borderless">
                                <thead class="small text-muted">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'N/D' }}</td>
                                            <td>${{ number_format($order->total, 0) }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $order->status ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at?->format('d/m H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- TOP AFILIADOS --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold">Top Afiliados por comisión</span>
                    <a href="{{ route('admin.analytics.affiliates') }}" class="small text-decoration-none">
                        Ver analytics →
                    </a>
                </div>
                <div class="card-body">

                    @if($top_affiliates->isEmpty())
                        <p class="text-muted small mb-0">Aún no hay suficientes datos de afiliados.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 table-dark table-borderless">
                                <thead class="small text-muted">
                                    <tr>
                                        <th>Afiliado</th>
                                        <th>Órdenes</th>
                                        <th>Comisión total</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach($top_affiliates as $aff)
                                        <tr>
                                            <td>{{ $aff->name ?? ('Afiliado #' . $aff->id) }}</td>
                                            <td>{{ $aff->orders_count ?? 0 }}</td>
                                            <td>
                                                ${{ number_format($aff->commissions_sum_commission_amount ?? 0, 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const salesData   = @json($charts['sales_last_30_days']   ?? []);
    const ordersData  = @json($charts['orders_last_30_days']  ?? []);

    const salesLabels  = salesData.map(i => i.date);
    const salesTotals  = salesData.map(i => i.total);

    const ordersLabels = ordersData.map(i => i.date);
    const ordersTotals = ordersData.map(i => i.total);

    const salesCtx  = document.getElementById('chartSales');
    const ordersCtx = document.getElementById('chartOrders');

    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Ventas',
                    data: salesTotals,
                    borderWidth: 2,
                    borderColor: '#22c55e',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { color: '#9ca3af' } },
                    y: { ticks: { color: '#9ca3af' } }
                }
            }
        });
    }

    if (ordersCtx) {
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: ordersLabels,
                datasets: [{
                    label: 'Órdenes',
                    data: ordersTotals,
                    borderWidth: 2,
                    borderColor: '#0ea5e9',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { color: '#9ca3af' } },
                    y: { ticks: { color: '#9ca3af' } }
                }
            }
        });
    }
</script>
@endsection
