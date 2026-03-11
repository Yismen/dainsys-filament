<?php

namespace App\Filament\Support\Widgets;

use App\Actions\Filament\AssignTicketAction;
use App\Actions\Filament\CloseTicketAction;
use App\Actions\Filament\GrabTicketAction;
use App\Actions\Filament\ReopenTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use App\Filters\Filament\Support\TicketAgentsFilter;
use App\Filters\Filament\Support\TicketOwnersFilter;
use App\Infolists\Filament\Support\TicketInfolist;
use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class TicketsCompletedTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsManager();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('completed_at', 'desc')
            ->query(Ticket::query()->completed())
            ->columns(TicketsTable::make())
            ->queryStringIdentifier('tickets_completed')
            ->paginationMode(PaginationMode::Default)
            ->filters([
                TrashedFilter::make(),
                TicketOwnersFilter::make(),
                TicketAgentsFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Grid::make(2)
                            ->schema(TicketInfolist::make()),
                    ])
                    ->modalFooterActions([
                        GrabTicketAction::make(),
                        AssignTicketAction::make(),
                        CloseTicketAction::make(),
                        ReopenTicketAction::make(),
                    ]),
            ])
            ->toolbarActions([
            ]);

    }
}
