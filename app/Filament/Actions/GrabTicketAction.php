<?php

namespace App\Filament\Actions;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
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
            ->requiresConfirmation()
            ->successNotificationTitle('You are now assigned to this record')
            ->action(function (Ticket $record) {
                $record->grab();
            });
    }
}
