<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supabase Project URL
    |--------------------------------------------------------------------------
    |
    | URL base de tu proyecto Supabase. Por ahora es informativo,
    | pero puede servir para llamadas server-side en el futuro.
    |
    */

    'url' => env('SUPABASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase Anon Key
    |--------------------------------------------------------------------------
    |
    | Clave pública (anon) de Supabase. No la usamos para validar JWT,
    | sino para posibles llamadas HTTP desde el backend.
    |
    */

    'anon_key' => env('SUPABASE_ANON_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase JWT Secret
    |--------------------------------------------------------------------------
    |
    | Clave para verificar la firma de los tokens JWT de Supabase.
    | La sacás de Project Settings → API → JWT secret.
    |
    */

    'jwt_secret' => env('SUPABASE_JWT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase JWT Algorithm
    |--------------------------------------------------------------------------
    |
    | Algoritmo de firma usado por Supabase (por defecto HS256).
    |
    */

    'jwt_algo' => env('SUPABASE_JWT_ALGO', 'HS256'),
];
