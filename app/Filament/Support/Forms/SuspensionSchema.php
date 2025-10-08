<?php

namespace App\Filament\Support\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use Filament\Forms;
use App\Filament\Support\Forms\SuspensionTypeSchema;

final class SuspensionSchema
{
    public static function toArray(): array
    {
        return [
            Select::make('employee_id')
                ->relationship('employee', 'full_name')
                ->searchable()
                ->autofocus()
                ->createOptionForm(EmployeeSchema::toArray())
                ->createOptionModalHeading('Add New Employee')
                ->required(),
            Select::make('suspension_type_id')
                ->createOptionForm(SuspensionTypeSchema::toArray())
                ->createOptionModalHeading('Add New Suspen Type')
                ->relationship('suspensionType', 'name')
                ->searchable()
                ->preload()
                ->required(),
            DatePicker::make('starts_at')
                ->native(false)
                ->default(now())
                ->minDate(now()->subDays(10))
                ->live()
                ->required(),
            DatePicker::make('ends_at')
                ->native(false)
                ->default(now())
                ->live()
                ->minDate(fn (Get $get) => $get('starts_at'))
                ->required(),
            Textarea::make('comments')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
