<?php

namespace App\Filament\Supervisor\Resources\Employees\Tables;

use App\Enums\DowntimeStatuses;
use App\Enums\EmployeeStatuses;
use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Models\Campaign;
use App\Models\Comment;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\HRActivityRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cellphone')
                    ->label('Phone')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => EmployeeStatuses::Hired,
                        'warning' => EmployeeStatuses::Suspended,
                        'info' => EmployeeStatuses::Created,
                    ]),
            ])
            ->recordActions([
                Action::make('requestActivity')
                    ->label('Request HR Activity')
                    ->icon('heroicon-o-paper-clip')
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
                    ->successNotificationTitle('HR Activity Request created successfully'),
                Action::make('requestDowntime')
                    ->label('Request Downtime')
                    ->icon('heroicon-o-clock')
                    ->modalHeading('Request downtime')
                    ->schema([
                        DatePicker::make('date')->required(),
                        Select::make('campaign_id')
                            ->label('Campaign')
                            ->options(Campaign::query()->where('revenue_type', \App\Enums\RevenueTypes::Downtime)->pluck('name', 'id'))
                            ->required(),
                        Select::make('downtime_reason_id')
                            ->label('Reason')
                            ->options(DowntimeReason::query()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('total_time')
                            ->label('Total time (hours)')
                            ->numeric()
                            ->minValue(0.25)
                            ->step(0.25)
                            ->required(),
                        Textarea::make('comment')
                            ->label('Comment')
                            ->nullable()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data): void {
                        $supervisor = Auth::user()?->supervisor;

                        if (! $supervisor) {
                            return;
                        }

                        $downtime = Downtime::create([
                            'employee_id' => $record->id,
                            'campaign_id' => $data['campaign_id'],
                            'downtime_reason_id' => $data['downtime_reason_id'],
                            'date' => $data['date'],
                            'total_time' => $data['total_time'],
                            'status' => DowntimeStatuses::Pending,
                        ]);

                        if (! empty($data['comment'])) {
                            Comment::query()->forceCreate([
                                'text' => $data['comment'],
                                'commentable_id' => $downtime->id,
                                'commentable_type' => Downtime::class,
                            ]);
                        }
                    })
                    ->successNotificationTitle('Downtime requested successfully'),
            ])
            ->bulkActions([
                BulkAction::make('requestDowntimes')
                    ->label('Request Downtimes')
                    ->icon('heroicon-o-clock')
                    ->modalHeading('Request downtimes for selected')
                    ->form([
                        DatePicker::make('date')->required(),
                        Select::make('campaign_id')
                            ->label('Campaign')
                            ->options(Campaign::query()->where('revenue_type', \App\Enums\RevenueTypes::Downtime)->pluck('name', 'id'))
                            ->required(),
                        Select::make('downtime_reason_id')
                            ->label('Reason')
                            ->options(DowntimeReason::query()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('total_time')
                            ->label('Total time (hours)')
                            ->numeric()
                            ->minValue(0.25)
                            ->step(0.25)
                            ->required(),
                        Textarea::make('comment')
                            ->label('Comment')
                            ->nullable()
                            ->rows(3),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        $supervisor = Auth::user()?->supervisor;

                        if (! $supervisor) {
                            return;
                        }

                        $records->each(function ($employee) use ($data) {
                            $downtime = Downtime::create([
                                'employee_id' => $employee->id,
                                'campaign_id' => $data['campaign_id'],
                                'downtime_reason_id' => $data['downtime_reason_id'],
                                'date' => $data['date'],
                                'total_time' => $data['total_time'],
                                'status' => DowntimeStatuses::Pending,
                            ]);

                            if (! empty($data['comment'])) {
                                Comment::query()->forceCreate([
                                    'text' => $data['comment'],
                                    'commentable_id' => $downtime->id,
                                    'commentable_type' => Downtime::class,
                                ]);
                            }
                        });
                    })
                    ->successNotificationTitle('Downtimes requested successfully'),
            ]);
    }
}
