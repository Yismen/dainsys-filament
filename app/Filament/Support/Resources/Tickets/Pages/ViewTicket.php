<?php

namespace App\Filament\Support\Resources\Tickets\Pages;

use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\ReopenTicketAction;
use App\Filament\Support\Resources\Tickets\TicketResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CloseTicketAction::make(),
            ReopenTicketAction::make(),
        ];
    }
}
