<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Campaign');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('view Campaign');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Campaign');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('update Campaign');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('delete Campaign');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Campaign');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('restore Campaign');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Campaign');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('replicate Campaign');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Campaign');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $user->checkPermissionTo('force-delete Campaign');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Campaign');
    }
}
