<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function editRole($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit-role', compact('user', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        // Reemplazar todos los roles del usuario por el nuevo
        $user->roles()->sync([$request->role_id]);

        return redirect()
            ->route('admin.users.show', $id)
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function permissions($id)
    {
        $user = User::with('roles.abilities')->findOrFail($id);

        // Permisos finales = combinación de roles
        $finalPermissions = $user->roles
            ->flatMap(fn ($r) => $r->abilities)
            ->unique('id')
            ->values();

        return view('admin.users.permissions', compact('user', 'finalPermissions'));
    }

    public function index()
    {
        $query = User::query()->with('roles')->withCount('orders');

        // Otros filtros pueden ir aquí

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}
