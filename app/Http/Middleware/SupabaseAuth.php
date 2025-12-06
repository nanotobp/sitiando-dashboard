<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SupabaseAuth
{
    /**
     * Handle an incoming request.
     *
     * Valida el JWT de Supabase y vincula/crea un usuario local.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorized('Missing or invalid Authorization header.');
        }

        $token = substr($authHeader, 7); // quitar "Bearer "

        $secret = config('supabase.jwt_secret');
        $algo   = config('supabase.jwt_algo', 'HS256');

        if (!$secret) {
            return $this->unauthorized('Supabase JWT secret not configured.');
        }

        try {
            // Decodificar JWT
            $decoded = JWT::decode($token, new Key($secret, $algo));

            // Validar expiración (exp)
            if (isset($decoded->exp) && time() >= $decoded->exp) {
                return $this->unauthorized('Token expired.');
            }

            // Sacar datos básicos del payload Supabase
            $supabaseUserId = $decoded->sub ?? null;
            $email          = $decoded->email ?? null;
            $metadata       = $decoded->user_metadata ?? null;

            if (!$supabaseUserId || !$email) {
                return $this->unauthorized('Invalid Supabase token payload.');
            }

            // Buscar usuario local por email
            $user = User::where('email', $email)->first();

            // Si no existe, lo creamos con datos mínimos
            if (!$user) {
                $user = new User();
                $user->name  = $metadata->full_name ?? $email;
                $user->email = $email;
                // Podés agregar un campo supabase_user_id en una migración extra si querés.
                $user->password = bcrypt(str()->random(32)); // password dummy, no se usa
                $user->save();
            }

            // Inyectar en el contexto de autenticación de Laravel
            Auth::setUser($user);

            // Opcional: guardar el payload crudo para uso en controllers
            $request->attributes->set('supabase_payload', $decoded);

        } catch (Throwable $e) {
            return $this->unauthorized('Invalid Supabase token: '.$e->getMessage());
        }

        return $next($request);
    }

    /**
     * Helper para respuestas 401 JSON
     */
    protected function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error'   => 'unauthorized',
            'message' => $message,
        ], 401);
    }
}
