@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Órdenes</h1>
    <p class="subtitle-text">
        Listado de ventas con filtros rápidos por estado, fecha y cliente.
    </p>
</div>

{{-- FILTROS --}}
<div class="card mb-4">
    <div class="card-body">

        <form method="GET" class="filters-grid">

            <div>
                <span class="filter-label">Estado</span>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="pending"   {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="paid"      {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>Pagado</option>
                    <option value="failed"    {{ ($filters['status'] ?? '') === 'failed' ? 'selected' : '' }}>Fallido</option>
                    <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="refunded"  {{ ($filters['status'] ?? '') === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>

            <div>
                <span class="filter-label">Desde</span>
                <input type="date" name="from" class="form-control"
                       value="{{ $filters['from'] ?? '' }}">
            </div>

            <div>
                <span class="filter-label">Hasta</span>
                <input type="date" name="to" class="form-control"
                       value="{{ $filters['to'] ?? '' }}">
            </div>

            <div>
                <span class="filter-label">Buscar (cliente u orden)</span>
                <input type="text" name="search" class="form-control"
                       placeholder="Nombre cliente, email, N° orden..."
                       value="{{ $filters['search'] ?? '' }}">
            </div>

            <div style="align-self: end;">
                <button class="topbar-btn" type="submit">Aplicar filtros</button>
            </div>

        </form>

    </div>
</div>

{{-- RESUMEN POR ESTADO --}}
<div class="cards mb-4">

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Pendientes</h3>
        </div>
        <div class="kpi-number">{{ $statusCounts['pending'] ?? 0 }}</div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Pagadas</h3>
        </div>
        <div class="kpi-number">{{ $statusCounts['paid'] ?? 0 }}</div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Fallidas</h3>
        </div>
        <div class="kpi-number">{{ $statusCounts['failed'] ?? 0 }}</div>
    </div>

    <div class="card kpi-card">
        <div class="kpi-header">
            <h3>Completadas</h3>
        </div>
        <div class="kpi-number">{{ $statusCounts['completed'] ?? 0 }}</div>
    </div>

</div>

{{-- TABLA DE ÓRDENES --}}
<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Orden</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Método</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ number_format($order->total, 0, ',', '.') }} Gs</td>
                    <td>
                        <span class="badge badge-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->payment_method ?? '-' }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                            Ver
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay órdenes con estos filtros.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-3 py-2">
        {{ $orders->links() }}
    </div>
</div>

@endsection