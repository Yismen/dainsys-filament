<?php

namespace App\Actions\Filament;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ReopenTicketAction
{
    public static function make(string $name = 're-open'): Action
    {
        return
            Action::make(name: 'reOpen')
                ->color(Color::Blue)
                ->button()
                ->icon(Heroicon::OutlinedArrowPath)
                ->visible(function (Ticket $record) {
                    return Auth::user()->can('reopen', $record);
                })
                ->schema([
                    Textarea::make('comment')
                        ->minLength(5)
                        ->required(),
                ])
                ->successNotificationTitle(fn (Ticket $record) => "Ticket {$record->reference} has been reopened!")
                ->action(function (Ticket $record, array $data, $livewire): void {
                    $record->reOpen($data['comment']);

                    $livewire->dispatch('refreshRelationManagers');
                });
    }
}
