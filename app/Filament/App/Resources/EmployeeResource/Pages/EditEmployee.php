<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Actions\Action;
// use Filament\Forms\Components\Actions\Action;
use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\EmployeeResource;
use App\Filament\Support\Forms\SuspensionTypeSchema;
use App\Filament\Support\Forms\TerminationTypeSchema;
use App\Filament\Support\Forms\TerminationReasonSchema;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
            Action::make('suspend')
                ->visible(fn (Model $record) => $record->status !== EmployeeStatus::Suspended)
                ->color('warning')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Select::make('suspension_type_id')
                        ->label('Suspension Type')
                        ->createOptionForm(SuspensionTypeSchema::toArray())
                        ->createOptionModalHeading('Add New Suspen Type')
                        ->relationship('suspensions.suspensionType', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\DatePicker::make('starts_at')
                        ->native(false)
                        ->default(now())
                        ->minDate(now()->subDays(10))
                        ->live()
                        ->required(),
                    Forms\Components\DatePicker::make('ends_at')
                        ->native(false)
                        ->default(now())
                        ->native(false)
                        ->live()
                        ->minDate(fn (Get $get) => $get('starts_at'))
                        ->required(),
                    Forms\Components\Textarea::make('comments')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->action(function (Model $record, $data) {
                    $record->suspensions()->create($data);
                }),

            Action::make('terminate')
                ->color('danger')
                ->visible(fn (Model $record) => $record->status !== EmployeeStatus::Inactive)
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Select::make('termination_type_id')
                        ->label('Termination type')
                        ->relationship('terminations.terminationType', 'name')
                        ->createOptionForm(TerminationTypeSchema::toArray())
                        ->createOptionModalHeading('Create Termination Type')
                        ->required(),
                    Forms\Components\Select::make('termination_reason_id')
                        ->label('Termination reason')
                        ->relationship('terminations.terminationReason', 'name')
                        ->createOptionForm(TerminationReasonSchema::toArray())
                        ->createOptionModalHeading('Create Termination Reason')
                        ->required(),
                    Forms\Components\DatePicker::make('date')
                        ->native(false)
                        ->default(now())
                        ->required(),
                    Forms\Components\Toggle::make('rehireable')
                        ->default(true)
                        ->required(),
                    Forms\Components\Textarea::make('comments')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->action(function (Model $record, $data) {
                    $record->terminations()->create($data);
                }),
            Action::make('reactivate')
                ->color('success')
                ->visible(fn (Model $record) => $record->status === EmployeeStatus::Inactive)
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\DateTimePicker::make('hired_at')
                        ->native(false)
                        ->default(now()->format('Y-m-d'))
                        ->maxDate(now()->addDays(10))
                        ->required(),
                ])
                ->action(function (Model $record, $data) {
                    $record->updateQuietly([
                        'hired_at' => $data['hired_at'],
                        'status' => EmployeeStatus::Current
                    ]);
                }),
        ];
    }
}
