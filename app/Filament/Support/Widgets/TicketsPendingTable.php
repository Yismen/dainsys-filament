<?php

namespace App\Filament\Support\Widgets;

use App\Actions\Filament\AssignTicketAction;
use App\Actions\Filament\CloseTicketAction;
use App\Actions\Filament\GrabTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use App\Filters\Filament\Support\TicketAgentsFilter;
use App\Filters\Filament\Support\TicketOwnersFilter;
use App\Infolists\Filament\Support\TicketInfolist;
use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TicketsPendingTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsManager() || Auth::user()->isTicketsAgent();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('expected_at', 'asc')
            ->query(fn (): Builder => Ticket::query()->incompleted())
            ->columns(TicketsTable::make())
            ->queryStringIdentifier(identifier: 'tickets_incompleted')
            ->paginationMode(PaginationMode::Default)
            ->filters([
                TrashedFilter::make(),
                TicketOwnersFilter::make(),
                TicketAgentsFilter::make(),
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->button()
                        ->schema([
                            Grid::make(2)
                                ->schema(TicketInfolist::make()),
                        ]),
                    GrabTicketAction::make(),
                    AssignTicketAction::make(),
                    CloseTicketAction::make(),
                ])
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
