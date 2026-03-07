<?php

namespace App\Actions\Filament;

use App\Enums\SupportRoles;
use App\Models\Ticket;
use App\Models\User;
use App\Schemas\Filament\Support\TicketSchema;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

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
                AssignTicketAction::make(),
            ])
            ->stickyModalHeader();
    }
}
