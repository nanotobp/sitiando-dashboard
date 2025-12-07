@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $affiliate->full_name }}</h1>
    <p class="page-subtitle">Perfil del vendedor externo / afiliado.</p>
</div>

<div class="card kpi-grid">

    <div class="card">
        <h3>Total Ventas</h3>
        <p>₲ {{ number_format($stats['total_sales'], 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Comisión Ganada</h3>
        <p>₲ {{ number_format($stats['total_commission'], 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Comisión Pagada</h3>
        <p>₲ {{ number_format($stats['paid_commission'], 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Comisión Pendiente</h3>
        <p>₲ {{ number_format($stats['pending_commission'], 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Clicks Totales</h3>
        <p>{{ $stats['total_clicks'] }}</p>
    </div>

    <div class="card">
        <h3>Conversión</h3>
        <p>{{ $stats['conversion_rate'] }}%</p>
    </div>

    <div class="card">
        <h3>Ticket Promedio</h3>
        <p>₲ {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</p>
    </div>

</div>


<!-- ================================
     TABLA DE ÚLTIMAS ÓRDENES
================================ -->
<div class="section-block">
    <h2 class="subtitle">Últimas Órdenes</h2>

    <div class="card table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $o)
                    <tr>
                        <td>{{ $o->order_number }}</td>
                        <td>₲ {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ strtolower($o->status) }}">{{ $o->status }}</span></td>
                        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- ================================
     TABLA DE ÚLTIMOS CLICKS
================================ -->
<div class="section-block">
    <h2 class="subtitle">Últimos Clics</h2>

    <div class="card table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Referrer</th>
                    <th>IP</th>
                    <th>Dispositivo</th>
                    <th>Conversión</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clicks as $c)
                    <tr>
                        <td>{{ $c->referrer_url ?? '—' }}</td>
                        <td>{{ $c->ip_address }}</td>
                        <td>{{ $c->device_fingerprint ?? '—' }}</td>
                        <td>
                            @if($c->converted)
                                <span class="badge badge-paid">Sí</span>
                            @else
                                <span class="badge badge-pending">No</span>
                            @endif
                        </td>
                        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
