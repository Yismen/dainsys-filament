<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Site;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny site');
    }

    public function view(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('view site');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create site');
    }

    public function update(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('update site');
    }

    public function delete(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('delete site');
    }

    public function restore(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('restore site');
    }

    public function forceDelete(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('forceDelete site');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny site');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny site');
    }

    public function replicate(AuthUser $authUser, Site $site): bool
    {
        return $authUser->can('replicate site');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder site');
    }
}
