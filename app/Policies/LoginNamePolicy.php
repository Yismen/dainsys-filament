<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LoginName;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class LoginNamePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny loginName');
    }

    public function view(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('view loginName');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create loginName');
    }

    public function update(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('update loginName');
    }

    public function delete(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('delete loginName');
    }

    public function restore(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('restore loginName');
    }

    public function forceDelete(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('forceDelete loginName');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny loginName');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny loginName');
    }

    public function replicate(AuthUser $authUser, LoginName $loginName): bool
    {
        return $authUser->can('replicate loginName');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder loginName');
    }
}
