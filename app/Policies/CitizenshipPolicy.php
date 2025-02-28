<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Citizenship;
use App\Models\User;

class CitizenshipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Citizenship');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('view Citizenship');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Citizenship');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('update Citizenship');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('delete Citizenship');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Citizenship');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('restore Citizenship');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Citizenship');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('replicate Citizenship');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Citizenship');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Citizenship $citizenship): bool
    {
        return $user->checkPermissionTo('force-delete Citizenship');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Citizenship');
    }
}
