<?php

namespace App\Actions\Filament;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ReplyToTicketAction
{
    public static function make(string $name = 'reply'): Action
    {
        return Action::make($name)
            ->color(Color::Cyan)
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->button()
            ->visible(function (Ticket $record) {
                return Auth::user()->can('reply', $record);
            })
            ->schema([
                Textarea::make('content')
                    ->minLength(5)
                    ->required(),
            ])
            ->successNotificationTitle(fn (Ticket $record) => "Ticket {$record->reference} has been replied!")
            ->action(function (Ticket $record, array $data, $livewire): void {
                $record->replies()->create([
                    'user_id' => Auth::id(),
                    'content' => $data['content'],
                ]);

                $livewire->dispatch('ticketRepliesUpdated', ticketId: (string) $record->getKey());
            });
    }
}
