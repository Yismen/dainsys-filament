<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Absence;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AbsencePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny absence');
    }

    public function view(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('view absence');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create absence');
    }

    public function update(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('update absence');
    }

    public function delete(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('delete absence');
    }

    public function restore(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('restore absence');
    }

    public function forceDelete(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('forceDelete absence');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny absence');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny absence');
    }

    public function replicate(AuthUser $authUser, Absence $absence): bool
    {
        return $authUser->can('replicate absence');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder absence');
    }
}
