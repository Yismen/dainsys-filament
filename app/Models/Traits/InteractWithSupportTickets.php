<?php

namespace App\Models\Traits;

use App\Enums\SupportRoles;
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

    public function isTicketsManager(): bool
    {
        return $this->hasRole(SupportRoles::Manager->value);
    }

    public function isTicketsAgent(): bool
    {
        return $this->hasRole(SupportRoles::Agent->value);
    }
}
