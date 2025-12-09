<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupabaseAuth
{
    /**
     * Middleware placeholder para no romper autoloading si está vacío.
     * Luego lo completamos con la lógica real JWT/Supabase.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
