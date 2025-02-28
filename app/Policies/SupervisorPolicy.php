<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Supervisor;
use App\Models\User;

class SupervisorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Supervisor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('view Supervisor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Supervisor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('update Supervisor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('delete Supervisor');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Supervisor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('restore Supervisor');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Supervisor');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('replicate Supervisor');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Supervisor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Supervisor $supervisor): bool
    {
        return $user->checkPermissionTo('force-delete Supervisor');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Supervisor');
    }
}
