<?php

namespace App\Filament\Actions;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Downtime;
use App\Enums\TicketRoles;
use Filament\Actions\Action;
use App\Services\ModelListService;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;

class GrabTicketAction
{
    public static function make(string $name = 'grab'): Action
    {
        return Action::make($name)
            ->visible(function (Ticket $record) {
                return $record->assigned_to === null &&
                    Auth::user()->can('grab', $record);
            })
            ->button()
            ->color(Color::Lime)
            ->requiresConfirmation()
            ->successNotificationTitle("You are now assigned to this record")
            ->action(function (Ticket $record) {
                $record->grab();
            });
    }
}
