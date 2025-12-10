@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Nueva Liquidación</h1>
    <p class="page-subtitle">Seleccioná afiliado y rango de fechas para generar el payout.</p>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('admin.payouts.store') }}">
            @csrf

            <div class="filters-grid">

                <div>
                    <label class="filter-label">Afiliado</label>
                    <select name="affiliate_id" class="form-control">
                        @foreach ($affiliates as $a)
                            <option value="{{ $a->id }}">{{ $a->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="filter-label">Desde</label>
                    <input type="date" name="period_start" class="form-control" required>
                </div>

                <div>
                    <label class="filter-label">Hasta</label>
                    <input type="date" name="period_end" class="form-control" required>
                </div>

            </div>

            <button class="btn btn-primary" style="margin-top:20px;">
                Generar Liquidación
            </button>

        </form>

    </div>
</div>

@endsection
