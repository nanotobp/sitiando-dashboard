@extends('layouts.dashboard')

@section('content')

<div class="container dashboard-container">

    <h1 class="dashboard-title">Panel General</h1>
    <p class="dashboard-subtitle">Resumen ejecutivo del negocio</p>

    <div class="cards-wrapper">

        <div class="card-item">
            <h3>Usuarios</h3>
            <span class="value">{{ $totalUsuarios }}</span>
        </div>

        <div class="card-item">
            <h3>Productos</h3>
            <span class="value">{{ $totalProductos }}</span>
        </div>

        <div class="card-item">
            <h3>Pedidos</h3>
            <span class="value">{{ $totalPedidos }}</span>
        </div>

        <div class="card-item">
            <h3>Afiliados</h3>
            <span class="value">{{ $totalAfiliados }}</span>
        </div>

    </div>

</div>

@endsection
