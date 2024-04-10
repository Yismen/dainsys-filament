<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Get;
// use Filament\Forms\Components\Actions\Action;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\EmployeeResource;
use App\Filament\Support\Forms\SuspensionTypeSchema;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Action::make('suspend')
                ->color('warning')
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
                        ->default(now())
                        ->minDate(now()->subDays(10))
                        ->live()
                        ->required(),
                    Forms\Components\DatePicker::make('ends_at')
                        ->default(now())
                        ->live()
                        ->minDate(fn (Get $get) => $get('starts_at'))
                        ->required(),
                    Forms\Components\Textarea::make('comments')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->action(function (Model $record, $data) {
                    $record->suspensions()->create($data);
                })
        ];
    }
}
