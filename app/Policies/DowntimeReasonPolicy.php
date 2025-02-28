<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DowntimeReason;
use App\Models\User;

class DowntimeReasonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DowntimeReason');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('view DowntimeReason');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DowntimeReason');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('update DowntimeReason');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('delete DowntimeReason');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any DowntimeReason');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('restore DowntimeReason');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any DowntimeReason');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('replicate DowntimeReason');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder DowntimeReason');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DowntimeReason $downtimereason): bool
    {
        return $user->checkPermissionTo('force-delete DowntimeReason');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any DowntimeReason');
    }
}
