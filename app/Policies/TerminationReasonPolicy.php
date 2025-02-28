<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TerminationReason;
use App\Models\User;

class TerminationReasonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TerminationReason');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('view TerminationReason');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TerminationReason');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('update TerminationReason');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('delete TerminationReason');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TerminationReason');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('restore TerminationReason');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TerminationReason');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('replicate TerminationReason');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TerminationReason');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TerminationReason $terminationreason): bool
    {
        return $user->checkPermissionTo('force-delete TerminationReason');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TerminationReason');
    }
}
