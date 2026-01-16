<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Supervisor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SupervisorPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny supervisor');
    }

    public function view(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('view supervisor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create supervisor');
    }

    public function update(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('update supervisor');
    }

    public function delete(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('delete supervisor');
    }

    public function restore(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('restore supervisor');
    }

    public function forceDelete(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('forceDelete supervisor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny supervisor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny supervisor');
    }

    public function replicate(AuthUser $authUser, Supervisor $supervisor): bool
    {
        return $authUser->can('replicate supervisor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder supervisor');
    }
}
