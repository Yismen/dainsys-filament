<?php

namespace App\Filament\Supervisor\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\HRActivityRequest;
use App\Services\DowntimeService;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn ($record) => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->wrap()
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
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('requestActivity')
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
                        ->successNotificationTitle('HR Activity Request created successfully'),
                    Action::make('requestDowntime')
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
                        }),
                ]),
            ]);
    }
}
