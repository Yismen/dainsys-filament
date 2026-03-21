<?php

namespace App\Filament\Support\Widgets;

use App\Actions\Filament\AssignTicketAction;
use App\Actions\Filament\CloseTicketAction;
use App\Actions\Filament\EditTicketAction;
use App\Actions\Filament\GrabTicketAction;
use App\Actions\Filament\ReplyToTicketAction;
use App\Filament\Support\Widgets\Tables\TicketsTable;
use App\Filters\Filament\Support\TicketAgentsFilter;
use App\Filters\Filament\Support\TicketOwnersFilter;
use App\Filters\Filament\Support\TicketStatusFilter;
use App\Infolists\Filament\Support\TicketInfolist;
use App\Models\Ticket;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class TicketsPendingTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '90s';

    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsManager() || Auth::user()->isTicketsAgent();
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('expected_at', 'asc')
            ->query(fn (): Builder => Ticket::query()->with(['replies.user'])->incompleted())
            ->columns(TicketsTable::make())
            ->queryStringIdentifier(identifier: 'tickets_incompleted')
            ->paginationMode(PaginationMode::Default)
            ->filters([
                TrashedFilter::make(),
                TicketOwnersFilter::make(),
                TicketAgentsFilter::make(),
                TicketStatusFilter::make(),
            ])
            ->headerActions([
                //
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
                        GrabTicketAction::make(),
                        AssignTicketAction::make(),
                        ReplyToTicketAction::make(),
                        CloseTicketAction::make(),
                    ]),
                EditTicketAction::make()
                    ->icon('heroicon-o-pencil')
                    ->visible(fn (Ticket $record) => Auth::user()->can('edit', $record)),
            ])
            ->toolbarActions([
            ]);
    }

    #[On('ticketRepliesUpdated')]
    public function ticketRepliesUpdated(string $ticketId): void
    {
        $mountedRecord = $this->getMountedAction()?->getRecord();

        if (! $mountedRecord instanceof Ticket) {
            return;
        }

        if ((string) $mountedRecord->getKey() !== $ticketId) {
            return;
        }

        $mountedRecord->load(['owner', 'agent', 'replies.user']);
    }
}
