<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolesMiddleware
{
    /**
     * Middleware para validar múltiples roles.
     * Uso: ->middleware("roles:admin,manager")
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Usuario no autenticado.');
        }

        // Si el usuario tiene al menos 1 de los roles permitidos → OK
        foreach ($roles as $role) {
            if ($user->roles->contains('name', $role)) {
                return $next($request);
            }
        }

        abort(403, 'No tenés permiso para acceder a esta sección.');
    }
}
