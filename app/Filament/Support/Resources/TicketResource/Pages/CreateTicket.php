<?php

namespace App\Filament\Support\Resources\TicketResource\Pages;

use App\Filament\Support\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
}
