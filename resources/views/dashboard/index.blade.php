@extends('layout')

@section('content')

<div class="card">
    <h3>Bienvenido, {{ auth()->user()->name }}</h3>
    <p>Este es el panel principal de Sitiando.</p>
</div>

<div class="card">
    <h3>Resumen</h3>
    <table class="table">
        <tr>
            <th>Métrica</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td>Productos totales</td>
            <td>{{ $totalProductos ?? 0 }}</td>
        </tr>
        <tr>
            <td>Usuarios</td>
            <td>{{ $totalUsuarios ?? 0 }}</td>
        </tr>
    </table>
</div>

@endsection
