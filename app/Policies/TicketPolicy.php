<?php

namespace App\Policies;

use App\Enums\TicketRoles;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole([
            TicketRoles::Admin->value,
            // TicketRoles::Operator->value,
        ]) ||
        $ticket->owner_id === $user->id ||
        $ticket->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->owner_id && $ticket->isOpen();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->owner_id
            && $ticket->isOpen();
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $ticket->owner_id === $user->id;
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasRole(TicketRoles::Admin->value) || $user->id === $ticket->owner_id;
    }

    public function grab(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole([
            TicketRoles::Admin->value,
            TicketRoles::Operator->value,
        ]) && $user->id !== $ticket->owner_id;
    }

    public function reOpen(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole([
            TicketRoles::Admin->value,
            // TicketRoles::Operator->value,
        ]) || $user->id === $ticket->owner_id;
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole([
            TicketRoles::Admin->value,
            // TicketRoles::Operator->value,
        ]) || $user->id === $ticket->assigned_to;
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Ticket');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
