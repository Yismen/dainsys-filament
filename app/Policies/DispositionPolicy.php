<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disposition;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DispositionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny disposition');
    }

    public function view(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('view disposition');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create disposition');
    }

    public function update(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('update disposition');
    }

    public function delete(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('delete disposition');
    }

    public function restore(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('restore disposition');
    }

    public function forceDelete(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('forceDelete disposition');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny disposition');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny disposition');
    }

    public function replicate(AuthUser $authUser, Disposition $disposition): bool
    {
        return $authUser->can('replicate disposition');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder disposition');
    }
}
