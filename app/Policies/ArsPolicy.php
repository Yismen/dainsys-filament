<?php

namespace App\Policies;

use App\Models\Ars;
use App\Models\User;

class ArsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Ars');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('view Ars');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Ars');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('update Ars');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('delete Ars');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Ars');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('restore Ars');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Ars');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('replicate Ars');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Ars');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ars $ars): bool
    {
        return $user->checkPermissionTo('force-delete Ars');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Ars');
    }
}
