<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Holiday;
use App\Models\User;

class HolidayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Holiday');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('view Holiday');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Holiday');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('update Holiday');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('delete Holiday');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Holiday');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('restore Holiday');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Holiday');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('replicate Holiday');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Holiday');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Holiday $holiday): bool
    {
        return $user->checkPermissionTo('force-delete Holiday');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Holiday');
    }
}
