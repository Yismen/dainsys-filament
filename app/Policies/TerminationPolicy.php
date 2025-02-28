<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Termination;
use App\Models\User;

class TerminationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Termination');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('view Termination');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Termination');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('update Termination');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('delete Termination');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Termination');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('restore Termination');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Termination');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('replicate Termination');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Termination');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Termination $termination): bool
    {
        return $user->checkPermissionTo('force-delete Termination');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Termination');
    }
}
