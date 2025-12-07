<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartActivityLog;

class CartActivityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Solo trackeamos si el usuario está logueado
        if (!auth()->check()) {
            return $response;
        }

        $user = auth()->user();

        // Buscar carrito activo del usuario (si existe)
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        // Registrar actividad mínima
        CartActivityLog::create([
            'user_id' => $user->id,
            'cart_id' => $cart?->id,
            'page'    => $request->path(),
            'action'  => 'view',
            'device'  => $request->userAgent(),
            'ip'      => $request->ip(),
        ]);

        // Mantener “vivo” el carrito
        if ($cart) {
            $cart->update(['updated_at' => now()]);
        }

        return $response;
    }
}
