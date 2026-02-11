<?php

namespace App\Filament\Actions;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

class CloseTicketAction
{
    public static function make(string $name = 'finish'): Action
    {
        return Action::make($name)
            ->color(Color::Orange)
            ->button()
            ->visible(function (Ticket $record) {
                return Auth::user()->can('close', $record);
            })
            ->schema([
                Textarea::make('comment')
                    ->minLength(5)
                    ->required(),
            ])
            ->successNotificationTitle(fn (Ticket $record) => "Ticket {$record->reference} has been closed!")
            ->action(function (Ticket $record, array $data, $livewire): void {
                $record->close($data['comment']);

                $livewire->dispatch('refreshRelationManagers');
            });
    }
}
