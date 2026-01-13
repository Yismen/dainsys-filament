<?php

namespace App\Filament\Actions;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;

class ReopenTicketAction
{
    public static function make(string $name = 're-open'): Action
    {
        return
            Action::make(name: 'reOpen')
                ->color(Color::Blue)
                ->visible(function (Ticket $record) {
                    return $record->isOpen() === false;
                })
                ->schema([
                    Textarea::make('comment')
                        ->minLength(10)
                        ->required(),
                ])
                ->successNotificationTitle(fn (Ticket $record) => "Ticket {$record->reference} has been closed!")
                ->action(function (Ticket $record, array $data, $livewire) {
                    $record->reOpen($data['comment']);

                    $livewire->dispatch('refreshRelationManagers');
                });
    }
}
