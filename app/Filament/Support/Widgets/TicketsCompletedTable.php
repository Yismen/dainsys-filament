<?php

namespace App\Filament\Support\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Enums\TicketRoles;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Enums\TicketStatuses;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Actions\ViewAction;
use Filament\Widgets\TableWidget;
use App\Services\ModelListService;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\PaginationMode;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Support\Widgets\Tables\TicketsTable;

class TicketsCompletedTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(Ticket::query()->completed())
            ->columns(TicketsTable::make())
            ->queryStringIdentifier('tickets_completed')
            ->paginationMode(PaginationMode::Default)
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->button()
                    ->url(fn (Ticket $record) => url(Filament::getCurrentPanel()->getId() . "/tickets", ['record' =>$record->getRouteKey()]))
                    ->openUrlInNewTab()
                    ,
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);

    }
}
