<?php

namespace App\Filament\Workforce\Resources\Employees;

use App\Filament\Workforce\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Workforce\Resources\Employees\Pages\EditEmployee;
use App\Filament\Workforce\Resources\Employees\Pages\ListEmployees;
use App\Filament\Workforce\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Workforce\Resources\Employees\RelationManagers\LoginNamesRelationManager;
use App\Filament\Workforce\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Workforce\Resources\Employees\Schemas\EmployeeInfolist;
use App\Filament\Workforce\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            LoginNamesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'view' => ViewEmployee::route('/{record}'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
