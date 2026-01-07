<?php

namespace App\Filament\HumanResource\Resources\Hires;

use BackedEnum;
use App\Models\Hire;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\HumanResource\Resources\Hires\Pages\EditHire;
use App\Filament\HumanResource\Resources\Hires\Pages\ViewHire;
use App\Filament\HumanResource\Resources\Hires\Pages\ListHires;
use App\Filament\HumanResource\Resources\Hires\Pages\CreateHire;
use App\Filament\HumanResource\Resources\Hires\Schemas\HireForm;
use App\Filament\HumanResource\Resources\Hires\Tables\HiresTable;
use App\Filament\HumanResource\Resources\Hires\Schemas\HireInfolist;
use App\Filament\HumanResource\Clusters\EmployeesManagement\EmployeesManagementCluster;

class HireResource extends Resource
{
    protected static ?string $model = Hire::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $cluster = EmployeesManagementCluster::class;

    protected static ?int $navigationSort = 2;

    public static function getRecordTitle(?Model $record): string | Htmlable | null
    {
        return $record ? $record->employee->full_name : static::getModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return HireForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HireInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HiresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHires::route('/'),
            'create' => CreateHire::route('/create'),
            'view' => ViewHire::route('/{record}'),
            'edit' => EditHire::route('/{record}/edit'),
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
