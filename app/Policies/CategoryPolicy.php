<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view category');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('view category');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create category');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('update category');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('delete category');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('restore category');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('force delete category');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('replicate category');
    }
}
