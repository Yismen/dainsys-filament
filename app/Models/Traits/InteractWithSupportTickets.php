<?php

namespace App\Models\Traits;

use App\Enums\TicketRoles;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait InteractWithSupportTickets
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'owner_id');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function isTicketsAdmin(): bool
    {
        return $this->hasRole(TicketRoles::Admin->value);
    }

    public function isTicketsOperator(): bool
    {
        return $this->hasRole(TicketRoles::Operator->value);
    }
}
