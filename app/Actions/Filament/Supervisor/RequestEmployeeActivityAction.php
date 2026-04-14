<?php

namespace App\Actions\Filament\Supervisor;

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Models\HRActivityRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class RequestEmployeeActivityAction
{
    public static function make(string $name = 'requestActivity'): Action
    {
        return Action::make($name)
            ->label('HR Activity')
            ->icon('heroicon-o-paper-clip')
            ->color('info')
            ->schema([
                Select::make('activity_type')
                    ->label('Activity Type')
                    ->options(HRActivityTypes::class)
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
            ])
            ->action(function ($record, array $data): void {
                $supervisor = Auth::user()?->supervisor;

                if ($supervisor) {
                    HRActivityRequest::create([
                        'employee_id' => $record->id,
                        'supervisor_id' => $supervisor->id,
                        'activity_type' => $data['activity_type'],
                        'status' => HRActivityRequestStatuses::Requested,
                        'description' => $data['description'] ?? null,
                        'requested_at' => now(),
                    ]);
                }
            })
            ->successNotificationTitle('HR Activity Request created successfully');

    }
}
