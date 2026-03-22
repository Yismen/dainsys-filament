<?php

namespace App\Actions\Filament\Ticket;

use App\Schemas\Filament\Support\TicketSchema;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Grid;

class EditTicketAction
{
    public static function make(string $name = 'assign'): EditAction
    {
        return EditAction::make()
            ->schema([
                Grid::make(2)
                    ->schema(TicketSchema::make()),
            ])
            ->extraModalFooterActions([
                CloseTicketAction::make(),
                ReopenTicketAction::make(),
                GrabTicketAction::make(),
                ReplyToTicketAction::make(),
                AssignTicketAction::make(),
            ])
            ->stickyModalHeader();
    }
}
