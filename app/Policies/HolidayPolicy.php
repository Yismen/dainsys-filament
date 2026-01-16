<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Holiday;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class HolidayPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny holiday');
    }

    public function view(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('view holiday');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create holiday');
    }

    public function update(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('update holiday');
    }

    public function delete(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('delete holiday');
    }

    public function restore(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('restore holiday');
    }

    public function forceDelete(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('forceDelete holiday');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny holiday');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny holiday');
    }

    public function replicate(AuthUser $authUser, Holiday $holiday): bool
    {
        return $authUser->can('replicate holiday');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder holiday');
    }
}
