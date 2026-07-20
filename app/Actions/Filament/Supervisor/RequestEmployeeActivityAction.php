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
            ->label(__('filament.hr_activity'))
            ->icon('heroicon-o-paper-clip')
            ->color('info')
            ->schema([
                Select::make('activity_type')
                    ->label(__('filament.activity_type'))
                    ->options(HRActivityTypes::class)
                    ->required(),
                Textarea::make('description')
                    ->label(__('filament.description'))
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
            ->successNotificationTitle(__('filament.hr_activity_request_created'));

    }
}
