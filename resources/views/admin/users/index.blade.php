@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Usuarios</h1>
    <p class="subtitle-text">
        Usuarios internos con acceso al panel Sitiando PRO.
    </p>
</div>

<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                            Ver
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Sin usuarios por ahora.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-3 py-2">
        {{ $users->links() }}
    </div>
</div>

@endsection