@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Usuario: {{ $user->name }}</h1>
    <p class="subtitle-text">
        Detalle del usuario y sus órdenes.
    </p>
</div>

<div class="cards">

    <div class="card">
        <h3>Datos básicos</h3>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Roles:</strong> {{ $user->roles->pluck('name')->join(', ') }}</p>
    </div>

    <div class="card table-card">
        <h3>Órdenes del usuario</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }} Gs</td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Sin órdenes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
