<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Position;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PositionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny position');
    }

    public function view(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('view position');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create position');
    }

    public function update(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('update position');
    }

    public function delete(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('delete position');
    }

    public function restore(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('restore position');
    }

    public function forceDelete(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('forceDelete position');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny position');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny position');
    }

    public function replicate(AuthUser $authUser, Position $position): bool
    {
        return $authUser->can('replicate position');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder position');
    }
}
