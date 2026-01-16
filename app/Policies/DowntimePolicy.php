<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Downtime;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DowntimePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny downtime');
    }

    public function view(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('view downtime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create downtime');
    }

    public function update(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('update downtime');
    }

    public function delete(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('delete downtime');
    }

    public function restore(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('restore downtime');
    }

    public function forceDelete(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('forceDelete downtime');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny downtime');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny downtime');
    }

    public function replicate(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('replicate downtime');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder downtime');
    }
}
