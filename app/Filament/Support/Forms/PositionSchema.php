<?php

namespace App\Filament\Support\Forms;

use Filament\Forms;
use App\Filament\Support\Forms\PaymentTypeSchema;

final class PositionSchema
{
    public static function toArray(): array
    {
        return [
            Forms\Components\Select::make('department_id')
                ->relationship('department', 'name')
                ->autofocus()
                ->required()
                ->createOptionModalHeading('New Department')
                ->createOptionForm(DepartmentSchema::toArray()),
            Forms\Components\Select::make('payment_type_id')
                ->relationship('paymentType', 'name')
                ->required()
                ->createOptionForm(PaymentTypeSchema::toArray())
                ->createOptionModalHeading('New Payment Type'),
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique()
                ->maxLength(500),
            Forms\Components\TextInput::make('salary')
                ->required()
                ->minValue(0)
                ->numeric(),
            Forms\Components\Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }
}
