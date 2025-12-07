@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-inner">

        <div class="df-title-block">
            <h1 class="title">Checkout</h1>
            <p class="subtitle-text">Completá tus datos para finalizar el pedido.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="card" style="padding:24px;">
            @csrf

            <h3 class="subtitle">Datos del comprador</h3>

            <div class="filters-grid">

                <div>
                    <label class="filter-label">Nombre completo</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>

                <div>
                    <label class="filter-label">Email</label>
                    <input type="email" name="customer_email" class="form-control" required>
                </div>

                <div>
                    <label class="filter-label">Teléfono</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
            </div>

            <hr style="margin:24px 0;">

            <h3 class="subtitle">Resumen del pedido</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->items as $item)
                        <tr>
                            <td>{{ $item->product?->nombre }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>₲ {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th>₲ {{ number_format($cart->total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top:24px; display:flex; justify-content:flex-end;">
                <button type="submit" class="topbar-btn" style="padding:10px 22px; font-size:16px;">
                    Finalizar pedido
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
