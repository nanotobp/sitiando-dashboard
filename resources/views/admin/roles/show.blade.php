@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $role->name }}</h1>
    <p class="page-subtitle">
        Administración de permisos del rol
    </p>
</div>

<div class="card mb-3">
    <div class="card-body flex-row-between">
        <div>
            <h3 class="m-0">{{ $role->description ?? 'Sin descripción' }}</h3>
            <small class="text-muted">
                ID del Rol: {{ $role->id }}
            </small>
        </div>

        <div class="text-right">
            <div class="badge badge-blue">
                {{ $role->users()->count() }} usuarios
            </div>

            <div class="badge badge-purple">
                {{ $role->abilities()->count() }} permisos
            </div>
        </div>
    </div>
</div>


<form action="{{ route('admin.roles.updateAbilities', $role->id) }}"
      method="POST">

    @csrf

    <!-- CONTENIDO -->
    <div class="card">
        <div class="card-header">
            <h2>Permisos del Rol</h2>
            <p class="text-muted">Activá o desactivá los permisos para este rol.</p>
        </div>

        <div class="card-body">

            @foreach ($abilities as $group => $items)
                <div class="permission-group mb-4">

                    <!-- TÍTULO DEL GRUPO -->
                    <h3 class="permission-title">
                        {{ strtoupper($group) }}
                    </h3>

                    <div class="permission-grid">

                        @foreach ($items as $ability)
                            @php
                                $checked = $role->abilities->contains($ability->id);
                            @endphp

                            <label class="permission-item">
                                <div class="permission-info">
                                    <strong>{{ $ability->label }}</strong>
                                    <small class="text-muted">{{ $ability->key }}</small>
                                </div>

                                <input type="checkbox"
                                    name="abilities[]"
                                    value="{{ $ability->id }}"
                                    {{ $checked ? 'checked' : '' }}
                                >
                                <span class="toggle-switch"></span>
                            </label>

                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary" type="submit">
                Guardar Cambios
            </button>
        </div>
    </div>

</form>

@endsection
