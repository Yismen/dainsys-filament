<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\OvernightHour;
use App\Models\User;

class OvernightHourPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OvernightHour');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('view OvernightHour');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OvernightHour');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('update OvernightHour');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('delete OvernightHour');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any OvernightHour');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('restore OvernightHour');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any OvernightHour');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('replicate OvernightHour');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder OvernightHour');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OvernightHour $overnighthour): bool
    {
        return $user->checkPermissionTo('force-delete OvernightHour');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any OvernightHour');
    }
}
