@extends('layouts.admin')

@section('title', 'Actualizar estado')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4" style="font-size:1.4rem; font-weight:600;">
        🧾 Actualizar estado — Orden {{ $order->order_number }}
    </h1>

    <div class="card border-0" style="background:#0f172a;">
        <div class="card-body">

            {{-- Estado actual --}}
            <div class="mb-4">
                <div class="text-gray-400">Estado actual</div>

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

                <span class="badge bg-{{ $color }} fs-5">{{ ucfirst($order->status) }}</span>
            </div>

            {{-- FORMULARIO --}}
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="p-3 rounded" style="background:#1e293b;">
                @csrf

                <label class="text-gray-200 mb-2 fw-semibold">
                    Nuevo estado
                </label>

                <select name="status" class="form-select mb-4 bg-dark text-light border-secondary">
                    <option value="pending"   @selected($order->status == 'pending')>Pendiente</option>
                    <option value="paid"      @selected($order->status == 'paid')>Pagado</option>
                    <option value="failed"    @selected($order->status == 'failed')>Fallido</option>
                    <option value="shipped"   @selected($order->status == 'shipped')>Enviado</option>
                    <option value="delivered" @selected($order->status == 'delivered')>Entregado</option>
                </select>

                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary px-4 py-2 ms-2">
                    Cancelar
                </a>
            </form>

        </div>
    </div>

</div>

@endsection
