<?php

namespace App\Filament\Support\Resources\Tickets\Pages;

use App\Actions\Filament\AssignTicketAction;
use App\Actions\Filament\CloseTicketAction;
use App\Actions\Filament\GrabTicketAction;
use App\Actions\Filament\ReopenTicketAction;
use App\Filament\Support\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CloseTicketAction::make(),
            ReopenTicketAction::make(),
            AssignTicketAction::make(),
            GrabTicketAction::make(),
        ];
    }
}
