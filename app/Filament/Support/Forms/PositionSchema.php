<?php

namespace App\Filament\Support\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms;
use App\Filament\Support\Forms\PaymentTypeSchema;

final class PositionSchema
{
    public static function toArray(): array
    {
        return [
            Select::make('department_id')
                ->relationship('department', 'name')
                ->autofocus()
                ->required()
                ->searchable()
                ->preload()
                ->createOptionModalHeading('New Department')
                ->createOptionForm(DepartmentSchema::toArray()),
            Select::make('payment_type_id')
                ->relationship('paymentType', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->createOptionForm(PaymentTypeSchema::toArray())
                ->createOptionModalHeading('New Payment Type'),
            TextInput::make('name')
                ->required()
                ->unique()
                ->maxLength(500),
            TextInput::make('salary')
                ->required()
                ->prefix('$')
                ->minValue(0)
                ->numeric(),
            Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
