<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Hire;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class HirePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny hire');
    }

    public function view(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('view hire');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create hire');
    }

    public function update(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('update hire');
    }

    public function delete(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('delete hire');
    }

    public function restore(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('restore hire');
    }

    public function forceDelete(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('forceDelete hire');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny hire');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny hire');
    }

    public function replicate(AuthUser $authUser, Hire $hire): bool
    {
        return $authUser->can('replicate hire');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder hire');
    }
}
