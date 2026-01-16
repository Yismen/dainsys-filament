<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use App\Filament\Actions\AssignTicketAction;
use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\GrabTicketAction;
use App\Filament\Actions\ReopenTicketAction;
use App\Filament\Support\Resources\MyTickets\MyTicketResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMyTicket extends ViewRecord
{
    protected static string $resource = MyTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CloseTicketAction::make(),
            ReopenTicketAction::make(),
            AssignTicketAction::make(),
            GrabTicketAction::make(),
        ];
    }
}
