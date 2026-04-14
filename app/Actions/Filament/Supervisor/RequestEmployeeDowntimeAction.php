<?php

namespace App\Actions\Filament\Supervisor;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\DowntimeReason;
use App\Services\DowntimeService;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class RequestEmployeeDowntimeAction
{
    public static function make(string $name = 'requestDowntime'): Action
    {
        return Action::make('requestDowntime')
            ->label('Downtime')
            ->icon('heroicon-o-clock')
            ->color('warning')
            ->modalHeading('Request downtime')
            ->schema([
                Grid::make(2)
                    ->schema([
                        DatePicker::make('date')
                            ->default(now())
                            ->minDate(now()->subDays(20))
                            ->maxDate(now()->endOfDay())
                            ->required(),
                        Select::make('campaign_id')
                            ->options(ModelListService::get(model: Campaign::query()->where('revenue_type', RevenueTypes::Downtime)))
                            ->searchable()
                            ->required(),
                        Select::make('downtime_reason_id')
                            ->options(ModelListService::get(model: DowntimeReason::query()))
                            ->searchable()
                            ->required(),
                        TextInput::make('total_time')
                            ->required()
                            ->minValue(0)
                            ->maxValue(13)
                            ->numeric(),
                        Textarea::make('comment')
                            ->label('Comment')
                            ->nullable()
                            ->rows(3),
                    ]),
            ])
            ->action(function ($record, array $data): void {
                $supervisor = Auth::user()?->supervisor;

                if (! $supervisor) {
                    return;
                }

                DowntimeService::create(
                    employeeId: $record->id,
                    date: $data['date'],
                    campaignId: $data['campaign_id'],
                    downtimeReasonId: $data['downtime_reason_id'],
                    totalTime: $data['total_time'],
                    comment: $data['comment'] ?? null,
                );
            });

    }
}
