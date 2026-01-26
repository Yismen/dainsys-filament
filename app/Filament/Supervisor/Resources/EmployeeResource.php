<?php

namespace App\Filament\Supervisor\Resources;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\Employees\Pages\ListEmployees;
use App\Filament\Supervisor\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Supervisor\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('hires', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id);
            })
            ->whereNotIn('status', [EmployeeStatuses::Terminated]);
    }

    public static function canAccess(): bool
    {
        $supervisor = Auth::user()?->supervisor;

        return $supervisor?->is_active === true;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'view' => ViewEmployee::route('/{record}'),
        ];
    }
}
