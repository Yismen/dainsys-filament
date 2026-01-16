<?php

namespace App\Filament\Support\Widgets;

use App\Filament\Actions\AssignTicketAction;
use App\Filament\Actions\CloseTicketAction;
use App\Filament\Actions\GrabTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Facades\Filament;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TicketsPendingTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsAdmin() || Auth::user()->isTicketsOperator();
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
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->button()
                        ->url(fn (Ticket $record) => url(Filament::getCurrentPanel()->getId().'/tickets', ['record' => $record->getRouteKey()]))
                        ->openUrlInNewTab(),
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
