<?php

namespace App\Policies;

use App\Models\Production;
use App\Models\User;

class ProductionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Production');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('view Production');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Production');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('update Production');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('delete Production');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Production');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('restore Production');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Production');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('replicate Production');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Production');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Production $project): bool
    {
        return $user->checkPermissionTo('force-delete Production');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Production');
    }
}
