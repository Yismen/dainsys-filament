<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use App\Filament\Support\Resources\MyTickets\MyTicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMyTicket extends CreateRecord
{
    protected static string $resource = MyTicketResource::class;
}
