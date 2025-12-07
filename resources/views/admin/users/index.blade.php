@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-inner">

        <!-- Título -->
        <div class="df-title-block">
            <h1 class="title">Usuarios del Sistema</h1>
            <p class="subtitle-text">Administración de operadores, admins y clientes</p>
        </div>

        <!-- Buscador -->
        <x-admin.section title="Buscar Usuario">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="filters-grid">
                    <div>
                        <label class="filter-label">Nombre o Email</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar...">
                    </div>

                    <div>
                        <label class="filter-label">Rol</label>
                        <select name="role" class="form-control">
                            <option value="">Todos</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(request('role') == $role->id)>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="filter-label">&nbsp;</label>
                        <button class="topbar-btn" style="margin-top:2px;">Filtrar</button>
                    </div>
                </div>
            </form>
        </x-admin.section>

        <!-- Tabla -->
        <x-admin.section title="Listado de Usuarios">

            <x-admin.table>
                <x-slot name="head">
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Órdenes</th>
                    <th>Último acceso</th>
                    <th></th>
                </x-slot>

                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->roles->pluck('name')->implode(', ') ?: 'Sin Rol' }}</td>
                        <td>{{ $u->orders_count }}</td>
                        <td>{{ $u->last_login_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $u->id) }}" class="sidebar-link" style="padding:4px 8px;">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </x-admin.section>

    </div>
</div>
@endsection
