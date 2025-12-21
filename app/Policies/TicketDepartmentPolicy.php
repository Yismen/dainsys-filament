<?php

namespace App\Policies;

use App\Models\TicketDepartment;
use App\Models\User;

class TicketDepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketDepartment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('view TicketDepartment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketDepartment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('update TicketDepartment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('delete TicketDepartment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketDepartment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('restore TicketDepartment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketDepartment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('replicate TicketDepartment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketDepartment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketDepartment $ticketdepartment): bool
    {
        return $user->checkPermissionTo('force-delete TicketDepartment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketDepartment');
    }
}
