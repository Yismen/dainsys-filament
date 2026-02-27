<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CategoryAccessPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view category access');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('view category access');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create category access');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('update category access');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('delete category access');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('restore category access');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('force delete category access');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('replicate category access');
    }
}
