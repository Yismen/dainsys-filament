<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use App\Filament\Actions\AssignTicketAction;
use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\GrabTicketAction;
use App\Filament\Actions\ReopenTicketAction;
use App\Filament\Support\Resources\MyTickets\MyTicketResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

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
            AssignTicketAction::make(),
            GrabTicketAction::make(),
        ];
    }
}
