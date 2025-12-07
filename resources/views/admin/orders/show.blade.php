@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">
        Orden #{{ $order->order_number }}
    </h1>
    <p class="subtitle-text">
        Detalle completo de la venta, items, pagos y estados.
    </p>
</div>

<div class="cards">

    {{-- INFO PRINCIPAL --}}
    <div class="card">
        <h3>Resumen</h3>

        <p><strong>Cliente:</strong> {{ $order->customer_name }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
        <p><strong>Teléfono:</strong> {{ $order->customer_phone }}</p>

        <p><strong>Total:</strong> {{ number_format($order->total, 0, ',', '.') }} Gs</p>
        <p>
            <strong>Estado:</strong>
            <span class="badge badge-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <p><strong>Método de pago:</strong> {{ $order->payment_method ?? 'N/D' }}</p>
        <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- TIMELINE DE ESTADOS --}}
    <div class="card">
        <h3>Historial de estados</h3>

        @if($timeline->isEmpty())
            <p class="subtitle-text">Aún no hay historial de cambios.</p>
        @else
            <div class="timeline">
                @foreach($timeline as $index => $event)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-date">
                            {{ $event->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="timeline-status">
                            {{ ucfirst($event->status) }}
                        </div>
                        @if($event->comment)
                            <div class="subtitle-text">
                                {{ $event->comment }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- ITEMS + PAGOS + ACCIONES --}}
<div class="cards section-block">

    {{-- ITEMS --}}
    <div class="card table-card">
        <h3>Productos</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} Gs</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} Gs</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Sin items registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGOS + ACCIONES --}}
    <div class="card">
        <h3>Pagos & Acciones</h3>

        {{-- Historial de pagos --}}
        <h4 class="subtitle">Pagos</h4>
        @if($order->payments->isEmpty())
            <p class="subtitle-text">Aún no hay pagos registrados.</p>
        @else
            <ul>
                @foreach($order->payments as $payment)
                    <li>
                        {{ number_format($payment->amount, 0, ',', '.') }} Gs —
                        {{ $payment->payment_method }} —
                        {{ $payment->status }} —
                        {{ optional($payment->paid_at)->format('d/m/Y H:i') }}
                    </li>
                @endforeach
            </ul>
        @endif

        <hr>

        {{-- Cambiar estado --}}
        <h4 class="subtitle">Cambiar estado</h4>
        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
            @csrf
            <div class="mb-2">
                <select name="status" class="form-control">
                    <option value="pending"   {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="paid"      {{ $order->status === 'paid' ? 'selected' : '' }}>Pagado</option>
                    <option value="failed"    {{ $order->status === 'failed' ? 'selected' : '' }}>Fallido</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="refunded"  {{ $order->status === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>
            <button class="topbar-btn" type="submit">Actualizar estado</button>
        </form>

        <hr>

        {{-- Registrar pago manual --}}
        <h4 class="subtitle">Registrar pago manual</h4>
        <form method="POST" action="{{ route('admin.orders.register-payment', $order) }}">
            @csrf
            <button class="topbar-btn" type="submit">
                Registrar pago completo
            </button>
        </form>

        <hr>

        {{-- Reenviar link de pago (placeholder Bancard) --}}
        <h4 class="subtitle">Reenviar link de pago</h4>
        <form method="POST" action="{{ route('admin.orders.resend-payment-link', $order) }}">
            @csrf
            <button class="topbar-btn" type="submit">
                Reenviar link (placeholder)
            </button>
        </form>

    </div>

</div>

@endsection