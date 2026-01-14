<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\ReopenTicketAction;
use App\Filament\Support\Resources\MyTickets\MyTicketResource;

class EditMyTicket extends EditRecord
{
    protected static string $resource = MyTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            CloseTicketAction::make(),
            ReopenTicketAction::make(),
        ];
    }
}
