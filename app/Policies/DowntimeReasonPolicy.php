<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DowntimeReason;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DowntimeReasonPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny downtimeReason');
    }

    public function view(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('view downtimeReason');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create downtimeReason');
    }

    public function update(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('update downtimeReason');
    }

    public function delete(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('delete downtimeReason');
    }

    public function restore(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('restore downtimeReason');
    }

    public function forceDelete(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('forceDelete downtimeReason');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny downtimeReason');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny downtimeReason');
    }

    public function replicate(AuthUser $authUser, DowntimeReason $downtimeReason): bool
    {
        return $authUser->can('replicate downtimeReason');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder downtimeReason');
    }
}
