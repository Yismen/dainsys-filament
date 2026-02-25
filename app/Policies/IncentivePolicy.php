<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Incentive;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class IncentivePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny incentive');
    }

    public function view(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('view incentive');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create incentive');
    }

    public function update(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('update incentive');
    }

    public function delete(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('delete incentive');
    }

    public function restore(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('restore incentive');
    }

    public function forceDelete(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('forceDelete incentive');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny incentive');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny incentive');
    }

    public function replicate(AuthUser $authUser, Incentive $incentive): bool
    {
        return $authUser->can('replicate incentive');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder incentive');
    }
}
