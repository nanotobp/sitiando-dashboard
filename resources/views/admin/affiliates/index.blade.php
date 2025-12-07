@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Afiliados</h1>
    <p class="page-subtitle">Listado de vendedores externos / comisionistas.</p>
</div>

<div class="card table-card">
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Comisiones</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($affiliates as $a)
                    <tr>
                        <td>{{ $a->full_name }}</td>
                        <td>{{ $a->email }}</td>
                        <td>{{ $a->phone }}</td>
                        <td>
                            <span class="badge badge-purple">
                                ₲ {{ number_format($a->total_commission_earned, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($a->status) }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.affiliates.show', $a->id) }}"
                               class="btn btn-sm btn-primary">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div style="margin-top:20px;">
            {{ $affiliates->links() }}
        </div>

    </div>
</div>

@endsection
