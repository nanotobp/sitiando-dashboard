@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Liquidaciones de Afiliados</h1>
    <a href="{{ route('admin.payouts.create') }}" class="btn btn-primary">
        Nueva Liquidación
    </a>
</div>

<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Afiliado</th>
                <th>Período</th>
                <th>Monto Neto</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="text-right">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($payouts as $p)
                <tr>
                    <td>{{ $p->affiliate?->full_name }}</td>
                    <td>{{ $p->period_start }} → {{ $p->period_end }}</td>
                    <td>₲ {{ number_format($p->net_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($p->status) }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.payouts.show', $p->id) }}" class="btn btn-primary btn-sm">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div style="margin-top:20px;">
        {{ $payouts->links() }}
    </div>

</div>

@endsection
