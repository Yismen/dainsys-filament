<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\NightlyHour;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class NightlyHourPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny nightlyHour');
    }

    public function view(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('view nightlyHour');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create nightlyHour');
    }

    public function update(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('update nightlyHour');
    }

    public function delete(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('delete nightlyHour');
    }

    public function restore(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('restore nightlyHour');
    }

    public function forceDelete(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('forceDelete nightlyHour');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny nightlyHour');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny nightlyHour');
    }

    public function replicate(AuthUser $authUser, NightlyHour $nightlyHour): bool
    {
        return $authUser->can('replicate nightlyHour');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder nightlyHour');
    }
}
