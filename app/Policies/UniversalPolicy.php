<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Universal;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UniversalPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny universal');
    }

    public function view(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('view universal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create universal');
    }

    public function update(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('update universal');
    }

    public function delete(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('delete universal');
    }

    public function restore(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('restore universal');
    }

    public function forceDelete(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('forceDelete universal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny universal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny universal');
    }

    public function replicate(AuthUser $authUser, Universal $universal): bool
    {
        return $authUser->can('replicate universal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder universal');
    }
}
