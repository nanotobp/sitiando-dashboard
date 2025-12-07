@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-inner">

        <!-- ============================
             BLOQUE DE TÍTULO
        ============================ -->
        <div class="df-title-block">
            <h1 class="title">Dashboard</h1>
            <p class="subtitle-text">Resumen general del comercio en tiempo real</p>
        </div>

        <!-- ============================
             GRID DE KPIs (4-6)
        ============================ -->
        <div class="kpi-grid">

            <x-admin.kpi 
                label="Ingresos del Mes"
                :value="format_currency($kpis['monthly_revenue'])"
                :change="$kpis['monthly_revenue_change']"
                sparkId="sparkRevenue"
            />

            <x-admin.kpi 
                label="Órdenes del Mes"
                :value="$kpis['monthly_orders']"
                :change="$kpis['monthly_orders_change']"
                sparkId="sparkOrders"
            />

            <x-admin.kpi 
                label="Ticket Promedio"
                :value="format_currency($kpis['average_ticket'])"
                sparkId="sparkTicket"
            />

            <x-admin.kpi 
                label="Usuarios Nuevos"
                :value="$kpis['new_users']"
                :change="$kpis['new_users_change']"
                sparkId="sparkUsers"
            />

            <x-admin.kpi 
                label="Afiliados Activos"
                :value="$kpis['active_affiliates']"
                sparkId="sparkAffiliates"
            />

        </div>


        <!-- ============================
             SECCIÓN: GRÁFICOS PRINCIPALES
        ============================ -->
        <x-admin.section title="Actividad del Mes" text="Tendencias financieras y operativas">

            <div class="grid md:grid-cols-2 gap-5">

                <x-admin.chart 
                    id="chartRevenue"
                    title="Ingresos Mensuales"
                />

                <x-admin.chart 
                    id="chartOrders"
                    title="Órdenes por Día"
                />

            </div>

        </x-admin.section>


        <!-- ============================
             SECCIÓN: TABLAS (Órdenes)
        ============================ -->
        <x-admin.section title="Últimas Órdenes">

            <x-admin.table>
                <x-slot name="head">
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </x-slot>

                @foreach($recent_orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ format_currency($order->total) }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($order->status) }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </x-admin.table>

        </x-admin.section>


        <!-- ============================
             SECCIÓN: TOP AFILIADOS
        ============================ -->
        <x-admin.section title="Top Afiliados del Mes">

            <x-admin.table>
                <x-slot name="head">
                    <th>Afiliado</th>
                    <th>Ventas</th>
                    <th>Comisión</th>
                    <th>Clicks</th>
                    <th>Conversión</th>
                </x-slot>

                @foreach($top_affiliates as $a)
                    <tr>
                        <td>{{ $a->full_name }}</td>
                        <td>{{ format_currency($a->total_sales) }}</td>
                        <td>{{ format_currency($a->total_commission_earned) }}</td>
                        <td>{{ $a->total_clicks }}</td>
                        <td>{{ $a->conversion_rate }}%</td>
                    </tr>
                @endforeach
            </x-admin.table>

        </x-admin.section>

    </div>
</div>

<script>
    window.dashboardCharts = @json($charts);
</script>


@endsection
