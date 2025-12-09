<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $query = User::query()->with('roles')->withCount('orders');

        // Otros filtros futuros pueden ir aquí

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form para editar el rol del usuario
     */
    public function editRole($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit-role', compact('user', 'roles'));
    }

    /**
     * Actualizar rol del usuario
     */
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        // Reemplazar el rol del usuario por uno solo
        $user->roles()->sync([$request->role_id]);

        return redirect()
            ->route('admin.users.show', $id)
            ->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Permisos combinados del usuario
     */
    public function permissions($id)
    {
        $user = User::with('roles.abilities')->findOrFail($id);

        $finalPermissions = $user->roles
            ->flatMap(fn ($role) => $role->abilities)
            ->unique('id')
            ->values();

        return view('admin.users.permissions', compact('user', 'finalPermissions'));
    }
}
