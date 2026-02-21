<?php

namespace App\Filament\Supervisor\Resources\Employees;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\Employees\Pages\ListEmployees;
use App\Filament\Supervisor\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Supervisor\Resources\Employees\Schemas\EmployeeInfolist;
use App\Filament\Supervisor\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Team Management';

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeInfolist::configure($schema);
    }

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
            ->whereHas('supervisor', function ($query) use ($supervisor): void {
                $query->where('id', $supervisor->id);
            })
            ->whereNotIn('status', [EmployeeStatuses::Terminated]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            // 'view' => ViewEmployee::route('/{record}'),
        ];
    }
}
