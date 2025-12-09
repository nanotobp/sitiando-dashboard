@extends('layouts.admin')

@section('title', 'Órdenes')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4" style="font-size:1.4rem; font-weight:600;">🧾 Órdenes</h1>

    @if ($orders->count() === 0)
        <div class="alert alert-info bg-dark border-0 text-gray-300">
            No hay órdenes registradas aún.
        </div>
    @else

        <div class="card border-0 shadow-sm mb-4" style="background:#0f172a;">
            <div class="card-body table-responsive p-0">

                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th style="width:80px;">#</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th style="width:110px;">Acción</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach ($orders as $order)
                        <tr>
                            <td class="text-gray-200">
                                <strong>{{ $order->order_number }}</strong>
                            </td>

                            <td class="text-gray-300">
                                {{ $order->customer_email ?? '—' }}
                            </td>

                            <td class="text-gray-100">
                                <strong>₲ {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </td>

                            <td>
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

                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="text-gray-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-info">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>

    @endif

</div>

@endsection
