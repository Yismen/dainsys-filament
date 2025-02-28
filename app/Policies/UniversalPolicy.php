<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Universal;
use App\Models\User;

class UniversalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Universal');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('view Universal');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Universal');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('update Universal');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('delete Universal');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Universal');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('restore Universal');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Universal');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('replicate Universal');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Universal');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Universal $universal): bool
    {
        return $user->checkPermissionTo('force-delete Universal');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Universal');
    }
}
