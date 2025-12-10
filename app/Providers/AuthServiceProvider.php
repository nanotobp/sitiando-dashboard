<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Ability;
use App\Models\Role;
use Schema;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * ==========================================================
         *  SUPERADMIN — via roles pivot
         * ==========================================================
         */
        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });

        /**
         * ==========================================================
         *  REGISTRO AUTOMÁTICO DE ABILITIES DESDE BD
         * ==========================================================
         */
        if (Schema::hasTable('abilities')) {
            Ability::all()->each(function ($ability) {
                Gate::define($ability->key, function (User $user) use ($ability) {
                    return $user->roles()
                        ->whereHas('abilities', fn($q) =>
                            $q->where('abilities.id', $ability->id)
                        )
                        ->exists();
                });
            });
        }

        /**
         * ==========================================================
         *  ROLES BASE
         * ==========================================================
         */

        Gate::define('affiliate', function (User $user) {
            return $user->hasRole('affiliate') || $user->affiliate()->exists();
        });

        Gate::define('vendor', function (User $user) {
            return $user->hasRole('vendor');
        });

        Gate::define('customer', function (User $user) {
            return $user->hasRole('customer');
        });

        /**
         * ==========================================================
         *  SUPERADMIN OVERRIDE (siempre puede todo)
         * ==========================================================
         */
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
