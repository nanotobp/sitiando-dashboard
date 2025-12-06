@extends('layouts.dashboard')

@section('content')
<h1 class="title">Dashboard</h1>

<div class="cards">

    <div class="card">
        <h3>Usuarios</h3>
        <p>{{ $totalUsuarios }}</p>
    </div>

    <div class="card">
        <h3>Productos</h3>
        <p>{{ $totalProductos }}</p>
    </div>

    <div class="card">
        <h3>Órdenes</h3>
        <p>{{ $totalOrders }}</p>
    </div>

    <div class="card">
        <h3>Afiliados</h3>
        <p>{{ $totalAffiliates }}</p>
    </div>

    <div class="card">
        <h3>Ventas del mes</h3>
        <p>${{ number_format($ventasMes, 0) }}</p>
    </div>

</div>


{{-- Últimos productos --}}
<h2 class="subtitle">Últimos productos</h2>
<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Creado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ultimosProductos as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>${{ number_format($p->price, 0) }}</td>
                <td>{{ $p->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No hay productos todavía.</td></tr>
        @endforelse
    </tbody>
</table>


{{-- Últimas órdenes --}}
<h2 class="subtitle">Últimas órdenes</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ultimasOrdenes as $o)
            <tr>
                <td>#{{ $o->id }}</td>
                <td>${{ number_format($o->total, 0) }}</td>
                <td>{{ $o->status ?? 'pendiente' }}</td>
                <td>{{ $o->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No hay órdenes todavía.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
