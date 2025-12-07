@extends('layouts.dashboard')

@section('content')
<div class="content-inner">

    <div class="df-title-block">
        <h1 class="title">Analytics de {{ $affiliate->full_name }}</h1>
        <p class="subtitle-text">Rendimiento individual del afiliado.</p>
    </div>

    {{-- ========= KPIs ========= --}}
    <div class="kpi-grid">

        <div class="card kpi-card">
            <h3>Clicks</h3>
            <div class="kpi-number">{{ $stats['clicks'] }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Conversiones</h3>
            <div class="kpi-number">{{ $stats['conversions'] }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Ventas</h3>
            <div class="kpi-number">₲ {{ number_format($stats['sales'],0,'.','.') }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Comisión Ganada</h3>
            <div class="kpi-number">₲ {{ number_format($stats['commission'],0,'.','.') }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Conversión</h3>
            <div class="kpi-number">{{ $stats['conversion_rate'] }}%</div>
        </div>

    </div>

    {{-- ========= FUNNEL ========= --}}
    <div class="section-block">
        <h2 class="subtitle">Embudo de Conversión</h2>
        <div class="card chart-card">
            <canvas id="funnelChart"></canvas>
        </div>
    </div>

    {{-- ========= GRÁFICOS ========= --}}
    <div class="section-block">
        <h2 class="subtitle">Clicks (últimos 30 días)</h2>
        <div class="card chart-card">
            <canvas id="clicksChart"></canvas>
        </div>
    </div>

    <div class="section-block">
        <h2 class="subtitle">Conversiones</h2>
        <div class="card chart-card">
            <canvas id="convChart"></canvas>
        </div>
    </div>

    <div class="section-block">
        <h2 class="subtitle">Ventas</h2>
        <div class="card chart-card">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="section-block">
        <h2 class="subtitle">Comisiones</h2>
        <div class="card chart-card">
            <canvas id="commChart"></canvas>
        </div>
    </div>

    {{-- ========= ÓRDENES RECIENTES ========= --}}
    <div class="section-block">
        <h2 class="subtitle">Últimas Órdenes Atribuidas</h2>

        <div class="card table-card">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>₲ {{ number_format($order->total,0,'.','.') }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========= COMISIONES ========= --}}
    <div class="section-block">
        <h2 class="subtitle">Últimas Comisiones</h2>

        <div class="card table-card">
            <table class="table">
                <thead>
                <tr>
                    <th>Monto</th>
                    <th>Porcentaje</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentCommissions as $c)
                    <tr>
                        <td>₲ {{ number_format($c->commission_amount,0,'.','.') }}</td>
                        <td>{{ $c->commission_rate }}%</td>
                        <td>{{ $c->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    new Chart(document.getElementById("funnelChart"), {
        type: 'bar',
        data: {
            labels: ['Clicks','Conversiones','Ventas','Comisión'],
            datasets: [{
                label: "Embudo",
                data: [
                    {{ $funnel['clicks'] }},
                    {{ $funnel['conversions'] }},
                    {{ $funnel['sales'] }},
                    {{ $funnel['commission'] }}
                ],
                backgroundColor: ["#93c5fd","#60a5fa","#3b82f6","#2563eb"]
            }]
        }
    });

    new Chart(document.getElementById("clicksChart"), {
        type: 'line',
        data: {
            labels: {!! $charts['clicks']->pluck('date') !!},
            datasets: [{
                label: "Clicks",
                data: {!! $charts['clicks']->pluck('total') !!},
                borderColor: "#2563eb",
                tension: 0.3
            }]
        }
    });

    new Chart(document.getElementById("convChart"), {
        type: 'line',
        data: {
            labels: {!! $charts['conversions']->pluck('date') !!},
            datasets: [{
                label: "Conversiones",
                data: {!! $charts['conversions']->pluck('total') !!},
                borderColor: "#16a34a",
                tension: 0.3
            }]
        }
    });

    new Chart(document.getElementById("salesChart"), {
        type: 'line',
        data: {
            labels: {!! $charts['sales']->pluck('date') !!},
            datasets: [{
                label: "Ventas (₲)",
                data: {!! $charts['sales']->pluck('total') !!},
                borderColor: "#3b82f6",
                tension: 0.3
            }]
        }
    });

    new Chart(document.getElementById("commChart"), {
        type: 'line',
        data: {
            labels: {!! $charts['commissions']->pluck('date') !!},
            datasets: [{
                label: "Comisiones (₲)",
                data: {!! $charts['commissions']->pluck('total') !!},
                borderColor: "#f59e0b",
                tension: 0.3
            }]
        }
    });

});
</script>
@endsection
