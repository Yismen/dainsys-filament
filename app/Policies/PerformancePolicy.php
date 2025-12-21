<?php

namespace App\Policies;

use App\Models\Performance;
use App\Models\User;

class PerformancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Performance');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('view Performance');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Performance');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('update Performance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('delete Performance');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Performance');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('restore Performance');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Performance');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('replicate Performance');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Performance');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Performance $performance): bool
    {
        return $user->checkPermissionTo('force-delete Performance');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Performance');
    }
}
