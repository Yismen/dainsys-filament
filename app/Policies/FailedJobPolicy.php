<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use BinaryBuilds\FilamentFailedJobs\Models\FailedJob;
use Illuminate\Auth\Access\HandlesAuthorization;

class FailedJobPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny failedJob');
    }

    public function view(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('view failedJob');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create failedJob');
    }

    public function update(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('update failedJob');
    }

    public function delete(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('delete failedJob');
    }

    public function restore(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('restore failedJob');
    }

    public function forceDelete(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('forceDelete failedJob');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny failedJob');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny failedJob');
    }

    public function replicate(AuthUser $authUser, FailedJob $failedJob): bool
    {
        return $authUser->can('replicate failedJob');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder failedJob');
    }

}