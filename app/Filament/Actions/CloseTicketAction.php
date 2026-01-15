<?php

namespace App\Filament\Actions;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;

class CloseTicketAction
{
    public static function make(string $name = 'finish'): Action
    {
        return Action::make($name)
            ->color(Color::Orange)
            ->visible(function (Ticket $record) {
                return $record->isOpen();
            })
            ->schema([
                Textarea::make('comment')
                    ->minLength(10)
                    ->required(),
            ])
            ->successNotificationTitle(fn (Ticket $record) => "Ticket {$record->reference} has been closed!")
            ->action(function (Ticket $record, array $data, $livewire) {
                $record->close($data['comment']);

                $livewire->dispatch('refreshRelationManagers');
            });
    }
}
