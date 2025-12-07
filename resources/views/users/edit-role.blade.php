@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Editar Rol de Usuario</h1>
    <p class="page-subtitle">
        Gestioná el rol del usuario dentro del sistema.
    </p>
</div>

<div class="card">
    <div class="card-body">

        <h3>{{ $user->name }}</h3>
        <p class="text-muted">{{ $user->email }}</p>

        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom:22px;">
                <label class="filter-label">Rol asignado</label>
                <select name="role_id" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Guardar cambios</button>
        </form>

    </div>
</div>

@endsection
