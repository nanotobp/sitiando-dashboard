@extends('layout')

@section('content')

    <h1 class="fw-bold mb-4 text-white">Dashboard</h1>

    {{-- MÉTRICAS --}}
    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card card-dark p-4">
                <h4 class="text-white">Ventas hoy</h4>
                <h2 class="text-success fw-bold">Gs {{ number_format($stats['ventas_hoy'], 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card card-dark p-4">
                <h4 class="text-white">Ventas del mes</h4>
                <h2 class="text-info fw-bold">Gs {{ number_format($stats['ventas_mes'], 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card card-dark p-4">
                <h4 class="text-white">Productos totales</h4>
                <h2 class="fw-bold text-warning">{{ $stats['productos_total'] }}</h2>
            </div>
        </div>

    </div>

    {{-- GRÁFICO --}}
    <div class="card card-dark mb-4 p-4">
        <h4 class="text-white mb-3">Ventas últimos 7 días</h4>
        <canvas id="chartVentas" height="80"></canvas>
    </div>

    {{-- ACTIVIDAD --}}
    <div class="card card-dark p-4">
        <h4 class="text-white mb-3">Última actividad</h4>

        <ul class="list-group list-group-dark">
            @foreach ($activity as $a)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $a['msg'] }}
                    <span class="badge bg-secondary">{{ $a['time'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>


    {{-- CHART SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('chartVentas').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Ventas (Gs)',
                    data: {!! json_encode($chartData['values']) !!},
                    borderColor: '#3b7ddd',
                    backgroundColor: 'rgba(59, 125, 221, 0.2)',
                    borderWidth: 3,
                    tension: .3,
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: false }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>

@endsection
