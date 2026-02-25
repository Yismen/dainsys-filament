<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\HRActivityRequest;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class HRActivityRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny hRActivityRequest');
    }

    public function view(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('view hRActivityRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create hRActivityRequest');
    }

    public function update(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('update hRActivityRequest');
    }

    public function delete(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('delete hRActivityRequest');
    }

    public function restore(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('restore hRActivityRequest');
    }

    public function forceDelete(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('forceDelete hRActivityRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny hRActivityRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny hRActivityRequest');
    }

    public function replicate(AuthUser $authUser, HRActivityRequest $hRActivityRequest): bool
    {
        return $authUser->can('replicate hRActivityRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder hRActivityRequest');
    }
}
