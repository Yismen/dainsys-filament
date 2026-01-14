<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\ReopenTicketAction;
use App\Filament\Support\Resources\MyTickets\MyTicketResource;

class ViewMyTicket extends ViewRecord
{
    protected static string $resource = MyTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CloseTicketAction::make(),
            ReopenTicketAction::make(),
        ];
    }
}
