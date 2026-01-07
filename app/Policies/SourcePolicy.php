<?php

namespace App\Policies;

use App\Models\Source;
use App\Models\User;

class SourcePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Source');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('view Source');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Source');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('update Source');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('delete Source');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Source');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('restore Source');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Source');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('replicate Source');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Source');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Source $project): bool
    {
        return $user->checkPermissionTo('force-delete Source');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Source');
    }
}
