<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Ability;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Listado de roles del sistema
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Vista individual del rol
     */
    public function show($id)
    {
        $role = Role::with('abilities')->findOrFail($id);
        $abilities = Ability::all()->groupBy(function ($item) {
            return explode('_', $item->key)[0]; // agrupa por prefijo ej: view_
        });

        return view('admin.roles.show', compact('role', 'abilities'));
    }

    /**
     * Actualizar permisos del rol
     */
    public function updateAbilities(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'abilities' => 'array',
            'abilities.*' => 'integer|exists:abilities,id'
        ]);

        $role->abilities()->sync($request->abilities ?? []);

        return redirect()->back()->with('success', 'Permisos actualizados correctamente.');
    }
}
