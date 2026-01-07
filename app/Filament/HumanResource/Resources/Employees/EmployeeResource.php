<?php

namespace App\Filament\HumanResource\Resources\Employees;

use App\Filament\HumanResource\Clusters\EmployeesManagement\EmployeesManagementCluster;
use App\Filament\HumanResource\Resources\Employees\Pages\CreateEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\EditEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\ListEmployees;
use App\Filament\HumanResource\Resources\Employees\Pages\ViewEmployee;
use App\Filament\HumanResource\Resources\Employees\RelationManagers\HiresRelationManager;
use App\Filament\HumanResource\Resources\Employees\RelationManagers\SocialSecuritiesRelationManager;
use App\Filament\HumanResource\Resources\Employees\RelationManagers\SuspensionsRelationManager;
use App\Filament\HumanResource\Resources\Employees\RelationManagers\TerminationsRelationManager;
use App\Filament\HumanResource\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\HumanResource\Resources\Employees\Schemas\EmployeeInfolist;
use App\Filament\HumanResource\Resources\Employees\Tables\EmployeesTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'full_name';

    protected static ?string $cluster = EmployeesManagementCluster::class;

    protected static ?int $navigationSort = 1;

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
            HiresRelationManager::class,
            SocialSecuritiesRelationManager::class,
            SuspensionsRelationManager::class,
            TerminationsRelationManager::class,
            // productions
            // Downtimes
            // Payroll hours
            // Login Names
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
