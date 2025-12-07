@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-inner">

        <div class="card" style="padding:30px; text-align:center;">
            <h1 class="title">¡Pedido recibido! 🎉</h1>

            <p class="subtitle-text">
                Gracias por tu compra. Tu número de pedido es:
            </p>

            <h2 style="font-size:28px; margin:20px 0;">
                {{ $order->order_number }}
            </h2>

            <p style="margin-top:10px;">
                Te enviamos un correo con los detalles.
            </p>

            <a href="/dashboard" class="topbar-btn" style="margin-top:20px;">
                Volver al inicio
            </a>
        </div>

    </div>
</div>
@endsection
