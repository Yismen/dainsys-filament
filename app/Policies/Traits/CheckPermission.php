<?php

namespace App\Policies\Traits;

use Illuminate\Foundation\Auth\User;

trait CheckPermission
{
    public function before(User $user, string $ability)
    {
        $model = str(request()->segment(2))->singular()->studly();
        $ability = str($ability)->kebab();

        return $user->hasAnyRole(['Super Admin', 'super admin']) || $user->hasAnyPermission([$ability . ' ' . $model]);
    }
}
