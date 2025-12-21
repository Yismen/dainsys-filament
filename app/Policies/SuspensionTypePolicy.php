<?php

namespace App\Policies;

use App\Models\SuspensionType;
use App\Models\User;

class SuspensionTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SuspensionType');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('view SuspensionType');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SuspensionType');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('update SuspensionType');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('delete SuspensionType');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SuspensionType');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('restore SuspensionType');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SuspensionType');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('replicate SuspensionType');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SuspensionType');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SuspensionType $suspensiontype): bool
    {
        return $user->checkPermissionTo('force-delete SuspensionType');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SuspensionType');
    }
}
