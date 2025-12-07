@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-inner">
        <div class="card" style="padding:30px;">
            <h1 class="title">Pago procesado</h1>
            <p class="subtitle-text">
                Orden: {{ $order->order_number }}
            </p>
            <p>Estado actual: <strong>{{ $order->status }}</strong></p>
        </div>
    </div>
</div>
@endsection
