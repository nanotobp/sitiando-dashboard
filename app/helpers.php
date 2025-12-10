<?php

if (! function_exists('userHasRole')) {
    function userHasRole(string $role): bool {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}

if (! function_exists('userHasAnyRole')) {
    function userHasAnyRole(...$roles): bool {
        return auth()->check() && auth()->user()->hasAnyRole($roles);
    }
}
