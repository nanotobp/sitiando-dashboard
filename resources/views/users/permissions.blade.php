@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Permisos del Usuario</h1>
    <p class="page-subtitle">
        Permisos combinados de todos los roles asignados.
    </p>
</div>

<div class="card">
    <div class="card-body">

        <h3>{{ $user->name }}</h3>
        <p class="text-muted">{{ $user->email }}</p>

        <div class="permission-grid" style="margin-top:20px;">

            @foreach ($finalPermissions as $ability)
                <div class="permission-item">
                    <div class="permission-info">
                        <strong>{{ $ability->label }}</strong>
                        <small>{{ $ability->key }}</small>
                    </div>
                    
                    <span class="badge badge-blue">Activo</span>
                </div>
            @endforeach

        </div>

    </div>
</div>

@endsection
