<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Afp;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AfpPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny afp');
    }

    public function view(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('view afp');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create afp');
    }

    public function update(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('update afp');
    }

    public function delete(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('delete afp');
    }

    public function restore(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('restore afp');
    }

    public function forceDelete(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('forceDelete afp');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny afp');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny afp');
    }

    public function replicate(AuthUser $authUser, Afp $afp): bool
    {
        return $authUser->can('replicate afp');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder afp');
    }
}
