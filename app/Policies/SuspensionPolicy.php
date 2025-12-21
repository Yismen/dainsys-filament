<?php

namespace App\Policies;

use App\Models\Suspension;
use App\Models\User;

class SuspensionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Suspension');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('view Suspension');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Suspension');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('update Suspension');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('delete Suspension');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Suspension');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('restore Suspension');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Suspension');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('replicate Suspension');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Suspension');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Suspension $suspension): bool
    {
        return $user->checkPermissionTo('force-delete Suspension');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Suspension');
    }
}
