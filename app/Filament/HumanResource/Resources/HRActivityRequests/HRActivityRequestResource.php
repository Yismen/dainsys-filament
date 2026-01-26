<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests;

use App\Filament\HumanResource\Clusters\EmployeesManagement\EmployeesManagementCluster;
use App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ListHRActivityRequests;
use App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ViewHRActivityRequest;
use App\Filament\HumanResource\Resources\HRActivityRequests\Schemas\HRActivityRequestForm;
use App\Filament\HumanResource\Resources\HRActivityRequests\Schemas\HRActivityRequestInfolist;
use App\Filament\HumanResource\Resources\HRActivityRequests\Tables\HRActivityRequestsTable;
use App\Models\HRActivityRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HRActivityRequestResource extends Resource
{
    protected static ?string $model = HRActivityRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = EmployeesManagementCluster::class;

    protected static ?string $navigationLabel = 'HR Activity Requests';

    protected static ?string $modelLabel = 'HR Activity Request';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return HRActivityRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HRActivityRequestsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HRActivityRequestInfolist::configure($schema);
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
            'index' => ListHRActivityRequests::route('/'),
            'view' => ViewHRActivityRequest::route('/{record}'),
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
