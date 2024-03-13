<?php

namespace App\Filament\Support\Forms;

use Filament\Forms;
use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Support\Forms\PaymentTypeSchema;

final class PunchSchema
{
    public static function toArray(): array
    {
        return [
            Forms\Components\TextInput::make('punch')
                ->required()
                ->minLength(4)
                ->unique(ignoreRecord: true)
                ->maxLength(4),
            Forms\Components\Select::make('employee_id')
                ->relationship(
                    'employee',
                    'full_name',
                    modifyQueryUsing: fn (Builder $query, string $operation) => $query
                        ->where('status', '<>', EmployeeStatus::Inactive) // prevent from creating or assigning to inactive employees
                        ->when($operation === 'create', fn (Builder $query) => $query->doesntHave('punch')) // only load employees that does not have a punch
                )
                ->searchable()
                ->unique(ignoreRecord: true)
                ->preload()
                ->required(),
        ];
    }
}
