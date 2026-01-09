<?php

namespace App\Policies;

use App\Models\Downtime;
use App\Models\User;

class DowntimePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Downtime');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('view Downtime');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Downtime');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('update Downtime');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function aprove(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('aprove Downtime');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('delete Downtime');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Downtime');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('restore Downtime');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Downtime');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('replicate Downtime');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Downtime');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Downtime $position): bool
    {
        return $user->checkPermissionTo('force-delete Downtime');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Downtime');
    }
}
