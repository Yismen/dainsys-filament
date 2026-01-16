<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Citizenship;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CitizenshipPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny citizenship');
    }

    public function view(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('view citizenship');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create citizenship');
    }

    public function update(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('update citizenship');
    }

    public function delete(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('delete citizenship');
    }

    public function restore(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('restore citizenship');
    }

    public function forceDelete(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('forceDelete citizenship');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny citizenship');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny citizenship');
    }

    public function replicate(AuthUser $authUser, Citizenship $citizenship): bool
    {
        return $authUser->can('replicate citizenship');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder citizenship');
    }
}
