<?php

namespace App\Filament\Actions;

use App\Enums\TicketRoles;
use App\Models\Ticket;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

class AssignTicketAction
{
    public static function make(string $name = 'assign'): Action
    {
        return Action::make($name)
            ->visible(function (Ticket $record) {
                return Auth::user()->can('assign', $record);
            })
            ->button()
            ->size('sm')
            ->color(Color::Indigo)
            ->successNotificationTitle('Ticket has been assigned!')
            ->schema([
                Select::make('user_id')
                    ->searchable()
                    ->required()
                    ->options(
                        ModelListService::make(
                            User::query()
                                ->where('id', '!=', Auth::id())
                                ->where(function ($userQuery) {
                                    $userQuery->whereHas('roles', function ($rolesQuery) {
                                        $rolesQuery->whereIn('name', [
                                            TicketRoles::Admin->value,
                                            TicketRoles::Operator->value,
                                        ]);
                                    });
                                })
                        )
                    ),
            ])
            ->action(function (Ticket $record, array $data) {
                $record->assignTo($data['user_id']);
            });
    }
}
