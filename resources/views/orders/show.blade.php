@extends('layouts.admin')

@section('title', 'Orden #' . $order->order_number)

@section('content')

<div class="container-fluid">

    {{-- HEADER DE LA ORDEN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0" style="font-size:1.4rem; font-weight:600;">
            🧾 Orden <span class="text-info">#{{ $order->order_number }}</span>
        </h1>

        @php
            $colors = [
                'pending'   => 'warning',
                'paid'      => 'success',
                'failed'    => 'danger',
                'shipped'   => 'info',
                'delivered' => 'primary'
            ];
            $color = $colors[$order->status] ?? 'secondary';
        @endphp

        <span class="badge bg-{{ $color }}" style="font-size:1rem;">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    {{-- RESUMEN DE LA ORDEN --}}
    <div class="row g-4 mb-4">

        {{-- Cliente --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background:#0f172a;">
                <div class="card-header border-bottom text-gray-300">
                    👤 Cliente
                </div>
                <div class="card-body text-gray-200">
                    <p><strong>Nombre:</strong> {{ $order->customer_name ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email ?? '—' }}</p>
                    <p><strong>Teléfono:</strong> {{ $order->customer_phone ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Totales --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background:#0f172a;">
                <div class="card-header border-bottom text-gray-300">
                    💰 Totales
                </div>
                <div class="card-body text-gray-200">
                    <p><strong>Subtotal:</strong> ₲ {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    <p><strong>Descuento:</strong> ₲ {{ number_format($order->discount ?? 0, 0, ',', '.') }}</p>
                    <p class="text-info"><strong>Total:</strong> ₲ {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Fechas --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="background:#0f172a;">
                <div class="card-header border-bottom text-gray-300">
                    🕒 Fechas
                </div>
                <div class="card-body text-gray-200">
                    <p><strong>Creada:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Actualizada:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Pagada en:</strong>
                        {{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '—' }}
                    </p>
                </div>
            </div>
        </div>

    </div>


    {{-- ITEMS DE LA ORDEN --}}
    <div class="card border-0 shadow-sm mb-4" style="background:#0f172a;">
        <div class="card-header border-bottom text-gray-300">
            📦 Ítems del Pedido
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Producto eliminado' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₲ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td>₲ {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>


    {{-- PAGOS REGISTRADOS --}}
    <div class="card border-0 shadow-sm mb-4" style="background:#0f172a;">
        <div class="card-header border-bottom text-gray-300">
            💳 Pagos
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->payments as $payment)
                        <tr>
                            <td>{{ $payment->provider ?? '—' }}</td>
                            <td>₲ {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($payment->status ?? '—') }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-3">
                                No hay pagos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>


    {{-- HISTORIAL DE ESTADOS --}}
    <div class="card border-0 shadow-sm mb-5" style="background:#0f172a;">
        <div class="card-header border-bottom text-gray-300">
            🔄 Historial de Estados
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle mb-0">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>Estado</th>
                        <th>Anterior</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->statusHistory as $history)
                        <tr>
                            <td>{{ ucfirst($history->new_status) }}</td>
                            <td>{{ ucfirst($history->old_status ?? '—') }}</td>
                            <td>{{ $history->changed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-3">
                                No hay historial disponible.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
