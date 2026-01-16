<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SocialSecurity;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SocialSecurityPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny socialSecurity');
    }

    public function view(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('view socialSecurity');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create socialSecurity');
    }

    public function update(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('update socialSecurity');
    }

    public function delete(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('delete socialSecurity');
    }

    public function restore(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('restore socialSecurity');
    }

    public function forceDelete(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('forceDelete socialSecurity');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny socialSecurity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny socialSecurity');
    }

    public function replicate(AuthUser $authUser, SocialSecurity $socialSecurity): bool
    {
        return $authUser->can('replicate socialSecurity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder socialSecurity');
    }
}
