<?php

namespace App\Filament\Support\Resources\Tickets\Pages;

use App\Filament\Support\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
}
