@extends('layouts.dashboard')

@section('content')
<div style="margin-top:10px; display:flex; gap:12px;">
    
    <a href="{{ route('admin.payouts.export.csv', $payout->id) }}"
       class="topbar-btn">
       Exportar Liquidación CSV
    </a>

    <a href="{{ route('admin.payouts.export.commissions', $payout->id) }}"
       class="topbar-btn">
       Exportar Comisiones CSV
    </a>
    <a href="{{ route('admin.payouts.resend-email', $payout->id) }}"
   class="topbar-btn">
    Reenviar Email
</a>


</div>


<div class="page-header">
    <h1 class="page-title">Liquidación #{{ $payout->id }}</h1>
    <p class="page-subtitle">Detalle completo del pago al afiliado.</p>
</div>

<div class="card kpi-grid">

    <div class="card">
        <h3>Afiliado</h3>
        <p>{{ $payout->affiliate?->full_name }}</p>
    </div>

    <div class="card">
        <h3>Total Comisión</h3>
        <p>₲ {{ number_format($payout->total_amount, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Monto Neto</h3>
        <p>₲ {{ number_format($payout->net_amount, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Estado</h3>
        <p>
            <span class="badge badge-{{ strtolower($payout->status) }}">
                {{ ucfirst($payout->status) }}
            </span>
        </p>
    </div>

</div>


<!-- ESTADO DEL PAGO -->
<div class="section-block">
    <h2 class="subtitle">Actualizar Estado</h2>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.payouts.updateStatus', $payout->id) }}">
                @csrf

                <div class="filters-grid">

                    <div>
                        <label class="filter-label">Estado</label>
                        <select name="status" class="form-control">
                            @foreach (['pending','processing','paid','failed','cancelled'] as $status)
                                <option value="{{ $status }}" 
                                    {{ $payout->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="filter-label">Referencia</label>
                        <input name="payment_reference"
                            class="form-control"
                            value="{{ $payout->payment_reference }}">
                    </div>

                </div>

                <label class="filter-label" style="margin-top:14px;">Notas</label>
                <textarea name="notes" class="form-control" rows="3">{{ $payout->notes }}</textarea>

                <button class="btn btn-primary" style="margin-top:16px;">Guardar</button>

            </form>
        </div>
    </div>
</div>


<!-- TABLA DE COMISIONES -->
<div class="section-block">
    <h2 class="subtitle">Comisiones Incluidas</h2>

    <div class="card table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Monto Base</th>
                    <th>Comisión</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commissions as $c)
                    <tr>
                        <td>{{ $c->order_id }}</td>
                        <td>₲ {{ number_format($c->commission_base, 0, ',', '.') }}</td>
                        <td>₲ {{ number_format($c->commission_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($c->commission_type) }}</td>
                        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


<!-- SUBIR COMPROBANTE -->
<div class="section-block">
    <h2 class="subtitle">Comprobante de Pago</h2>

    <div class="card">
        <div class="card-body">

            @if ($payout->payment_proof_url)
                <p><strong>Comprobante actual:</strong></p>
                <a href="{{ asset('storage/' . $payout->payment_proof_url) }}" target="_blank">
                    Ver comprobante
                </a>
            @endif

            <form method="POST" action="{{ route('admin.payouts.uploadProof', $payout->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="file" name="proof" class="form-control" style="margin-top:12px;">
                <button class="btn btn-primary" style="margin-top:16px;">Subir</button>

            </form>

        </div>
    </div>
</div>


@endsection
