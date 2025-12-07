<?php

if (! function_exists('userHasRole')) {
    function userHasRole($role) {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}

if (! function_exists('userHasAnyRole')) {
    function userHasAnyRole(...$roles) {
        if (!auth()->check()) return false;

        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}
