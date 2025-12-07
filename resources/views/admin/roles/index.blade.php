@extends('layouts.dashboard')

@section('content')

<div class="page-header">
    <h1 class="page-title">Roles del Sistema</h1>
    <p class="page-subtitle">Administración de roles y permisos (ACL)</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Listado de Roles</h2>
    </div>

    <div class="card-body">

        <table class="table table-pro">
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th>Usuarios</th>
                    <th>Permisos</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ $role->name }}</strong>
                        </td>

                        <td>
                            {{ $role->description ?? '—' }}
                        </td>

                        <td>
                            <span class="badge badge-blue">
                                {{ $role->users_count }}
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-purple">
                                {{ $role->abilities()->count() }}
                            </span>
                        </td>

                        <td class="text-right">
                            <a href="{{ route('admin.roles.show', $role->id) }}"
                               class="btn btn-sm btn-primary">
                                Ver / Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
