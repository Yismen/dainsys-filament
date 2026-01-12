<?php

namespace App\Services;

use App\Enums\TicketRoles;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TicketRecipientsService
{
    protected Ticket $ticket;

    protected Collection $recipients;

    public function __construct()
    {
        $this->recipients = new Collection;
    }

    public function ofTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function get(): Collection
    {
        $recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            });

        return $recipients->filter(function ($user) {
            return $user->id !== auth()->user()?->id;
        });
    }

    public function superAdmins(): self
    {
        $super_admins = User::query()
            ->withWhereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', 'super_admin');
            })
            ->get();

        if ($super_admins->count()) {
            $this->recipients = $this->recipients
                ->merge($super_admins);
        }

        return $this;
    }

    public function ticketAdmins(): self
    {
        $admins = User::query()
            ->withWhereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', TicketRoles::Admin->value);
            })
            ->get();

        if ($admins->count()) {
            $this->recipients = $this->recipients
                ->merge($admins);
        }

        return $this;
    }

    public function ticketOperators(): self
    {
        $operators = User::query()
            ->withWhereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', TicketRoles::Operator->value);
            })
            ->get();

        if ($operators->count()) {
            $this->recipients = $this->recipients
                ->merge($operators);
        }

        return $this;
    }

    public function owner($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;
        $ticket->load('owner');

        $this->recipients = $this->recipients
            ->push($ticket->owner);

        return $this;
    }

    public function operator($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;
        $ticket->load('operator');

        $this->recipients = $this->recipients
            ->push($ticket->operator);

        return $this;
    }

    // public function agent($ticket = null): self
    // {
    //     return $this->operator($ticket);
    // }
}
