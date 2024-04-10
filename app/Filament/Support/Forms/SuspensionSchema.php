<?php

namespace App\Filament\Support\Forms;

use Filament\Forms;
use Filament\Forms\Get;
use App\Filament\Support\Forms\SuspensionTypeSchema;

final class SuspensionSchema
{
    public static function toArray(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'full_name')
                ->searchable()
                ->autofocus()
                ->createOptionForm(EmployeeSchema::toArray())
                ->createOptionModalHeading('Add New Employee')
                ->required(),
            Forms\Components\Select::make('suspension_type_id')
                ->createOptionForm(SuspensionTypeSchema::toArray())
                ->createOptionModalHeading('Add New Suspen Type')
                ->relationship('suspensionType', 'name')
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
        ];
    }
}
