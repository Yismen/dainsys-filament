<?php

namespace App\Filament\Support\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Enums\TicketRoles;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Enums\TicketStatuses;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Widgets\TableWidget;
use App\Services\ModelListService;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Actions\GrabTicketAction;
use App\Filament\Actions\AssignTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Enums\PaginationMode;

class TicketsPendingTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsAdmin() || Auth::user()->isTicketsOperator();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('expected_at', 'asc')
            ->query(fn(): Builder => Ticket::query()->incompleted())
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
                        ->url(fn (Ticket $record) => url(Filament::getCurrentPanel()->getId() . "/tickets", ['record' =>$record->getRouteKey()]))
                        ->openUrlInNewTab()
                        ,
                    GrabTicketAction::make(),
                    AssignTicketAction::make(),
                ])
                ->iconButton()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
