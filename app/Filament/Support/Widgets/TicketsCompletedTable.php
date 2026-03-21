<?php

namespace App\Filament\Support\Widgets;

use App\Actions\Filament\ReopenTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use App\Filters\Filament\Support\TicketAgentsFilter;
use App\Filters\Filament\Support\TicketOwnersFilter;
use App\Infolists\Filament\Support\TicketInfolist;
use App\Models\Ticket;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class TicketsCompletedTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '90s';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsManager();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('completed_at', 'desc')
            ->query(Ticket::query()->with(['replies.user'])->completed())
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
                    ->stickyModalFooter()
                    ->modalFooterActions([
                        ReopenTicketAction::make(),
                    ]),
            ])
            ->toolbarActions([
            ]);

    }
}
