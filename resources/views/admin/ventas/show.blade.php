@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Venta #{{ $venta->id }}</h1>
    <p class="subtitle-text">Detalle completo de la operación</p>
</div>

<div style="display:flex; gap:24px; align-items:flex-start;">

    {{-- ==========================================================
        SIDEBAR INTERNO (NAVEGACIÓN DEL DETALLE)
    =========================================================== --}}
    <div style="width:240px; position:sticky; top:80px;">
        <div class="card" style="padding:16px;">
            <h3 style="margin-bottom:12px;">Navegación</h3>

            <div class="order-nav">
                <a href="#resumen">Resumen</a>
                <a href="#cliente">Cliente</a>
                <a href="#productos">Productos</a>
                <a href="#pagos">Pagos Bancard</a>
                <a href="#timeline">Timeline</a>
                <a href="#acciones">Acciones</a>
            </div>
        </div>
    </div>

    {{-- ==========================================================
        CONTENIDO PRINCIPAL
    =========================================================== --}}
    <div style="flex:1;">

        {{-- ======================================================
            RESUMEN
        ======================================================= --}}
        <div id="resumen" class="card" style="margin-bottom:24px;">
            <h3>Resumen</h3>

            <p><strong>Total:</strong> ${{ number_format($venta->total,0) }}</p>

            <p><strong>Estado:</strong>
                <span class="badge badge-{{ $venta->status }}">
                    {{ ucfirst($venta->status) }}
                </span>
            </p>

            <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
        </div>

        {{-- ======================================================
            CLIENTE
        ======================================================= --}}
        <div id="cliente" class="card" style="margin-bottom:24px;">
            <h3>Cliente</h3>

            <p><strong>Nombre:</strong> {{ $venta->customer_name }}</p>
            <p><strong>Email:</strong> {{ $venta->customer_email }}</p>
            <p><strong>Teléfono:</strong> {{ $venta->customer_phone ?? '—' }}</p>
        </div>

        {{-- ======================================================
            PRODUCTOS
        ======================================================= --}}
        <div id="productos" class="section-block">
            <h2 class="subtitle">Productos</h2>

            <div class="card table-card">
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
                        @foreach($venta->items as $i)
                        <tr>
                            <td>{{ $i->product->name }}</td>
                            <td>{{ $i->qty }}</td>
                            <td>${{ number_format($i->price,0) }}</td>
                            <td>${{ number_format($i->qty * $i->price,0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- ======================================================
            PAGOS (BANCARD)
        ======================================================= --}}
        <div id="pagos" class="section-block">
            <h2 class="subtitle">Pagos Bancard</h2>

            <div class="card table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transacción</th>
                            <th>Estado</th>
                            <th>Mensaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($venta->payments as $p)
                        <tr>
                            <td>{{ $p->transaction_id }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($p->status) }}">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td>{{ $p->message }}</td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No hay pagos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- ======================================================
            TIMELINE
        ======================================================= --}}
        <div id="timeline" class="section-block">
            <h2 class="subtitle">Timeline</h2>

            <div class="card">
                <div class="timeline">

                    @forelse($venta->statusHistory as $log)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>

                        <div class="timeline-date">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div class="timeline-status">
                            Estado cambiado a
                            <strong>{{ ucfirst($log->status) }}</strong>
                        </div>
                    </div>
                    @empty
                    <p>No hay historial de estados.</p>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- ======================================================
            ACCIONES
        ======================================================= --}}
        <div id="acciones" style="margin-top:24px; display:flex; gap:12px;">

            {{-- Reenviar email --}}
            <form method="POST" action="{{ route('orders.resendEmail', $venta->id) }}">
                @csrf
                <button class="topbar-btn">Reenviar correo</button>
            </form>

            {{-- Refrescar pago Bancard --}}
            <form method="POST" action="{{ route('orders.refreshPayment', $venta->id) }}">
                @csrf
                <button class="topbar-btn">Refrescar estado Bancard</button>
            </form>

        </div>

    </div>

</div>

@endsection
