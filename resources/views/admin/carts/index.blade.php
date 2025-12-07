@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Carritos</h1>
    <p class="subtitle-text">Seguimiento de carritos activos y abandonados</p>
</div>

<div class="cards">
    <div class="card kpi-card">
        <h3>Valor Abandonado</h3>
        <p>₲ {{ number_format($abandonedValue, 0, ',', '.') }}</p>
    </div>
</div>

<div class="section-block">
    <h2 class="subtitle">Carritos Activos</h2>

    <div class="table-card card">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Última actividad</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($active as $cart)
                <tr>
                    <td>{{ $cart->user?->name }}</td>
                    <td>{{ $cart->items_count }}</td>
                    <td>₲ {{ number_format($cart->total, 0, ',', '.') }}</td>
                    <td>{{ $cart->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.carts.show', $cart->id) }}">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $active->links() }}
</div>

<div class="section-block">
    <h2 class="subtitle">Carritos Abandonados</h2>

    <div class="table-card card">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Abandonado hace</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($abandoned as $cart)
                <tr>
                    <td>{{ $cart->user?->name }}</td>
                    <td>{{ $cart->items_count }}</td>
                    <td>₲ {{ number_format($cart->total, 0, ',', '.') }}</td>
                    <td>{{ $cart->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.carts.show', $cart->id) }}">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $abandoned->links() }}
</div>

@endsection
