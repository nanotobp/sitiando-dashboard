@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detalle de Liquidación</h1>
    <p class="page-subtitle">Afiliado: {{ $payout->affiliate?->full_name }}</p>
</div>

<div class="card">
    <div class="card-body">

        <h3>Información General</h3>

        <ul class="detail-list">
            <li><strong>Afiliado:</strong> {{ $payout->affiliate?->full_name }}</li>
            <li><strong>Período:</strong> {{ $payout->period_start }} → {{ $payout->period_end }}</li>
            <li><strong>Total generado:</strong> ₲ {{ number_format($payout->total_amount, 0, ',', '.') }}</li>
            <li><strong>Monto neto:</strong> ₲ {{ number_format($payout->net_amount, 0, ',', '.') }}</li>
            <li><strong>Estado:</strong> {{ ucfirst($payout->status) }}</li>
            <li><strong>Fecha creación:</strong> {{ $payout->created_at->format('d/m/Y H:i') }}</li>
        </ul>

        <hr>

        <h3>Comisiones Incluidas</h3>

        @if ($payout->commission_ids && is_array($payout->commission_ids))
            <ul class="detail-list">
                @foreach ($payout->commission_ids as $cid)
                    <li>ID Comisión: {{ $cid }}</li>
                @endforeach
            </ul>
        @else
            <p>No hay comisiones asociadas.</p>
        @endif

    </div>
</div>

@endsection
