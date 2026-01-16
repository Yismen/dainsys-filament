<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SuspensionType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SuspensionTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny suspensionType');
    }

    public function view(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('view suspensionType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create suspensionType');
    }

    public function update(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('update suspensionType');
    }

    public function delete(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('delete suspensionType');
    }

    public function restore(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('restore suspensionType');
    }

    public function forceDelete(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('forceDelete suspensionType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny suspensionType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny suspensionType');
    }

    public function replicate(AuthUser $authUser, SuspensionType $suspensionType): bool
    {
        return $authUser->can('replicate suspensionType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder suspensionType');
    }
}
