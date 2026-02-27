<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        // Allow any authenticated user to view articles
        return (bool) $authUser;
    }

    public function view(AuthUser $authUser): bool
    {
        // Allow any authenticated user to view a single article
        return (bool) $authUser;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create article');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('update article');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('delete article');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('restore article');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('force delete article');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('replicate article');
    }

    /**
     * Only admins can assign categories to articles
     */
    public function assignCategories(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }
}
