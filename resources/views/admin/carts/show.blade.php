@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Carrito #{{ $cart->id }}</h1>
    <p class="subtitle-text">Detalle del carrito y actividad del cliente</p>
</div>

<div class="card" style="margin-bottom:24px;">
    <h3>Información del usuario</h3>
    <p><strong>Nombre:</strong> {{ $cart->user?->name }}</p>
    <p><strong>Email:</strong> {{ $cart->user?->email }}</p>
</div>

<div class="section-block">
    <h2 class="subtitle">Productos en el carrito</h2>

    <div class="table-card card">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                <tr>
                    <td>{{ $item->product?->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>₲ {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="section-block">
    <h2 class="subtitle">Actividad del carrito (Timeline)</h2>

    <div class="card">
        <div class="timeline">
            @foreach($timeline as $log)
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-date">{{ $log->created_at }}</div>
                <div class="timeline-status">
                    {{ $log->action }} — {{ $log->page }}
                </div>
                <div class="timeline-status" style="font-size:12px;color:#777;">
                    {{ $log->ip }} — {{ $log->device }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
