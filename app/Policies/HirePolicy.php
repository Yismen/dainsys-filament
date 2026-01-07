<?php

namespace App\Policies;

use App\Models\Hire;
use App\Models\User;

class HirePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Hire');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('view Hire');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Hire');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('update Hire');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('delete Hire');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Hire');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('restore Hire');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Hire');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('replicate Hire');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Hire');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hire $termination): bool
    {
        return $user->checkPermissionTo('force-delete Hire');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Hire');
    }
}
