<?php

namespace App\Policies;

use App\Models\TicketReply;
use App\Models\User;

class TicketReplyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketReply');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('view TicketReply');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketReply');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('update TicketReply');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('delete TicketReply');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketReply');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('restore TicketReply');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketReply');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('replicate TicketReply');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketReply');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketReply $ticketreply): bool
    {
        return $user->checkPermissionTo('force-delete TicketReply');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketReply');
    }
}
