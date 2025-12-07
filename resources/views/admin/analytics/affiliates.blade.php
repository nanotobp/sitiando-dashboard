@extends('layouts.dashboard')

@section('content')

<div class="content-inner">

    <div class="df-title-block">
        <h1 class="title">Analytics de Afiliados</h1>
        <p class="subtitle-text">Visualización global de rendimiento de la red de afiliados.</p>
    </div>

    {{-- ======================
         KPIs PRINCIPALES
    ======================= --}}
    <div class="kpi-grid">

        <div class="card kpi-card">
            <h3>Afiliados Totales</h3>
            <div class="kpi-number">{{ $global['total_affiliates'] }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Afiliados Activos</h3>
            <div class="kpi-number">{{ $global['active_affiliates'] }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Ventas Generadas</h3>
            <div class="kpi-number">₲ {{ number_format($global['total_sales'],0,'.','.') }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Comisiones Pagadas</h3>
            <div class="kpi-number">₲ {{ number_format($global['paid_payouts'],0,'.','.') }}</div>
        </div>

        <div class="card kpi-card">
            <h3>Conversión Global</h3>
            <div class="kpi-number">{{ $global['conversion_rate'] }}%</div>
        </div>

    </div>


    {{-- ======================
         GRÁFICOS
    ======================= --}}
    <div class="section-block">
        <h2 class="subtitle">Ventas (últimos 30 días)</h2>
        <div class="card chart-card">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="section-block">
        <h2 class="subtitle">Comisiones (últimos 30 días)</h2>
        <div class="card chart-card">
            <canvas id="commChart"></canvas>
        </div>
    </div>


    {{-- ======================
         TOP LISTADOS
    ======================= --}}
    <div class="section-block">
        <h2 class="subtitle">Top Afiliados por Comisiones</h2>
        <div class="card table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Afiliado</th>
                        <th>Ventas Totales</th>
                        <th>Comisión Ganada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCommission as $a)
                        <tr>
                            <td>{{ $a->full_name }}</td>
                            <td>₲ {{ number_format($a->total_sales,0,'.','.') }}</td>
                            <td>₲ {{ number_format($a->total_commission_earned,0,'.','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="section-block">
        <h2 class="subtitle">Afiliados sin Conversiones</h2>
        <div class="card table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Afiliado</th>
                        <th>Clicks Totales</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($noConversions as $a)
                        <tr>
                            <td>{{ $a->full_name }}</td>
                            <td>{{ $a->total_clicks }}</td>
                            <td>{{ $a->created_at->format('d/m/Y') }}</td>
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

    // SALES CHART
    new Chart(document.getElementById("salesChart"), {
        type: 'line',
        data: {
            labels: {!! $dailySales->pluck('date') !!},
            datasets: [{
                label: "Ventas (₲)",
                data: {!! $dailySales->pluck('total') !!},
                borderColor: "#3b82f6",
                backgroundColor: "rgba(59,130,246,0.15)",
                tension: 0.3,
                fill: true
            }]
        }
    });

    // COMMISSIONS CHART
    new Chart(document.getElementById("commChart"), {
        type: 'line',
        data: {
            labels: {!! $dailyComms->pluck('date') !!},
            datasets: [{
                label: "Comisiones (₲)",
                data: {!! $dailyComms->pluck('total') !!},
                borderColor: "#10b981",
                backgroundColor: "rgba(16,185,129,0.20)",
                tension: 0.3,
                fill: true
            }]
        }
    });

});
</script>
@endsection
