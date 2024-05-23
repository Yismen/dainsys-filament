<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            $model = str(request()->segment(2))->singular()->studly();

            return $user->hasAnyRole(['Super Admin', 'super admin', 'super-admin', 'super_admin']) || $user->hasAnyPermission([str($ability)->kebab() . ' ' . $model]);
        });
    }
}
