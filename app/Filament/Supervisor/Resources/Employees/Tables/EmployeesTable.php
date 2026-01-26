<?php

namespace App\Filament\Supervisor\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Models\HRActivityRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BadgeColumn;
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
                BadgeColumn::make('status')
                    ->colors([
                        'success' => EmployeeStatuses::Hired,
                        'warning' => EmployeeStatuses::Suspended,
                        'info' => EmployeeStatuses::Created,
                    ]),
            ])
            ->actions([
                Action::make('requestActivity')
                    ->label('Request HR Activity')
                    ->icon('heroicon-o-paper-clip')
                    ->form([
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
            ]);
    }
}
