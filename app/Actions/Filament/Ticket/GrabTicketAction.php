<?php

namespace App\Actions\Filament\Ticket;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class GrabTicketAction
{
    public static function make(string $name = 'grab'): Action
    {
        return Action::make($name)
            ->visible(function (Ticket $record) {
                return Auth::user()->can('grab', $record);
            })
            ->button()
            ->color(Color::Lime)
            ->icon(Heroicon::OutlinedHandRaised)
            ->requiresConfirmation()
            ->successNotificationTitle('You are now assigned to this record')
            ->action(function (Ticket $record): void {
                $record->grab();

                $record->load('owner', 'agent', 'replies.user');
            });
    }
}
