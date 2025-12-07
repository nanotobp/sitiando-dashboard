@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Ventas</h1>
    <p class="subtitle-text">Listado general de ventas</p>
</div>

{{-- ==========================================================
     FILTROS / BÚSQUEDA
=========================================================== --}}
<div class="card" style="padding:20px; margin-bottom:24px;">

    <form method="GET">
        <div class="filters-grid">
            
            {{-- Búsqueda --}}
            <div>
                <label class="filter-label">Buscar</label>
                <input type="text"
                       name="search"
                       placeholder="ID, email o cliente…"
                       class="form-control"
                       value="{{ request('search') }}">
            </div>

            {{-- Estado --}}
            <div>
                <label class="filter-label">Estado</label>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="pending"   @selected(request('status')=='pending')>Pendiente</option>
                    <option value="paid"      @selected(request('status')=='paid')>Pagado</option>
                    <option value="failed"    @selected(request('status')=='failed')>Fallido</option>
                    <option value="shipped"   @selected(request('status')=='shipped')>Envío</option>
                    <option value="completed" @selected(request('status')=='completed')>Completado</option>
                </select>
            </div>

            {{-- Fecha desde --}}
            <div>
                <label class="filter-label">Desde</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            {{-- Fecha hasta --}}
            <div>
                <label class="filter-label">Hasta</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            {{-- Botón --}}
            <div style="display:flex; align-items:flex-end;">
                <button class="topbar-btn" style="padding:8px 16px;">Filtrar</button>
            </div>
        </div>
    </form>

</div>



{{-- ==========================================================
     TABLA DE VENTAS
=========================================================== --}}
<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @forelse($ventas as $v)
            <tr>
                <td>#{{ $v->id }}</td>

                <td>{{ $v->customer_name }}</td>

                <td>{{ $v->customer_email }}</td>

                <td>${{ number_format($v->total,0) }}</td>

                <td>
                    <span class="badge badge-{{ $v->status }}">
                        {{ ucfirst($v->status) }}
                    </span>
                </td>

                <td>
                    {{ $v->created_at->format('d/m/Y H:i') }}
                </td>

                <td>
                    <a href="{{ route('ventas.show', $v->id) }}"
                       class="topbar-btn"
                       style="padding:6px 10px;">
                        Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">
                    No hay ventas registradas.
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

{{-- PAGINACIÓN --}}
<div style="margin-top:20px;">
    {{ $ventas->links() }}
</div>

@endsection
