<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Suspension;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SuspensionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny suspension');
    }

    public function view(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('view suspension');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create suspension');
    }

    public function update(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('update suspension');
    }

    public function delete(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('delete suspension');
    }

    public function restore(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('restore suspension');
    }

    public function forceDelete(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('forceDelete suspension');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny suspension');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny suspension');
    }

    public function replicate(AuthUser $authUser, Suspension $suspension): bool
    {
        return $authUser->can('replicate suspension');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder suspension');
    }
}
