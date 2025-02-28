<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TerminationType;
use App\Models\User;

class TerminationTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TerminationType');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('view TerminationType');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TerminationType');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('update TerminationType');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('delete TerminationType');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TerminationType');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('restore TerminationType');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TerminationType');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('replicate TerminationType');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TerminationType');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TerminationType $terminationtype): bool
    {
        return $user->checkPermissionTo('force-delete TerminationType');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TerminationType');
    }
}
