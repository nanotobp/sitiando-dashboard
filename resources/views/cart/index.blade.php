@extends('layouts.dashboard')

@section('content')
    <div class="content-wrapper">
        <div class="content-inner">

            <div class="df-title-block">
                <h1 class="title">Tu carrito</h1>
                <p class="subtitle-text">
                    Revisá los productos antes de avanzar al checkout.
                </p>
            </div>

            @if (session('success'))
                <div class="card" style="margin-bottom:16px; padding:12px 16px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (! $cart || $cart->items->isEmpty())
                <div class="card">
                    <p>No tenés productos en el carrito todavía.</p>
                </div>
            @else
                <div class="card table-card">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width: 100px;">Precio</th>
                                <th style="width: 90px;">Cantidad</th>
                                <th style="width: 110px;">Subtotal</th>
                                <th style="width: 80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product?->nombre ?? 'Producto #' . $item->product_id }}
                                    </td>
                                    <td>
                                        ₲ {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.item.update', $item->id) }}" method="POST" style="display:flex; gap:4px; align-items:center;">
                                            @csrf
                                            @method('PUT')
                                            <input type="number"
                                                   name="qty"
                                                   value="{{ $item->qty }}"
                                                   min="0"
                                                   class="form-control"
                                                   style="max-width:70px;">
                                            <button type="submit" class="topbar-btn">
                                                Actualizar
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        ₲ {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.item.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="topbar-btn danger">
                                                X
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right;">Total:</th>
                                <th colspan="2">
                                    ₲ {{ number_format($cart->total, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div style="margin-top:16px; display:flex; gap:12px; justify-content:space-between; flex-wrap:wrap;">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="topbar-btn danger">
                            Vaciar carrito
                        </button>
                    </form>

                    {{-- Más adelante acá va el botón de Checkout PRO --}}
                    <a href="#" class="topbar-btn">
                        Ir al checkout (mock)
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
